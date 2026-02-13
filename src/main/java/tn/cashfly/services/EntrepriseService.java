package tn.cashfly.services;

import tn.cashfly.entities.ENTREPRISE;
import tn.cashfly.utils.MyDataBase;

import java.sql.*;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;
import java.util.stream.Collectors;

public class EntrepriseService implements IEntrepriseService {

    private final Connection cnx;

    public EntrepriseService() {
        this.cnx = MyDataBase.getInstance().getCnx();
    }

    @Override
    public void add(ENTREPRISE entreprise) throws SQLException {
        String sql = "INSERT INTO entreprises (nom, secteur, forme_juridique, date_creation, capital, id_proprietaire) " +
                "VALUES (?, ?, ?, ?, ?, ?)";
        try (PreparedStatement ps = cnx.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            ps.setString(1, entreprise.getNom());
            ps.setString(2, entreprise.getSecteur());
            ps.setString(3, entreprise.getFormeJuridique());

            if (entreprise.getDateCreation() != null) {
                ps.setDate(4, Date.valueOf(entreprise.getDateCreation()));
            } else {
                ps.setNull(4, Types.DATE);
            }

            ps.setDouble(5, entreprise.getCapital());
            ps.setInt(6, entreprise.getIdProprietaire());

            ps.executeUpdate();

            try (ResultSet rs = ps.getGeneratedKeys()) {
                if (rs.next()) {
                    entreprise.setIdEntreprise(rs.getInt(1));
                }
            }
        }
    }

    @Override
    public void update(ENTREPRISE entreprise) throws SQLException {
        String sql = "UPDATE entreprises SET nom = ?, secteur = ?, forme_juridique = ?, " +
                "date_creation = ?, capital = ?, id_proprietaire = ? WHERE id_entreprise = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setString(1, entreprise.getNom());
            ps.setString(2, entreprise.getSecteur());
            ps.setString(3, entreprise.getFormeJuridique());

            if (entreprise.getDateCreation() != null) {
                ps.setDate(4, Date.valueOf(entreprise.getDateCreation()));
            } else {
                ps.setNull(4, Types.DATE);
            }

            ps.setDouble(5, entreprise.getCapital());
            ps.setInt(6, entreprise.getIdProprietaire());
            ps.setInt(7, entreprise.getIdEntreprise());

            ps.executeUpdate();
        }
    }

    @Override
    public void delete(int idEntreprise) throws SQLException {
        String sql = "DELETE FROM entreprises WHERE id_entreprise = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, idEntreprise);
            ps.executeUpdate();
        }
    }

    @Override
    public ENTREPRISE getById(int idEntreprise) throws SQLException {
        String sql = "SELECT * FROM entreprises WHERE id_entreprise = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, idEntreprise);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    return mapRowToEntreprise(rs);
                }
            }
        }
        return null;
    }

    @Override
    public List<ENTREPRISE> getAll() throws SQLException {
        List<ENTREPRISE> result = new ArrayList<>();
        String sql = "SELECT * FROM entreprises";
        try (PreparedStatement ps = cnx.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {
            while (rs.next()) {
                result.add(mapRowToEntreprise(rs));
            }
        }
        return result;
    }

    @Override
    public List<ENTREPRISE> searchByName(String keyword) throws SQLException {
        String lower = keyword == null ? "" : keyword.toLowerCase();
        return getAll()
                .stream()
                .filter(e -> e.getNom() != null && e.getNom().toLowerCase().contains(lower))
                .collect(Collectors.toList());
    }

    @Override
    public List<ENTREPRISE> filterBySecteur(String secteur) throws SQLException {
        if (secteur == null) {
            return getAll();
        }
        String lower = secteur.toLowerCase();
        return getAll()
                .stream()
                .filter(e -> e.getSecteur() != null && e.getSecteur().toLowerCase().contains(lower))
                .collect(Collectors.toList());
    }

    private ENTREPRISE mapRowToEntreprise(ResultSet rs) throws SQLException {
        int id = rs.getInt("id_entreprise");
        String nom = rs.getString("nom");
        String secteur = rs.getString("secteur");
        String formeJuridique = rs.getString("forme_juridique");

        Date dateCreationSql = rs.getDate("date_creation");
        LocalDate dateCreation = dateCreationSql != null ? dateCreationSql.toLocalDate() : null;

        double capital = rs.getDouble("capital");
        int idProprietaire = rs.getInt("id_proprietaire");

        return new ENTREPRISE(id, nom, secteur, formeJuridique, dateCreation, capital, idProprietaire);
    }
}

