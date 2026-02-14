package tn.cashfly.services;

import tn.cashfly.models.Utilisateur;
import tn.cashfly.utils.CashFlyDB;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ServiceUtilisateur {

    private Connection connection;

    public ServiceUtilisateur() {
        connection = CashFlyDB.getInstance().getConnection();
    }

    public void ajouter(Utilisateur t) throws SQLException {
        String query = "INSERT INTO UTILISATEUR (nom_complet, email, mot_de_passe, role) VALUES (?, ?, ?, ?)";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setString(1, t.getNomComplet());
        ps.setString(2, t.getEmail());
        ps.setString(3, t.getMotDePasse());
        ps.setString(4, t.getRole());
        ps.executeUpdate();
    }

    public Utilisateur authentifier(String email, String motDePasse) throws SQLException {
        String query = "SELECT * FROM UTILISATEUR WHERE email = ? AND mot_de_passe = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setString(1, email);
        ps.setString(2, motDePasse);
        ResultSet rs = ps.executeQuery();

        if (rs.next()) {
            return new Utilisateur(
                    rs.getInt("id_utilisateur"),
                    rs.getString("nom_complet"),
                    rs.getString("email"),
                    rs.getString("mot_de_passe"),
                    rs.getString("role"),
                    rs.getTimestamp("date_creation"));
        }
        return null;
    }
}
