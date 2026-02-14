package tn.cashfly.services;

import tn.cashfly.models.Utilisateur;
import tn.cashfly.utils.CashFlyDB;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

import tn.cashfly.interfaces.Service;

public class ServiceUtilisateur implements Service<Utilisateur> {

    private Connection connection;

    public ServiceUtilisateur() {
        connection = CashFlyDB.getInstance().getConnection();
    }

    @Override
    public void add(Utilisateur t) throws SQLException {
        String query = "INSERT INTO UTILISATEUR (nom_complet, email, mot_de_passe, role) VALUES (?, ?, ?, ?)";
        try (PreparedStatement ps = connection.prepareStatement(query)) {
            ps.setString(1, t.getNomComplet());
            ps.setString(2, t.getEmail());
            ps.setString(3, t.getMotDePasse());
            ps.setString(4, t.getRole());
            ps.executeUpdate();
            System.out.println("Utilisateur ajouté : " + t.getNomComplet());
        }
    }

    @Override
    public List<Utilisateur> getAll() throws SQLException {
        List<Utilisateur> list = new ArrayList<>();
        String query = "SELECT * FROM UTILISATEUR";
        try (Statement st = connection.createStatement();
             ResultSet rs = st.executeQuery(query)) {
            while (rs.next()) {
                list.add(new Utilisateur(
                        rs.getInt("id_utilisateur"),
                        rs.getString("nom_complet"),
                        rs.getString("email"),
                        rs.getString("mot_de_passe"),
                        rs.getString("role"),
                        rs.getTimestamp("date_creation")));
            }
        }
        return list;
    }

    @Override
    public void update(Utilisateur t) throws SQLException {
        String query = "UPDATE UTILISATEUR SET nom_complet=?, email=?, mot_de_passe=?, role=? WHERE id_utilisateur=?";
        try (PreparedStatement ps = connection.prepareStatement(query)) {
            ps.setString(1, t.getNomComplet());
            ps.setString(2, t.getEmail());
            ps.setString(3, t.getMotDePasse());
            ps.setString(4, t.getRole());
            ps.setInt(5, t.getIdUtilisateur());
            ps.executeUpdate();
            System.out.println("Utilisateur mis à jour : " + t.getNomComplet());
        }
    }

    @Override
    public void delete(Utilisateur t) throws SQLException {
        String query = "DELETE FROM UTILISATEUR WHERE id_utilisateur=?";
        try (PreparedStatement ps = connection.prepareStatement(query)) {
            ps.setInt(1, t.getIdUtilisateur());
            ps.executeUpdate();
            System.out.println("Utilisateur supprimé ID: " + t.getIdUtilisateur());
        }
    }

    @Override
    public void deleteAll() throws SQLException {
        String query = "DELETE FROM UTILISATEUR";
        try (Statement st = connection.createStatement()) {
            st.executeUpdate(query);
            System.out.println("Tous les utilisateurs ont été supprimés.");
        }
    }

    public Utilisateur authentifier(String email, String motDePasse) throws SQLException {
        String query = "SELECT * FROM UTILISATEUR WHERE email = ? AND mot_de_passe = ?";
        try (PreparedStatement ps = connection.prepareStatement(query)) {
            ps.setString(1, email);
            ps.setString(2, motDePasse);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    return new Utilisateur(
                            rs.getInt("id_utilisateur"),
                            rs.getString("nom_complet"),
                            rs.getString("email"),
                            rs.getString("mot_de_passe"),
                            rs.getString("role"),
                            rs.getTimestamp("date_creation"));
                }
            }
        }
        return null;
    }
}
