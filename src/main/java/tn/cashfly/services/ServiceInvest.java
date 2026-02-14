package tn.cashfly.services;

import tn.cashfly.interfaces.Service;
import tn.cashfly.models.Investissement;
import tn.cashfly.utils.CashFlyDB;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ServiceInvest implements Service<Investissement> {

    private Connection connection;

    public ServiceInvest() {
        connection = CashFlyDB.getInstance().getConnection();
    }

    // CREATE
    @Override
    public void add(Investissement i) throws SQLException {
        if (connection == null) {
            throw new SQLException("Erreur : Pas de connexion à la base de données.");
        }
        String req = "INSERT INTO investissement " +
                "(id_investisseur,id_entreprise,montant,date_investissement,statut,taux_rendement_prevu,duree_mois,description) "
                +
                "VALUES (?,?,?,?,?,?,?,?)";

        PreparedStatement ps = connection.prepareStatement(req, Statement.RETURN_GENERATED_KEYS);
        ps.setInt(1, i.getIdInvestisseur());
        ps.setInt(2, i.getIdEntreprise());
        ps.setBigDecimal(3, i.getMontant());
        ps.setDate(4, i.getDateInvestissement());

        // Ensure status matches DB enum (uppercase)
        String status = i.getStatut();
        if (status == null || status.trim().isEmpty()) {
            status = "EN_ATTENTE";
        }
        ps.setString(5, status.toUpperCase().trim());

        ps.setBigDecimal(6, i.getTauxRendementPrevu());
        ps.setInt(7, i.getDureeMois());
        ps.setString(8, i.getDescription());

        int affectedRows = ps.executeUpdate();
        if (affectedRows == 0) {
            throw new SQLException("Échec de l'insertion, aucune ligne ajoutée.");
        }

        // Récupérer l'ID auto-incrémenté
        try (ResultSet generatedKeys = ps.getGeneratedKeys()) {
            if (generatedKeys.next()) {
                i.setIdInvestissement(generatedKeys.getInt(1));
            }
        }

        System.out.println("Invest ajouté avec ID = " + i.getIdInvestissement());

        // Générer automatiquement les rendements (projections)
        ServiceRendement serviceRendement = new ServiceRendement();
        serviceRendement.generateProjections(i);
    }

    // READ
    @Override
    public List<Investissement> getAll() throws SQLException {
        List<Investissement> list = new ArrayList<>();
        if (connection == null) {
            throw new SQLException("Erreur : Pas de connexion à la base de données.");
        }

        Statement st = connection.createStatement();
        ResultSet rs = st.executeQuery("SELECT * FROM investissement");

        while (rs.next()) {
            Investissement i = new Investissement();
            i.setIdInvestissement(rs.getInt("id_investissement"));
            i.setIdInvestisseur(rs.getInt("id_investisseur"));
            i.setIdEntreprise(rs.getInt("id_entreprise"));
            i.setMontant(rs.getBigDecimal("montant"));
            i.setDateInvestissement(rs.getDate("date_investissement"));
            i.setStatut(rs.getString("statut"));
            i.setTauxRendementPrevu(rs.getBigDecimal("taux_rendement_prevu"));
            i.setDureeMois(rs.getInt("duree_mois"));
            i.setDescription(rs.getString("description"));

            list.add(i);
        }

        return list;
    }

    // UPDATE
    @Override
    public void update(Investissement i) throws SQLException {
        if (connection == null) {
            throw new SQLException("Erreur : Pas de connexion à la base de données.");
        }
        String req = "UPDATE investissement SET montant=?, statut=?, taux_rendement_prevu=?, duree_mois=?, description=? "
                +
                "WHERE id_investissement=?";

        try (PreparedStatement ps = connection.prepareStatement(req)) {
            ps.setBigDecimal(1, i.getMontant());

            String status = i.getStatut();
            if (status == null || status.trim().isEmpty()) {
                status = "EN_ATTENTE";
            }
            ps.setString(2, status.toUpperCase().trim());

            ps.setBigDecimal(3, i.getTauxRendementPrevu());
            ps.setInt(4, i.getDureeMois());
            ps.setString(5, i.getDescription());
            ps.setInt(6, i.getIdInvestissement());

            int affectedRows = ps.executeUpdate();
            if (affectedRows == 0) {
                System.out.println("Aucune mise à jour effectuée pour l'ID " + i.getIdInvestissement());
            } else {
                System.out.println("Invest mis à jour avec ID = " + i.getIdInvestissement());
            }
        }
    }

    // DELETE
    @Override
    public void delete(Investissement i) throws SQLException {
        if (connection == null) {
            throw new SQLException("Erreur : Pas de connexion à la base de données.");
        }

        // 1. Supprimer d'abord les rendements associés (contrainte de clé étrangère)
        String reqRendements = "DELETE FROM rendement_investissement WHERE id_investissement=?";
        try (PreparedStatement psR = connection.prepareStatement(reqRendements)) {
            psR.setInt(1, i.getIdInvestissement());
            psR.executeUpdate();
        }

        // 2. Supprimer l'investissement
        String reqInvest = "DELETE FROM investissement WHERE id_investissement=?";
        try (PreparedStatement ps = connection.prepareStatement(reqInvest)) {
            ps.setInt(1, i.getIdInvestissement());

            int affectedRows = ps.executeUpdate();
            if (affectedRows == 0) {
                System.out.println("Aucun investissement supprimé pour l'ID " + i.getIdInvestissement());
            } else {
                System.out.println("Invest supprimé avec ID = " + i.getIdInvestissement());
            }
        }
    }

    // DELETE ALL
    @Override
    public void deleteAll() throws SQLException {
        if (connection == null) {
            throw new SQLException("Erreur : Pas de connexion à la base de données.");
        }

        // Supprimer tous les rendements
        try (Statement st = connection.createStatement()) {
            st.executeUpdate("DELETE FROM rendement_investissement");
        }

        // Supprimer tous les investissements
        String req = "DELETE FROM investissement";
        try (Statement st = connection.createStatement()) {
            st.executeUpdate(req);
            System.out.println("Tous les investissements ont été supprimés.");
        }
    }
}
