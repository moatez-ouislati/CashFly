package com.example.gestion_entreprises.services;


import com.example.gestion_entreprises.entities.Document;
import com.example.gestion_entreprises.entities.Document.Statut;
import com.example.gestion_entreprises.entities.Document.TypeDocument;
import com.example.gestion_entreprises.tools.MyConnection;

import java.sql.*;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;

public class DocumentService implements CrudInterface<Document> {

    private Connection cnx;

    public DocumentService() {
        cnx = MyConnection.getInstance().getCnx();
    }

    // ===================== CREATE =====================
    @Override
    public void ajouter(Document d) throws SQLException {

        String sql = """
            INSERT INTO documents_entreprise
            (id_entreprise, type_document, nom_fichier, chemin_fichier, description, statut)
            VALUES (?, ?, ?, ?, ?, ?)
        """;

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, d.getIdEntreprise());
            pst.setString(2, d.getTypeDocument().name().toLowerCase());
            pst.setString(3, d.getNomFichier());
            pst.setString(4, d.getCheminFichier());
            pst.setString(5, d.getDescription());
            pst.setString(6, d.getStatut().name().toLowerCase());

            pst.executeUpdate();
            System.out.println("Document ajouté avec succès");
        }
    }

    // ===================== UPDATE =====================
    @Override
    public void modifier(Document d) throws SQLException {

        String sql = """
            UPDATE documents_entreprise
            SET id_entreprise=?, type_document=?, nom_fichier=?, chemin_fichier=?, description=?, statut=?
            WHERE id_document=?
        """;

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, d.getIdEntreprise());
            pst.setString(2, d.getTypeDocument().name().toLowerCase());
            pst.setString(3, d.getNomFichier());
            pst.setString(4, d.getCheminFichier());
            pst.setString(5, d.getDescription());
            pst.setString(6, d.getStatut().name().toLowerCase());
            pst.setInt(7, d.getIdDocument());

            pst.executeUpdate();
            System.out.println("Document modifié avec succès");
        }
    }

    // ===================== DELETE =====================
    @Override
    public void supprimer(Document d) throws SQLException {

        String sql = "DELETE FROM documents_entreprise WHERE id_document=?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, d.getIdDocument());
            pst.executeUpdate();
            System.out.println("Document supprimé");
        }
    }

    // ===================== READ ALL =====================
    @Override
    public List<Document> afficher() throws SQLException {

        List<Document> list = new ArrayList<>();
        String sql = "SELECT * FROM documents_entreprise";

        try (Statement st = cnx.createStatement();
             ResultSet rs = st.executeQuery(sql)) {

            while (rs.next()) {
                list.add(mapResultSet(rs));
            }
        }
        return list;
    }

    // ===================== FILTER BY ENTREPRISE =====================
    public List<Document> getByEntreprise(int idEntreprise) throws SQLException {

        List<Document> list = new ArrayList<>();
        String sql = "SELECT * FROM documents_entreprise WHERE id_entreprise=?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, idEntreprise);

            try (ResultSet rs = pst.executeQuery()) {
                while (rs.next()) {
                    list.add(mapResultSet(rs));
                }
            }
        }
        return list;
    }

    // ===================== SEARCH =====================
    public List<Document> searchByNom(String keyword) throws SQLException {

        List<Document> list = new ArrayList<>();
        String sql = "SELECT * FROM documents_entreprise WHERE nom_fichier LIKE ?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setString(1, "%" + keyword + "%");

            try (ResultSet rs = pst.executeQuery()) {
                while (rs.next()) {
                    list.add(mapResultSet(rs));
                }
            }
        }
        return list;
    }

    // ===================== MAPPER =====================
    private Document mapResultSet(ResultSet rs) throws SQLException {

        return new Document(
                rs.getInt("id_document"),
                rs.getInt("id_entreprise"),
                TypeDocument.valueOf(rs.getString("type_document").toUpperCase()),
                rs.getString("nom_fichier"),
                rs.getString("chemin_fichier"),
                rs.getString("description"),
                rs.getTimestamp("date_upload").toLocalDateTime(),
                Statut.valueOf(rs.getString("statut").toUpperCase())
        );
    }
}
