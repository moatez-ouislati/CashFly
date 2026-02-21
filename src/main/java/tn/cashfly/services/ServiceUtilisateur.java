package tn.cashfly.services;

import tn.cashfly.entities.Utilisateur;
import tn.cashfly.utils.CashFlyDB;

import java.sql.*;

public class ServiceUtilisateur {

    private Connection connection;

    public ServiceUtilisateur() {
        connection = CashFlyDB.getInstance().getConnection();
    }

    public Utilisateur authenticate(String email, String password) throws SQLException {
        // In production: use hashed passwords with BCrypt
        String query = "SELECT * FROM utilisateurs WHERE email = ? AND mot_de_passe = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setString(1, email);
        ps.setString(2, password); // TODO: hash comparison

        ResultSet rs = ps.executeQuery();

        if (rs.next()) {
            Utilisateur user = new Utilisateur();
            user.setIdUtilisateur(rs.getInt("id_utilisateur"));
            user.setNomComplet(rs.getString("nom_complet"));
            user.setEmail(rs.getString("email"));
            user.setRole(rs.getString("role"));
            rs.close();
            ps.close();
            return user;
        }

        rs.close();
        ps.close();
        return null;
    }

    public Utilisateur getById(int id) throws SQLException {
        String query = "SELECT * FROM utilisateurs WHERE id_utilisateur = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, id);
        ResultSet rs = ps.executeQuery();

        if (rs.next()) {
            Utilisateur user = new Utilisateur();
            user.setIdUtilisateur(rs.getInt("id_utilisateur"));
            user.setNomComplet(rs.getString("nom_complet"));
            user.setEmail(rs.getString("email"));
            user.setRole(rs.getString("role"));
            rs.close();
            ps.close();
            return user;
        }

        rs.close();
        ps.close();
        return null;
    }
}