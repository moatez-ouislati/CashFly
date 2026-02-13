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
        String sql = "INSERT INTO TRÉSORERIE (id_entreprise, solde, devise, derniere_maj) " +
                "VALUES (?, ?, ?, ?)";
        try (PreparedStatement ps = cnx.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            ps.setInt(1, tresorerie.getIdEntreprise());
            ps.setDouble(2, tresorerie.getSolde());
            ps.setString(3, tresorerie.getDevise());

            if (tresorerie.getDerniereMaj() != null) {
                ps.setTimestamp(4, Timestamp.valueOf(tresorerie.getDerniereMaj()));
            } else {
                ps.setTimestamp(4, Timestamp.valueOf(LocalDateTime.now()));
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
        String sql = "UPDATE TRÉSORERIE SET id_entreprise = ?, solde = ?, devise = ?, derniere_maj = ? " +
                "WHERE id_tresorerie = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, tresorerie.getIdEntreprise());
            ps.setDouble(2, tresorerie.getSolde());
            ps.setString(3, tresorerie.getDevise());

            if (tresorerie.getDerniereMaj() != null) {
                ps.setTimestamp(4, Timestamp.valueOf(tresorerie.getDerniereMaj()));
            } else {
                ps.setTimestamp(4, Timestamp.valueOf(LocalDateTime.now()));
            }

            ps.setInt(5, tresorerie.getIdTresorerie());

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
        double solde = rs.getDouble("solde");
        String devise = rs.getString("devise");

        Timestamp ts = rs.getTimestamp("derniere_maj");
        LocalDateTime derniereMaj = ts != null ? ts.toLocalDateTime() : null;

        return new TRÉSORERIE(id, idEntreprise, solde, devise, derniereMaj);
    }
}

