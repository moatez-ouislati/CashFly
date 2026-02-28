const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const path = require('path');
const mysql = require('mysql2/promise');

const app = express();
const server = http.createServer(app);

const dbConfig = {
    host: process.env.DB_HOST || 'localhost',
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'cashflydb',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
};

const pool = mysql.createPool(dbConfig);

app.use((req, res, next) => {
    res.header('Access-Control-Allow-Origin', '*');
    res.header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    res.header('Access-Control-Allow-Headers', '*');
    next();
});

app.use(express.static(path.join(__dirname, 'public')));

app.get('/health', (req, res) => {
    res.json({ status: 'ok', timestamp: new Date() });
});

const io = socketIo(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"],
        allowedHeaders: ["*"],
        credentials: false
    },
    transports: ['polling', 'websocket']
});

io.on('connection', (socket) => {

    socket.on('authenticate', async (data) => {
        const { token, eventId } = data;

        try {
            const parts = token.split('.');
            const payload = JSON.parse(Buffer.from(parts[1], 'base64').toString());

            socket.userId = payload.id;
            socket.userName = payload.name;
            socket.userRole = payload.role;
            socket.eventId = eventId;
            socket.join(`event_${eventId}`);

            // Load messages with reply info
            const [messages] = await pool.execute(
                `SELECT m.*, u.nom_complet as user_name, u.role as user_role,
                        rm.id_message as reply_id, rm.message as reply_content, ru.nom_complet as reply_user_name
                 FROM chat_messages m
                          JOIN utilisateurs u ON m.id_utilisateur = u.id_utilisateur
                          LEFT JOIN chat_messages rm ON m.reply_to = rm.id_message
                          LEFT JOIN utilisateurs ru ON rm.id_utilisateur = ru.id_utilisateur
                 WHERE m.id_room = ?
                 ORDER BY m.created_at ASC
                     LIMIT 50`,
                [eventId]
            );

            socket.emit('recent_messages', messages.map(msg => ({
                id: msg.id_message,
                content: msg.message,
                userId: msg.id_utilisateur,
                userName: msg.user_name + (msg.user_role === 'proprietaire' ? ' 👑' : ''),
                userRole: msg.user_role,
                timestamp: msg.created_at,
                isEdited: msg.is_edited,
                editedAt: msg.edited_at,
                replyTo: msg.reply_id ? {
                    id: msg.reply_id,
                    content: msg.reply_content,
                    userName: msg.reply_user_name
                } : null
            })));

            await pool.execute(
                `INSERT INTO chat_presence (id_room, id_utilisateur, last_seen, is_online)
                 VALUES (?, ?, NOW(), 1)
                     ON DUPLICATE KEY UPDATE last_seen = NOW(), is_online = 1`,
                [eventId, payload.id]
            );

            socket.to(`event_${eventId}`).emit('user_joined', {
                userId: payload.id,
                userName: payload.name
            });

            const [onlineUsers] = await pool.execute(
                `SELECT u.id_utilisateur as userId, u.nom_complet as userName
                 FROM chat_presence p
                          JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
                 WHERE p.id_room = ? AND p.is_online = 1`,
                [eventId]
            );
            io.to(`event_${eventId}`).emit('online_users', onlineUsers);

        } catch (err) {
            console.error('Authentication error:', err);
            socket.emit('error', { message: 'Auth failed' });
        }
    });

    socket.on('send_message', async (data) => {
        if (!socket.userId || !socket.eventId) {
            socket.emit('error', { message: 'Not authenticated' });
            return;
        }

        const { content, replyTo } = data;

        if (!content || content.trim().length === 0) {
            socket.emit('error', { message: 'Empty message' });
            return;
        }

        let replyToId = null;
        if (replyTo !== undefined && replyTo !== null && replyTo !== '') {
            replyToId = parseInt(replyTo, 10);
            if (isNaN(replyToId) || replyToId <= 0) {
                replyToId = null;
            }
        }

        try {
            if (replyToId) {
                const [replyCheck] = await pool.execute(
                    'SELECT id_message FROM chat_messages WHERE id_message = ? AND id_room = ?',
                    [replyToId, socket.eventId]
                );
                if (replyCheck.length === 0) {
                    socket.emit('error', { message: 'Reply message not found' });
                    return;
                }
            }

            const [result] = await pool.execute(
                `INSERT INTO chat_messages (id_room, id_utilisateur, message, type, reply_to, created_at)
                 VALUES (?, ?, ?, 'text', ?, NOW())`,
                [socket.eventId, socket.userId, content.trim(), replyToId]
            );

            const [newMessage] = await pool.execute(
                `SELECT m.*, u.nom_complet as user_name, u.role as user_role,
                        rm.id_message as reply_id, rm.message as reply_content, ru.nom_complet as reply_user_name
                 FROM chat_messages m
                          JOIN utilisateurs u ON m.id_utilisateur = u.id_utilisateur
                          LEFT JOIN chat_messages rm ON m.reply_to = rm.id_message
                          LEFT JOIN utilisateurs ru ON rm.id_utilisateur = ru.id_utilisateur
                 WHERE m.id_message = ?`,
                [result.insertId]
            );

            const msg = newMessage[0];
            const message = {
                id: msg.id_message,
                content: msg.message,
                userId: socket.userId,
                userName: socket.userName,
                userRole: socket.userRole,
                timestamp: new Date(),
                isEdited: msg.is_edited,
                editedAt: msg.edited_at,
                replyTo: msg.reply_id ? {
                    id: msg.reply_id,
                    content: msg.reply_content,
                    userName: msg.reply_user_name
                } : null
            };

            io.to(`event_${socket.eventId}`).emit('new_message', message);

        } catch (dbErr) {
            console.error('Database error:', dbErr);
            socket.emit('error', { message: 'Failed to save message' });
        }
    });

    socket.on('edit_message', async (data) => {
        if (!socket.userId || !socket.eventId) {
            socket.emit('error', { message: 'Not authenticated' });
            return;
        }

        const { messageId, newContent } = data;

        if (!messageId || !newContent || newContent.trim().length === 0) {
            socket.emit('error', { message: 'Invalid edit data' });
            return;
        }

        const parsedMessageId = parseInt(messageId, 10);
        if (isNaN(parsedMessageId) || parsedMessageId <= 0) {
            socket.emit('error', { message: 'Invalid message ID' });
            return;
        }

        try {
            const [messageCheck] = await pool.execute(
                `SELECT id_message, id_utilisateur, id_room FROM chat_messages
                 WHERE id_message = ? AND id_room = ?`,
                [parsedMessageId, socket.eventId]
            );

            if (messageCheck.length === 0) {
                socket.emit('error', { message: 'Message not found' });
                return;
            }

            const message = messageCheck[0];

            if (message.id_utilisateur !== socket.userId) {
                socket.emit('error', { message: 'You can only edit your own messages' });
                return;
            }

            await pool.execute(
                `UPDATE chat_messages
                 SET message = ?, is_edited = 1, edited_at = NOW()
                 WHERE id_message = ?`,
                [newContent.trim(), parsedMessageId]
            );

            const [updatedMessage] = await pool.execute(
                `SELECT m.*, u.nom_complet as user_name, u.role as user_role,
                        rm.id_message as reply_id, rm.message as reply_content, ru.nom_complet as reply_user_name
                 FROM chat_messages m
                          JOIN utilisateurs u ON m.id_utilisateur = u.id_utilisateur
                          LEFT JOIN chat_messages rm ON m.reply_to = rm.id_message
                          LEFT JOIN utilisateurs ru ON rm.id_utilisateur = ru.id_utilisateur
                 WHERE m.id_message = ?`,
                [parsedMessageId]
            );

            const msg = updatedMessage[0];
            const broadcastData = {
                id: msg.id_message,
                content: msg.message,
                userId: msg.id_utilisateur,
                userName: msg.user_name + (msg.user_role === 'proprietaire' ? ' 👑' : ''),
                userRole: msg.user_role,
                timestamp: msg.created_at,
                isEdited: msg.is_edited,
                editedAt: msg.edited_at,
                replyTo: msg.reply_id ? {
                    id: msg.reply_id,
                    content: msg.reply_content,
                    userName: msg.reply_user_name
                } : null
            };

            io.to(`event_${socket.eventId}`).emit('message_edited', broadcastData);
            socket.emit('edit_confirmed', { messageId: parsedMessageId });

        } catch (dbErr) {
            console.error('Edit message error:', dbErr);
            socket.emit('error', { message: 'Failed to edit message' });
        }
    });

    socket.on('delete_message', async (data) => {

        if (!socket.userId || !socket.eventId) {
            socket.emit('error', { message: 'Not authenticated' });
            return;
        }

        const { messageId } = data;
        const parsedMessageId = parseInt(messageId, 10);

        if (isNaN(parsedMessageId) || parsedMessageId <= 0) {
            socket.emit('error', { message: 'Invalid message ID' });
            return;
        }

        try {
            const [messageCheck] = await pool.execute(
                `SELECT id_utilisateur, message FROM chat_messages
                 WHERE id_message = ? AND id_room = ?`,
                [parsedMessageId, socket.eventId]
            );

            if (messageCheck.length === 0) {
                socket.emit('error', { message: 'Message not found' });
                return;
            }

            if (messageCheck[0].id_utilisateur !== socket.userId) {
                socket.emit('error', { message: 'You can only delete your own messages' });
                return;
            }

            await pool.execute(
                `UPDATE chat_messages
                 SET message = '[Message supprimé]', is_edited = 2, edited_at = NOW(), status = 'deleted'
                 WHERE id_message = ?`,
                [parsedMessageId]
            );

            io.to(`event_${socket.eventId}`).emit('message_deleted', {
                id: parsedMessageId,
                content: '[Message supprimé]',
                isDeleted: true
            });

            socket.emit('delete_confirmed', { messageId: parsedMessageId });

        } catch (dbErr) {
            console.error('Delete message error:', dbErr);
            socket.emit('error', { message: 'Failed to delete message' });
        }
    });

    socket.on('disconnect', async () => {
        if (socket.eventId && socket.userId) {
            await pool.execute(
                `UPDATE chat_presence
                 SET is_online = 0, last_seen = NOW()
                 WHERE id_room = ? AND id_utilisateur = ?`,
                [socket.eventId, socket.userId]
            );

            socket.to(`event_${socket.eventId}`).emit('user_left', {
                userId: socket.userId,
                userName: socket.userName
            });
        }
    });
});

const PORT = process.env.PORT || 4500;

pool.getConnection()
    .then(conn => {
        conn.release();
        server.listen(PORT, '0.0.0.0', () => {
            console.log(`Chat server running on port ${PORT}`);
        });
    })
    .catch(err => {
        console.error('Database connection failed:', err.message);
        process.exit(1);
    });
