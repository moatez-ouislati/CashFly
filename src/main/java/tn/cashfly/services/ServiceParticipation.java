package tn.cashfly.services;

import tn.cashfly.entities.JPO;
import tn.cashfly.entities.Participation;
import tn.cashfly.utils.CashFlyDB;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ServiceParticipation {

    private Connection connection;

    public ServiceParticipation() {
        connection = CashFlyDB.getInstance().getConnection();
    }

    // Check if user has an active registration (not cancelled)
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

    /**
     * Get any participation (including cancelled ones) for a user and event
     */
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

    /**
     * FIXED: Register for event - handles both new registration and re-registration after cancellation
     */
    public boolean register(int idEvenement, int idUtilisateur) throws SQLException {
        // Check if already has active registration
        if (isRegistered(idEvenement, idUtilisateur)) {
            throw new SQLException("Vous êtes déjà inscrit à cet événement");
        }

        // Check if there's a previous cancelled participation
        Participation previousParticipation = getAnyParticipation(idEvenement, idUtilisateur);

        // Check event capacity
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
            // RE-REGISTRATION: Update existing cancelled participation
            System.out.println("DEBUG: Re-registering user " + idUtilisateur + " for event " + idEvenement);

            String updateQuery = "UPDATE participation_jpo SET statut = ?, date_inscription = NOW(), badge_genere = FALSE WHERE id_participation = ?";
            PreparedStatement updatePs = connection.prepareStatement(updateQuery);
            updatePs.setString(1, newStatus);
            updatePs.setInt(2, previousParticipation.getIdParticipation());
            updatePs.executeUpdate();
            updatePs.close();

        } else {
            // NEW REGISTRATION: Insert new participation
            System.out.println("DEBUG: New registration for user " + idUtilisateur + " for event " + idEvenement);

            String insertQuery = "INSERT INTO participation_jpo (id_evenement, id_utilisateur, statut, date_inscription, badge_genere) VALUES (?, ?, ?, NOW(), FALSE)";
            PreparedStatement insertPs = connection.prepareStatement(insertQuery);
            insertPs.setInt(1, idEvenement);
            insertPs.setInt(2, idUtilisateur);
            insertPs.setString(3, newStatus);
            insertPs.executeUpdate();
            insertPs.close();
        }

        // Update counter if confirmed
        if (newStatus.equals("confirmé")) {
            updateParticipantCount(idEvenement, 1);
        }

        return newStatus.equals("confirmé");
    }

    /**
     * Cancel registration
     */
    public void cancel(int idEvenement, int idUtilisateur) throws SQLException {
        // Get current status
        String statusQuery = "SELECT statut, id_participation FROM participation_jpo WHERE id_evenement = ? AND id_utilisateur = ? AND statut != 'annulé'";
        PreparedStatement statusPs = connection.prepareStatement(statusQuery);
        statusPs.setInt(1, idEvenement);
        statusPs.setInt(2, idUtilisateur);
        ResultSet rs = statusPs.executeQuery();

        if (!rs.next()) {
            rs.close();
            statusPs.close();
            throw new SQLException("Participation non trouvée ou déjà annulée");
        }

        String currentStatus = rs.getString("statut");
        int participationId = rs.getInt("id_participation");
        rs.close();
        statusPs.close();

        // Update status to cancelled
        String query = "UPDATE participation_jpo SET statut = 'annulé' WHERE id_participation = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, participationId);
        int updated = ps.executeUpdate();
        ps.close();

        if (updated == 0) {
            throw new SQLException("Échec de l'annulation");
        }

        System.out.println("DEBUG: Cancelled participation " + participationId + " (was: " + currentStatus + ")");

        // Decrease counter if was confirmed
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
                System.out.println("DEBUG: Promoted user " + idUser + " from waitlist");
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

    /**
     * Get user participations with event details - EXCLUDES cancelled ones for display
     */
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

    // Mark badge as generated
    public void markBadgeGenerated(int idParticipation) throws SQLException {
        String query = "UPDATE participation_jpo SET badge_genere = TRUE WHERE id_participation = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, idParticipation);
        ps.executeUpdate();
        ps.close();
    }
}