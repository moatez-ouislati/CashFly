package tn.cashfly.services;


import tn.cashfly.entities.Document;
import tn.cashfly.entities.Document.Statut;
import tn.cashfly.entities.Document.TypeDocument;
import tn.cashfly.tools.MyConnection;

import java.sql.*;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;

public class DocumentService implements CrudInterface<Document> {

    private Connection cnx;

    public DocumentService() {
        cnx = MyConnection.getInstance().getCnx();
        ensureSchema();
    }

    private void ensureSchema() {
        if (cnx == null) return;
        ensureColumnExists("description",
                "ALTER TABLE documents ADD COLUMN description TEXT NULL AFTER chemin_fichier");
        ensureColumnExists("texte_ocr",
                "ALTER TABLE documents ADD COLUMN texte_ocr LONGTEXT NULL AFTER description");
        ensureColumnExists("id_utilisateur",
                "ALTER TABLE documents ADD COLUMN id_utilisateur INT NULL AFTER id_entreprise");
    }

    private void ensureColumnExists(String column, String alterSql) {
        String checkSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS " +
                "WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'documents' AND COLUMN_NAME = ?";
        try (PreparedStatement ps = cnx.prepareStatement(checkSql)) {
            ps.setString(1, column);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next() && rs.getInt(1) == 0) {
                    try (Statement st = cnx.createStatement()) {
                        st.executeUpdate(alterSql);
                    }
                }
            }
        } catch (SQLException ignored) {
        }
    }

    // ===================== CREATE =====================
    @Override
    public void ajouter(Document d) throws SQLException {

        String sql = """
            INSERT INTO documents
            (id_entreprise, id_utilisateur, nom_document, type_document, statut, chemin_fichier, description, texte_ocr)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        """;

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, d.getIdEntreprise());
            pst.setInt(2, d.getIdUtilisateur());
            pst.setString(3, d.getNomFichier());
            pst.setString(4, d.getTypeDocument().name().toLowerCase());
            pst.setString(5, d.getStatut().name().toLowerCase());
            pst.setString(6, d.getCheminFichier());
            pst.setString(7, d.getDescription());
            pst.setString(8, d.getTexteOcr());

            pst.executeUpdate();
            System.out.println("Document ajouté avec succès");
        }
    }

    // ===================== UPDATE =====================
    @Override
    public void modifier(Document d) throws SQLException {

        String sql = """
            UPDATE documents
            SET id_entreprise=?, id_utilisateur=?, nom_document=?, type_document=?, statut=?, chemin_fichier=?, description=?, texte_ocr=?
            WHERE id_document=?
        """;

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, d.getIdEntreprise());
            pst.setInt(2, d.getIdUtilisateur());
            pst.setString(3, d.getNomFichier());
            pst.setString(4, d.getTypeDocument().name().toLowerCase());
            pst.setString(5, d.getStatut().name().toLowerCase());
            pst.setString(6, d.getCheminFichier());
            pst.setString(7, d.getDescription());
            pst.setString(8, d.getTexteOcr());
            pst.setInt(9, d.getIdDocument());

            pst.executeUpdate();
            System.out.println("Document modifié avec succès");
        }
    }

    // ===================== DELETE =====================
    @Override
    public void supprimer(Document d) throws SQLException {

        String sql = "DELETE FROM documents WHERE id_document=?";

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
        String sql = "SELECT * FROM documents";

        try (Statement st = cnx.createStatement();
             ResultSet rs = st.executeQuery(sql)) {

            while (rs.next()) {
                list.add(mapResultSet(rs));
            }
        }
        return list;
    }

    public List<Document> getByEntreprise(int idEntreprise) throws SQLException {
        List<Document> list = new ArrayList<>();
        String sql = "SELECT * FROM documents WHERE id_entreprise = ?";
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

    public List<Document> searchByNom(String keyword) throws SQLException {
        List<Document> list = new ArrayList<>();
        String sql = "SELECT * FROM documents WHERE nom_document LIKE ?";
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

    private Document mapResultSet(ResultSet rs) throws SQLException {
        LocalDateTime uploadedAt = null;
        Timestamp ts = rs.getTimestamp("date_upload");
        if (ts != null) {
            uploadedAt = ts.toLocalDateTime();
        }
        int idUtilisateur = 0;
        try {
            idUtilisateur = rs.getInt("id_utilisateur");
        } catch (SQLException ignored) {
        }
        String description = null;
        try {
            description = rs.getString("description");
        } catch (SQLException ignored) {
        }
        String texteOcr = null;
        try {
            texteOcr = rs.getString("texte_ocr");
        } catch (SQLException ignored) {
        }
        return new Document(
                rs.getInt("id_document"),
                rs.getInt("id_entreprise"),
                idUtilisateur,
                TypeDocument.valueOf(rs.getString("type_document").toUpperCase()),
                rs.getString("nom_document"),
                rs.getString("chemin_fichier"),
                description,
                uploadedAt,
                Statut.valueOf(rs.getString("statut").toUpperCase()),
                texteOcr
        );
    }

    public List<Object[]> countDocumentsPerEntreprise() throws SQLException {
        List<Object[]> list = new ArrayList<>();
        String sql = """
            SELECT e.nom, COUNT(d.id_document) AS total
            FROM entreprises e
            LEFT JOIN documents d ON e.id_entreprise = d.id_entreprise
            GROUP BY e.nom
            ORDER BY total DESC
        """;
        try (Statement st = cnx.createStatement();
             ResultSet rs = st.executeQuery(sql)) {
            while (rs.next()) {
                list.add(new Object[]{rs.getString("nom"), rs.getInt("total")});
            }
        }
        return list;
    }
}
