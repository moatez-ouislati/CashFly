package tn.cashfly.services;

import tn.cashfly.entities.OperationNote;
import tn.cashfly.utils.MyDataBase;

import java.sql.*;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;

public class NoteService {

    private final Connection cnx;

    public NoteService() {
        this.cnx = MyDataBase.getInstance().getCnx();
        createTableIfNotExists();
    }

    private void createTableIfNotExists() {
        try (Statement stmt = cnx.createStatement()) {
            // First, check if the table exists and has the correct column
            boolean columnExists = false;
            try (ResultSet rs = cnx.getMetaData().getColumns(null, null, "operation_notes", "id_operation")) {
                if (rs.next()) {
                    columnExists = true;
                }
            }
            if (!columnExists) {
                // Try uppercase just in case
                try (ResultSet rs = cnx.getMetaData().getColumns(null, null, "OPERATION_NOTES", "ID_OPERATION")) {
                    if (rs.next()) {
                        columnExists = true;
                    }
                }
            }

            if (!columnExists) {
                // Also check if table exists at all - if it doesn't exist, we don't need to drop it
                boolean tableExists = false;
                try (ResultSet rs = cnx.getMetaData().getTables(null, null, "operation_notes", null)) {
                    if (rs.next()) tableExists = true;
                }
                if (!tableExists) {
                    try (ResultSet rs = cnx.getMetaData().getTables(null, null, "OPERATION_NOTES", null)) {
                        if (rs.next()) tableExists = true;
                    }
                }

                if (tableExists) {
                    // If the table exists but column doesn't, it might be an old schema. 
                    // In dev, we can drop it to recreate correctly.
                    stmt.execute("DROP TABLE IF EXISTS `operation_notes` CASCADE");
                }
            }

            String sql = "CREATE TABLE IF NOT EXISTS `operation_notes` (" +
                    "`id_note` INT AUTO_INCREMENT PRIMARY KEY, " +
                    "`id_operation` INT NOT NULL, " +
                    "`content` TEXT, " +
                    "`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, " +
                    "`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, " +
                    "FOREIGN KEY (`id_operation`) REFERENCES `OPÉRATIONS`(`id_operation`) ON DELETE CASCADE" +
                    ")";
            stmt.execute(sql);
        } catch (SQLException e) {
            System.err.println("Erreur lors de la création/mise à jour de la table operation_notes: " + e.getMessage());
        }
    }

    public void add(OperationNote note) throws SQLException {
        String sql = "INSERT INTO `operation_notes` (`id_operation`, `content`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?)";
        try (PreparedStatement ps = cnx.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            ps.setInt(1, note.getIdOperation());
            ps.setString(2, note.getContent());
            ps.setTimestamp(3, Timestamp.valueOf(note.getCreatedAt() != null ? note.getCreatedAt() : LocalDateTime.now()));
            ps.setTimestamp(4, Timestamp.valueOf(note.getUpdatedAt() != null ? note.getUpdatedAt() : LocalDateTime.now()));
            ps.executeUpdate();

            try (ResultSet rs = ps.getGeneratedKeys()) {
                if (rs.next()) {
                    note.setIdNote(rs.getInt(1));
                }
            }
        }
    }

    public void update(OperationNote note) throws SQLException {
        String sql = "UPDATE `operation_notes` SET `content` = ?, `updated_at` = ? WHERE `id_note` = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setString(1, note.getContent());
            ps.setTimestamp(2, Timestamp.valueOf(LocalDateTime.now()));
            ps.setInt(3, note.getIdNote());
            ps.executeUpdate();
        }
    }

    public OperationNote getByOperationId(int idOperation) throws SQLException {
        String sql = "SELECT * FROM `operation_notes` WHERE `id_operation` = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, idOperation);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    return mapResultSetToNote(rs);
                }
            }
        }
        return null;
    }

    private OperationNote mapResultSetToNote(ResultSet rs) throws SQLException {
        return new OperationNote(
                rs.getInt("id_note"),
                rs.getInt("id_operation"),
                rs.getString("content"),
                rs.getTimestamp("created_at").toLocalDateTime(),
                rs.getTimestamp("updated_at").toLocalDateTime()
        );
    }
}
