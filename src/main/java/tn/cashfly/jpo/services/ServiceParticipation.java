package tn.cashfly.jpo.services;

import tn.cashfly.jpo.entities.Participation;
import tn.cashfly.jpo.entities.ParticipantInfo;
import tn.cashfly.jpo.entities.EventStatistics;
import tn.cashfly.jpo.utils.CashFlyDB;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ServiceParticipation {

    private final Connection connection;

    public ServiceParticipation() {
        connection = CashFlyDB.getInstance().getConnection();
        ensureSchema();
    }

    public boolean isRegistered(int idEvenement, int idUtilisateur) throws SQLException {
        String query = "SELECT * FROM participation_jpo WHERE id_evenement = ? AND id_utilisateur = ? AND statut != 'annulé'";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, idEvenement);
        ps.setInt(2, idUtilisateur);
        ResultSet rs = ps.executeQuery();
        boolean registered = rs.next();
        rs.close();
        ps.close();
        return registered;
    }

    private Participation getAnyParticipation(int idEvenement, int idUtilisateur) throws SQLException {
        String query = "SELECT * FROM participation_jpo WHERE id_evenement = ? AND id_utilisateur = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, idEvenement);
        ps.setInt(2, idUtilisateur);
        ResultSet rs = ps.executeQuery();
        Participation p = null;
        if (rs.next()) {
            p = new Participation();
            p.setIdParticipation(rs.getInt("id_participation"));
            p.setIdEvenement(rs.getInt("id_evenement"));
            p.setIdUtilisateur(rs.getInt("id_utilisateur"));
            p.setStatut(rs.getString("statut"));
            p.setDateInscription(rs.getTimestamp("date_inscription"));
            p.setBadgeGenere(rs.getBoolean("badge_genere"));
        }
        rs.close();
        ps.close();
        return p;
    }

    public boolean register(int idEvenement, int idUtilisateur) throws SQLException {
        if (isRegistered(idEvenement, idUtilisateur)) {
            throw new SQLException("Vous êtes déjà inscrit à cet événement");
        }
        Participation previousParticipation = getAnyParticipation(idEvenement, idUtilisateur);
        String checkQuery = "SELECT max_participants, current_participants FROM journées_portes_ouvertes WHERE id_evenement = ?";
        PreparedStatement checkPs = connection.prepareStatement(checkQuery);
        checkPs.setInt(1, idEvenement);
        ResultSet rs = checkPs.executeQuery();
        if (!rs.next()) {
            rs.close();
            checkPs.close();
            throw new SQLException("Événement non trouvé");
        }
        int max = rs.getInt("max_participants");
        int current = rs.getInt("current_participants");
        String newStatus = (current < max) ? "confirmé" : "en_attente";
        rs.close();
        checkPs.close();
        if (previousParticipation != null && "annulé".equals(previousParticipation.getStatut())) {
            String updateQuery = "UPDATE participation_jpo SET statut = ?, date_inscription = NOW(), badge_genere = FALSE WHERE id_participation = ?";
            PreparedStatement updatePs = connection.prepareStatement(updateQuery);
            updatePs.setString(1, newStatus);
            updatePs.setInt(2, previousParticipation.getIdParticipation());
            updatePs.executeUpdate();
            updatePs.close();
        } else {
            String insertQuery = "INSERT INTO participation_jpo (id_evenement, id_utilisateur, statut, date_inscription, badge_genere) VALUES (?, ?, ?, NOW(), FALSE)";
            PreparedStatement insertPs = connection.prepareStatement(insertQuery);
            insertPs.setInt(1, idEvenement);
            insertPs.setInt(2, idUtilisateur);
            insertPs.setString(3, newStatus);
            insertPs.executeUpdate();
            insertPs.close();
        }
        if (newStatus.equals("confirmé")) {
            updateParticipantCount(idEvenement, 1);
        }
        return newStatus.equals("confirmé");
    }

    public void cancel(int idEvenement, int idUtilisateur) throws SQLException {
        Participation participation = getParticipation(idEvenement, idUtilisateur);
        if (participation == null) {
            throw new SQLException("Participation non trouvée ou déjà annulée");
        }
        if (participation.isBadgeGenere()) {
            throw new SQLException("Impossible d'annuler après génération du badge");
        }
        String currentStatus = participation.getStatut();
        int participationId = participation.getIdParticipation();
        String query = "UPDATE participation_jpo SET statut = 'annulé' WHERE id_participation = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, participationId);
        int updated = ps.executeUpdate();
        ps.close();
        if (updated == 0) {
            throw new SQLException("Échec de l'annulation");
        }
        if ("confirmé".equals(currentStatus)) {
            updateParticipantCount(idEvenement, -1);
            promoteFromWaitlist(idEvenement);
        }
    }

    private void promoteFromWaitlist(int idEvenement) throws SQLException {
        String query = "SELECT id_utilisateur FROM participation_jpo WHERE id_evenement = ? AND statut = 'en_attente' ORDER BY date_inscription ASC LIMIT 1";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, idEvenement);
        ResultSet rs = ps.executeQuery();
        if (rs.next()) {
            int idUser = rs.getInt("id_utilisateur");
            String updateQuery = "UPDATE participation_jpo SET statut = 'confirmé' WHERE id_evenement = ? AND id_utilisateur = ? AND statut = 'en_attente'";
            PreparedStatement updatePs = connection.prepareStatement(updateQuery);
            updatePs.setInt(1, idEvenement);
            updatePs.setInt(2, idUser);
            int updated = updatePs.executeUpdate();
            updatePs.close();
            if (updated > 0) {
                updateParticipantCount(idEvenement, 1);
            }
        }
        rs.close();
        ps.close();
    }

    private void updateParticipantCount(int idEvenement, int delta) throws SQLException {
        String query = "UPDATE journées_portes_ouvertes SET current_participants = GREATEST(0, current_participants + ?) WHERE id_evenement = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, delta);
        ps.setInt(2, idEvenement);
        ps.executeUpdate();
        ps.close();
    }

    public List<Participation> getUserParticipationsWithEvents(int idUtilisateur) throws SQLException {
        List<Participation> list = new ArrayList<>();
        String query = "SELECT p.*, j.titre, j.date_evenement, j.lieu, j.description, j.image_path, j.max_participants, j.current_participants " +
                "FROM participation_jpo p " +
                "JOIN journées_portes_ouvertes j ON p.id_evenement = j.id_evenement " +
                "WHERE p.id_utilisateur = ? AND p.statut != 'annulé' " +
                "ORDER BY j.date_evenement ASC";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, idUtilisateur);
        ResultSet rs = ps.executeQuery();
        while (rs.next()) {
            Participation p = new Participation();
            p.setIdParticipation(rs.getInt("id_participation"));
            p.setIdEvenement(rs.getInt("id_evenement"));
            p.setIdUtilisateur(rs.getInt("id_utilisateur"));
            p.setStatut(rs.getString("statut"));
            p.setDateInscription(rs.getTimestamp("date_inscription"));
            p.setBadgeGenere(rs.getBoolean("badge_genere"));
            list.add(p);
        }
        rs.close();
        ps.close();
        return list;
    }

    public void markBadgeGenerated(int idParticipation) throws SQLException {
        String query = "UPDATE participation_jpo SET badge_genere = TRUE WHERE id_participation = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, idParticipation);
        ps.executeUpdate();
        ps.close();
    }

    public Participation getParticipation(int idEvenement, int idUtilisateur) throws SQLException {
        String query = "SELECT * FROM participation_jpo WHERE id_evenement = ? AND id_utilisateur = ? AND statut != 'annulé'";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, idEvenement);
        ps.setInt(2, idUtilisateur);
        ResultSet rs = ps.executeQuery();
        Participation p = null;
        if (rs.next()) {
            p = new Participation();
            p.setIdParticipation(rs.getInt("id_participation"));
            p.setIdEvenement(rs.getInt("id_evenement"));
            p.setIdUtilisateur(rs.getInt("id_utilisateur"));
            p.setStatut(rs.getString("statut"));
            p.setDateInscription(rs.getTimestamp("date_inscription"));
            p.setBadgeGenere(rs.getBoolean("badge_genere"));
        }
        rs.close();
        ps.close();
        return p;
    }

    public List<ParticipantInfo> getEventParticipantsDetailed(int idEvenement) throws SQLException {
        List<ParticipantInfo> participants = new ArrayList<>();
        String query = "SELECT p.id_participation, p.id_utilisateur, p.statut, p.date_inscription, p.badge_genere, " +
                "CONCAT(u.nom, ' ', u.prenom) AS nom_complet, u.email, u.role, u.date_creation, " +
                "j.titre as event_titre, j.date_evenement " +
                "FROM participation_jpo p " +
                "JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur " +
                "JOIN journées_portes_ouvertes j ON p.id_evenement = j.id_evenement " +
                "WHERE p.id_evenement = ? AND p.statut != 'annulé' " +
                "ORDER BY p.date_inscription ASC";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, idEvenement);
        ResultSet rs = ps.executeQuery();
        while (rs.next()) {
            ParticipantInfo info = new ParticipantInfo();
            info.setParticipationId(rs.getInt("id_participation"));
            info.setUserId(rs.getInt("id_utilisateur"));
            info.setStatut(rs.getString("statut"));
            info.setDateInscription(rs.getTimestamp("date_inscription"));
            info.setBadgeGenere(rs.getBoolean("badge_genere"));
            info.setNomComplet(rs.getString("nom_complet"));
            info.setEmail(rs.getString("email"));
            info.setRole(rs.getString("role"));
            info.setDateCreationCompte(rs.getDate("date_creation"));
            info.setEventTitre(rs.getString("event_titre"));
            info.setEventDate(rs.getTimestamp("date_evenement"));
            participants.add(info);
        }
        rs.close();
        ps.close();
        return participants;
    }

    public EventStatistics getEventStatistics(int idEvenement) throws SQLException {
        EventStatistics stats = new EventStatistics();
        String query = "SELECT " +
                "COUNT(*) as total_participants, " +
                "SUM(CASE WHEN statut = 'confirmé' THEN 1 ELSE 0 END) as confirmed, " +
                "SUM(CASE WHEN statut = 'en_attente' THEN 1 ELSE 0 END) as waiting, " +
                "SUM(CASE WHEN badge_genere = TRUE THEN 1 ELSE 0 END) as badges_generated " +
                "FROM participation_jpo " +
                "WHERE id_evenement = ? AND statut != 'annulé'";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, idEvenement);
        ResultSet rs = ps.executeQuery();
        if (rs.next()) {
            stats.setTotalParticipants(rs.getInt("total_participants"));
            stats.setConfirmed(rs.getInt("confirmed"));
            stats.setWaiting(rs.getInt("waiting"));
            stats.setBadgesGenerated(rs.getInt("badges_generated"));
        }
        rs.close();
        ps.close();
        return stats;
    }

    private void ensureSchema() {
        if (connection == null) {
            return;
        }
        try (Statement st = connection.createStatement()) {
            String createTable = """
                    CREATE TABLE IF NOT EXISTS participation_jpo (
                        id_participation INT AUTO_INCREMENT PRIMARY KEY,
                        id_evenement INT NOT NULL,
                        id_utilisateur INT NOT NULL,
                        statut VARCHAR(20) NOT NULL DEFAULT 'en_attente',
                        date_inscription TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                        badge_genere BOOLEAN NOT NULL DEFAULT FALSE
                    )
                    """;
            st.executeUpdate(createTable);
        } catch (SQLException ignored) {
        }

        ensureColumnExists("participation_jpo", "statut", "ALTER TABLE participation_jpo ADD COLUMN statut VARCHAR(20) NOT NULL DEFAULT 'en_attente'");
        ensureColumnExists("participation_jpo", "date_inscription", "ALTER TABLE participation_jpo ADD COLUMN date_inscription TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
        ensureColumnExists("participation_jpo", "badge_genere", "ALTER TABLE participation_jpo ADD COLUMN badge_genere BOOLEAN NOT NULL DEFAULT FALSE");
        dropForeignKeyIfExists("participation_jpo", "participation_jpo_ibfk_1");
    }

    private void ensureColumnExists(String table, String column, String alterSql) {
        String checkSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?";
        try (PreparedStatement ps = connection.prepareStatement(checkSql)) {
            ps.setString(1, table);
            ps.setString(2, column);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next() && rs.getInt(1) == 0) {
                    try (Statement alter = connection.createStatement()) {
                        alter.executeUpdate(alterSql);
                    }
                }
            }
        } catch (SQLException ignored) {
        }
    }

    private void dropForeignKeyIfExists(String table, String fkName) {
        String checkSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'";
        try (PreparedStatement ps = connection.prepareStatement(checkSql)) {
            ps.setString(1, table);
            ps.setString(2, fkName);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next() && rs.getInt(1) > 0) {
                    String alterSql = "ALTER TABLE " + table + " DROP FOREIGN KEY " + fkName;
                    try (Statement st = connection.createStatement()) {
                        st.executeUpdate(alterSql);
                    }
                }
            }
        } catch (SQLException ignored) {
        }
    }
}
