package tn.cashfly.services;

import tn.cashfly.interfaces.Service;
import tn.cashfly.models.RendementInvestissement;
import tn.cashfly.utils.CashFlyDB;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ServiceRendement implements Service<RendementInvestissement>{
    private Connection connection;

    public ServiceRendement() {
        connection = CashFlyDB.getInstance().getConnection();
    }

    // CREATE
    public void add(RendementInvestissement r) {
        String req = "INSERT INTO rendement_investissement (id_investissement, date_calcul, gain, perte, valeur_portefeuille) VALUES (?,?,?,?,?)";

        try (PreparedStatement ps = connection.prepareStatement(req)) {
            ps.setInt(1, r.getIdInvestissement());
            ps.setDate(2, r.getDateCalcul());
            ps.setDouble(3, r.getGain());
            ps.setDouble(4, r.getPerte());
            ps.setDouble(5, r.getValeurPortefeuille());

            ps.executeUpdate();
            System.out.println("Rendement ajouté");
        } catch (SQLException e) {
            System.out.println("Erreur add(): " + e.getMessage());
        }
    }

    // READ
    public List<RendementInvestissement> getAll() {
        List<RendementInvestissement> list = new ArrayList<>();

        try (Statement st = connection.createStatement();
             ResultSet rs = st.executeQuery("SELECT * FROM rendement_investissement")) {

            while (rs.next()) {
                RendementInvestissement r = new RendementInvestissement();
                r.setIdRendement(rs.getInt("id_rendement"));
                r.setIdInvestissement(rs.getInt("id_investissement"));
                r.setDateCalcul(rs.getDate("date_calcul"));
                r.setGain(rs.getDouble("gain"));
                r.setPerte(rs.getDouble("perte"));
                r.setValeurPortefeuille(rs.getDouble("valeur_portefeuille"));

                list.add(r);
            }
        } catch (SQLException e) {
            System.out.println("Erreur getAll(): " + e.getMessage());
        }

        return list;
    }

    // UPDATE
    public void update(RendementInvestissement r) {
        String req = "UPDATE rendement_investissement SET gain=?, perte=?, valeur_portefeuille=? WHERE id_rendement=?";
        try (PreparedStatement ps = connection.prepareStatement(req)) {
            ps.setDouble(1, r.getGain());
            ps.setDouble(2, r.getPerte());
            ps.setDouble(3, r.getValeurPortefeuille());
            ps.setInt(4, r.getIdRendement());

            ps.executeUpdate();
            System.out.println("Rendement mis à jour");
        } catch (SQLException e) {
            System.out.println("Erreur update(): " + e.getMessage());
        }
    }

    // DELETE
    public void delete(RendementInvestissement r) {
        String req = "DELETE FROM rendement_investissement WHERE id_rendement=?";
        try (PreparedStatement ps = connection.prepareStatement(req)) {
            ps.setInt(1, r.getIdRendement());
            ps.executeUpdate();
            System.out.println("Rendement supprimé");
        } catch (SQLException e) {
            System.out.println("Erreur delete(): " + e.getMessage());
        }
    }

    @Override
    public void deleteAll() {
        String req = "DELETE FROM rendement_investissement";
        try (Statement st = connection.createStatement()) {
            int affectedRows = st.executeUpdate(req);
            System.out.println("Tous les rendements supprimés, lignes affectées = " + affectedRows);
        } catch (SQLException e) {
            System.out.println("Erreur deleteAll(): " + e.getMessage());
        }
    }
}