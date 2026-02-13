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
    public void add(Investissement i) {
        String req = "INSERT INTO investissement " +
                "(id_investisseur,id_entreprise,montant,date_investissement,statut,taux_rendement_prevu,duree_mois,description) " +
                "VALUES (?,?,?,?,?,?,?,?)";

        try {
            PreparedStatement ps = connection.prepareStatement(req, Statement.RETURN_GENERATED_KEYS);
            ps.setInt(1, i.getIdInvestisseur());
            ps.setInt(2, i.getIdEntreprise());
            ps.setDouble(3, i.getMontant());
            ps.setDate(4, i.getDateInvestissement());
            ps.setString(5, i.getStatut());
            ps.setDouble(6, i.getTauxRendementPrevu());
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

        } catch (SQLException e) {
            System.out.println("Erreur add(): " + e.getMessage());
        }
    }

    // READ
    @Override
    public List<Investissement> getAll() {
        List<Investissement> list = new ArrayList<>();

        try {
            Statement st = connection.createStatement();
            ResultSet rs = st.executeQuery("SELECT * FROM investissement");

            while (rs.next()) {
                Investissement i = new Investissement();
                i.setIdInvestissement(rs.getInt("id_investissement"));
                i.setIdInvestisseur(rs.getInt("id_investisseur"));
                i.setIdEntreprise(rs.getInt("id_entreprise"));
                i.setMontant(rs.getDouble("montant"));
                i.setDateInvestissement(rs.getDate("date_investissement"));
                i.setStatut(rs.getString("statut"));
                i.setTauxRendementPrevu(rs.getDouble("taux_rendement_prevu"));
                i.setDureeMois(rs.getInt("duree_mois"));
                i.setDescription(rs.getString("description"));

                list.add(i);
            }

        } catch (SQLException e) {
            System.out.println("Erreur getAll(): " + e.getMessage());
        }

        return list;
    }

    // UPDATE
    @Override
    public void update(Investissement i) {
        String req = "UPDATE investissement SET montant=?, statut=?, taux_rendement_prevu=?, duree_mois=?, description=? " +
                "WHERE id_investissement=?";

        try {
            PreparedStatement ps = connection.prepareStatement(req);
            ps.setDouble(1, i.getMontant());
            ps.setString(2, i.getStatut());
            ps.setDouble(3, i.getTauxRendementPrevu());
            ps.setInt(4, i.getDureeMois());
            ps.setString(5, i.getDescription());
            ps.setInt(6, i.getIdInvestissement());

            int affectedRows = ps.executeUpdate();
            if (affectedRows == 0) {
                System.out.println("Aucune mise à jour effectuée pour l'ID " + i.getIdInvestissement());
            } else {
                System.out.println("Invest mis à jour avec ID = " + i.getIdInvestissement());
            }

        } catch (SQLException e) {
            System.out.println("Erreur update(): " + e.getMessage());
        }
    }

    // DELETE
    @Override
    public void delete(Investissement i) {
        String req = "DELETE FROM investissement WHERE id_investissement=?";

        try {
            PreparedStatement ps = connection.prepareStatement(req);
            ps.setInt(1, i.getIdInvestissement());

            int affectedRows = ps.executeUpdate();
            if (affectedRows == 0) {
                System.out.println("Aucun investissement supprimé pour l'ID " + i.getIdInvestissement());
            } else {
                System.out.println("Invest supprimé avec ID = " + i.getIdInvestissement());
            }

        } catch (SQLException e) {
            System.out.println("Erreur delete(): " + e.getMessage());
        }
    }

    @Override
    public void deleteAll() {
        String req = "DELETE FROM investissement";
        try (Statement st = connection.createStatement()) {
            int affectedRows = st.executeUpdate(req);
            System.out.println("Tous les investissements supprimés, lignes affectées = " + affectedRows);
        } catch (SQLException e) {
            System.out.println("Erreur deleteAll(): " + e.getMessage());
        }
    }
}