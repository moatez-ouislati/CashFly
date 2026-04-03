package tn.cashfly.services;

import tn.cashfly.entities.Entreprise;
import tn.cashfly.tools.MyConnection;

import java.sql.*;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

public class EntrepriseService implements CrudInterface<Entreprise> {

    private Connection cnx;

    public EntrepriseService() {
        cnx = MyConnection.getInstance().getCnx();
    }

    // ===================== CREATE =====================
    @Override
    public void ajouter(Entreprise e) throws SQLException {

        String sql = """
            INSERT INTO entreprises 
            (nom, secteur, forme_juridique, date_creation, capital, id_proprietaire, latitude, longitude, adresse)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        """;

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setString(1, e.getNom());
            pst.setString(2, e.getSecteur());
            pst.setString(3, e.getFormeJuridique());
            pst.setDate(4, e.getDateCreation() != null ? Date.valueOf(e.getDateCreation()) : null);
            pst.setBigDecimal(5, e.getCapital());
            pst.setInt(6, e.getIdProprietaire());
            pst.setDouble(7, e.getLatitude());
            pst.setDouble(8, e.getLongitude());
            pst.setString(9, e.getAdresse());

            pst.executeUpdate();
            System.out.println("Entreprise ajoutée avec succès");
        }
    }

    // ===================== UPDATE =====================
    @Override
    public void modifier(Entreprise e) throws SQLException {

        String sql = """
            UPDATE entreprises 
            SET nom=?, secteur=?, forme_juridique=?, date_creation=?, capital=?, id_proprietaire=?, latitude=?, longitude=?, adresse=?
            WHERE id_entreprise=?
        """;

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setString(1, e.getNom());
            pst.setString(2, e.getSecteur());
            pst.setString(3, e.getFormeJuridique());
            pst.setDate(4, e.getDateCreation() != null ? Date.valueOf(e.getDateCreation()) : null);
            pst.setBigDecimal(5, e.getCapital());
            pst.setInt(6, e.getIdProprietaire());
            pst.setDouble(7, e.getLatitude());
            pst.setDouble(8, e.getLongitude());
            pst.setString(9, e.getAdresse());
            pst.setInt(10, e.getIdEntreprise());


            pst.executeUpdate();
            System.out.println("Entreprise modifiée avec succès");
        }
    }

    // ===================== DELETE =====================
    @Override
    public void supprimer(Entreprise e) throws SQLException {

        String sql = "DELETE FROM entreprises WHERE id_entreprise=?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, e.getIdEntreprise());
            pst.executeUpdate();
            System.out.println("Entreprise supprimée");
        }
    }

    // ===================== READ ALL =====================
    @Override
    public List<Entreprise> afficher() throws SQLException {

        List<Entreprise> list = new ArrayList<>();
        String sql = "SELECT * FROM entreprises";

        try (Statement st = cnx.createStatement();
             ResultSet rs = st.executeQuery(sql)) {

            while (rs.next()) {
                Entreprise e = new Entreprise(
                        rs.getInt("id_entreprise"),
                        rs.getString("nom"),
                        rs.getString("secteur"),
                        rs.getString("forme_juridique"),
                        rs.getDate("date_creation") != null ? rs.getDate("date_creation").toLocalDate() : null,
                        rs.getBigDecimal("capital"),
                        rs.getInt("id_proprietaire"),
                        rs.getDouble("latitude"),
                        rs.getDouble("longitude"),
                        rs.getString("adresse")
                );
                list.add(e);
            }
        }
        return list;
    }

    public List<Entreprise> getByProprietaire(int idProp) throws SQLException {
        List<Entreprise> list = new ArrayList<>();
        String sql = "SELECT * FROM entreprises WHERE id_proprietaire = ?";
        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, idProp);
            try (ResultSet rs = pst.executeQuery()) {
                while (rs.next()) {
                    list.add(new Entreprise(
                            rs.getInt("id_entreprise"),
                            rs.getString("nom"),
                            rs.getString("secteur"),
                            rs.getString("forme_juridique"),
                            rs.getDate("date_creation") != null ? rs.getDate("date_creation").toLocalDate() : null,
                            rs.getBigDecimal("capital"),
                            rs.getInt("id_proprietaire"),
                            rs.getDouble("latitude"),
                            rs.getDouble("longitude"),
                            rs.getString("adresse")
                    ));
                }
            }
        }
        return list;
    }
}
