package tn.cashfly.services;

import tn.cashfly.entities.TRÉSORERIE;
import tn.cashfly.utils.MyDataBase;

import java.sql.*;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;
import java.util.stream.Collectors;

public class TresorerieService implements ITresorerieService {

    private final Connection cnx;

    public TresorerieService() {
        this.cnx = MyDataBase.getInstance().getCnx();
    }

    @Override
    public void add(TRÉSORERIE tresorerie) throws SQLException {
        String sql = "INSERT INTO TRÉSORERIE (id_entreprise, nom_compte, type_compte, solde, devise, rib, numero_compte, derniere_maj) " +
                "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        try (PreparedStatement ps = cnx.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            ps.setInt(1, tresorerie.getIdEntreprise());
            ps.setString(2, tresorerie.getNomCompte());
            ps.setString(3, tresorerie.getTypeCompte().name());
            ps.setDouble(4, tresorerie.getSolde());
            ps.setString(5, tresorerie.getDevise());
            ps.setString(6, tresorerie.getRib());
            ps.setString(7, tresorerie.getNumeroCompte());

            if (tresorerie.getDerniereMaj() != null) {
                ps.setTimestamp(8, Timestamp.valueOf(tresorerie.getDerniereMaj()));
            } else {
                ps.setTimestamp(8, Timestamp.valueOf(LocalDateTime.now()));
            }

            ps.executeUpdate();

            try (ResultSet rs = ps.getGeneratedKeys()) {
                if (rs.next()) {
                    tresorerie.setIdTresorerie(rs.getInt(1));
                }
            }
        }
    }

    @Override
    public void update(TRÉSORERIE tresorerie) throws SQLException {
        String sql = "UPDATE TRÉSORERIE SET id_entreprise = ?, nom_compte = ?, type_compte = ?, solde = ?, devise = ?, rib = ?, numero_compte = ?, derniere_maj = ? " +
                "WHERE id_tresorerie = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, tresorerie.getIdEntreprise());
            ps.setString(2, tresorerie.getNomCompte());
            ps.setString(3, tresorerie.getTypeCompte().name());
            ps.setDouble(4, tresorerie.getSolde());
            ps.setString(5, tresorerie.getDevise());
            ps.setString(6, tresorerie.getRib());
            ps.setString(7, tresorerie.getNumeroCompte());

            if (tresorerie.getDerniereMaj() != null) {
                ps.setTimestamp(8, Timestamp.valueOf(tresorerie.getDerniereMaj()));
            } else {
                ps.setTimestamp(8, Timestamp.valueOf(LocalDateTime.now()));
            }

            ps.setInt(9, tresorerie.getIdTresorerie());

            ps.executeUpdate();
        }
    }

    @Override
    public void delete(int idTresorerie) throws SQLException {
        String sql = "DELETE FROM TRÉSORERIE WHERE id_tresorerie = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, idTresorerie);
            ps.executeUpdate();
        }
    }

    @Override
    public TRÉSORERIE getById(int idTresorerie) throws SQLException {
        String sql = "SELECT * FROM TRÉSORERIE WHERE id_tresorerie = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, idTresorerie);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    return mapRowToTresorerie(rs);
                }
            }
        }
        return null;
    }

    @Override
    public List<TRÉSORERIE> getAll() throws SQLException {
        List<TRÉSORERIE> result = new ArrayList<>();
        String sql = "SELECT * FROM TRÉSORERIE";
        try (PreparedStatement ps = cnx.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {
            while (rs.next()) {
                result.add(mapRowToTresorerie(rs));
            }
        }
        return result;
    }

    @Override
    public List<TRÉSORERIE> filterByDevise(String devise) throws SQLException {
        if (devise == null) {
            return getAll();
        }
        String upper = devise.toUpperCase();
        return getAll()
                .stream()
                .filter(t -> t.getDevise() != null && t.getDevise().toUpperCase().equals(upper))
                .collect(Collectors.toList());
    }

    @Override
    public List<TRÉSORERIE> filterBySoldeGreaterThan(double minSolde) throws SQLException {
        return getAll()
                .stream()
                .filter(t -> t.getSolde() >= minSolde)
                .collect(Collectors.toList());
    }

    private TRÉSORERIE mapRowToTresorerie(ResultSet rs) throws SQLException {
        int id = rs.getInt("id_tresorerie");
        int idEntreprise = rs.getInt("id_entreprise");
        String nomCompte = rs.getString("nom_compte");
        String typeStr = rs.getString("type_compte");
        TRÉSORERIE.TypeCompte typeCompte = TRÉSORERIE.TypeCompte.valueOf(typeStr);

        double solde = rs.getDouble("solde");
        String devise = rs.getString("devise");

        String rib = rs.getString("rib");
        String numeroCompte = rs.getString("numero_compte");

        Timestamp ts = rs.getTimestamp("derniere_maj");
        LocalDateTime derniereMaj = ts != null ? ts.toLocalDateTime() : null;

        return new TRÉSORERIE(id, idEntreprise, nomCompte, typeCompte, solde, devise, derniereMaj, rib, numeroCompte);
    }
}

