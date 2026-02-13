package tn.cashfly.services;

import tn.cashfly.entities.OPÉRATIONS;
import tn.cashfly.entities.TRÉSORERIE;
import tn.cashfly.utils.MyDataBase;

import java.sql.*;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;
import java.util.stream.Collectors;

public class OperationService implements IOperationService {

    private final Connection cnx;
    private final ITresorerieService tresorerieService;

    public OperationService() {
        this.cnx = MyDataBase.getInstance().getCnx();
        this.tresorerieService = new TresorerieService();
    }

    @Override
    public void add(OPÉRATIONS operation) throws SQLException {
        String sql = "INSERT INTO OPÉRATIONS (id_tresorerie, type, montant, categorie, date_operation, description) " +
                "VALUES (?, ?, ?, ?, ?, ?)";
        try (PreparedStatement ps = cnx.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            ps.setInt(1, operation.getTresorerie().getIdTresorerie());
            ps.setString(2, operation.getType());
            ps.setDouble(3, operation.getMontant());
            ps.setString(4, operation.getCategorie());

            if (operation.getDateOperation() != null) {
                ps.setTimestamp(5, Timestamp.valueOf(operation.getDateOperation()));
            } else {
                ps.setTimestamp(5, Timestamp.valueOf(LocalDateTime.now()));
            }

            ps.setString(6, operation.getDescription());

            ps.executeUpdate();

            try (ResultSet rs = ps.getGeneratedKeys()) {
                if (rs.next()) {
                    operation.setIdOperation(rs.getInt(1));
                }
            }
        }
    }

    @Override
    public void update(OPÉRATIONS operation) throws SQLException {
        String sql = "UPDATE OPÉRATIONS SET id_tresorerie = ?, type = ?, montant = ?, categorie = ?, " +
                "date_operation = ?, description = ? WHERE id_operation = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, operation.getTresorerie().getIdTresorerie());
            ps.setString(2, operation.getType());
            ps.setDouble(3, operation.getMontant());
            ps.setString(4, operation.getCategorie());

            if (operation.getDateOperation() != null) {
                ps.setTimestamp(5, Timestamp.valueOf(operation.getDateOperation()));
            } else {
                ps.setTimestamp(5, Timestamp.valueOf(LocalDateTime.now()));
            }

            ps.setString(6, operation.getDescription());
            ps.setInt(7, operation.getIdOperation());

            ps.executeUpdate();
        }
    }

    @Override
    public void delete(int idOperation) throws SQLException {
        String sql = "DELETE FROM OPÉRATIONS WHERE id_operation = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, idOperation);
            ps.executeUpdate();
        }
    }

    @Override
    public OPÉRATIONS getById(int idOperation) throws SQLException {
        String sql = "SELECT * FROM OPÉRATIONS WHERE id_operation = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, idOperation);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    return mapRowToOperation(rs);
                }
            }
        }
        return null;
    }

    @Override
    public List<OPÉRATIONS> getAll() throws SQLException {
        List<OPÉRATIONS> result = new ArrayList<>();
        String sql = "SELECT * FROM OPÉRATIONS";
        try (PreparedStatement ps = cnx.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {
            while (rs.next()) {
                result.add(mapRowToOperation(rs));
            }
        }
        return result;
    }

    @Override
    public List<OPÉRATIONS> getByTresorerie(TRÉSORERIE tresorerie) throws SQLException {
        String sql = "SELECT * FROM OPÉRATIONS WHERE id_tresorerie = ?";
        List<OPÉRATIONS> result = new ArrayList<>();
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, tresorerie.getIdTresorerie());
            try (ResultSet rs = ps.executeQuery()) {
                while (rs.next()) {
                    result.add(mapRowToOperation(rs, tresorerie));
                }
            }
        }
        return result;
    }

    @Override
    public List<OPÉRATIONS> filterByType(String type) throws SQLException {
        if (type == null) {
            return getAll();
        }
        String lower = type.toLowerCase();
        return getAll()
                .stream()
                .filter(o -> o.getType() != null && o.getType().toLowerCase().equals(lower))
                .collect(Collectors.toList());
    }

    @Override
    public List<OPÉRATIONS> filterByAmountRange(double min, double max) throws SQLException {
        return getAll()
                .stream()
                .filter(o -> o.getMontant() >= min && o.getMontant() <= max)
                .collect(Collectors.toList());
    }

    @Override
    public List<OPÉRATIONS> filterByDateRange(LocalDateTime from, LocalDateTime to) throws SQLException {
        return getAll()
                .stream()
                .filter(o -> {
                    LocalDateTime d = o.getDateOperation();
                    if (d == null) return false;
                    boolean afterFrom = from == null || !d.isBefore(from);
                    boolean beforeTo = to == null || !d.isAfter(to);
                    return afterFrom && beforeTo;
                })
                .collect(Collectors.toList());
    }

    @Override
    public List<OPÉRATIONS> searchByCategorieOrDescription(String keyword) throws SQLException {
        if (keyword == null || keyword.isEmpty()) {
            return getAll();
        }
        String lower = keyword.toLowerCase();
        return getAll()
                .stream()
                .filter(o ->
                        (o.getCategorie() != null && o.getCategorie().toLowerCase().contains(lower)) ||
                        (o.getDescription() != null && o.getDescription().toLowerCase().contains(lower))
                )
                .collect(Collectors.toList());
    }

    private OPÉRATIONS mapRowToOperation(ResultSet rs) throws SQLException {
        int idTresorerie = rs.getInt("id_tresorerie");
        TRÉSORERIE tresorerie = tresorerieService.getById(idTresorerie);
        return mapRowToOperation(rs, tresorerie);
    }

    private OPÉRATIONS mapRowToOperation(ResultSet rs, TRÉSORERIE tresorerie) throws SQLException {
        int id = rs.getInt("id_operation");
        String type = rs.getString("type");
        double montant = rs.getDouble("montant");
        String categorie = rs.getString("categorie");
        String description = rs.getString("description");

        Timestamp ts = rs.getTimestamp("date_operation");
        LocalDateTime dateOperation = ts != null ? ts.toLocalDateTime() : null;

        OPÉRATIONS op = new OPÉRATIONS(id, tresorerie, type, montant, categorie, description, dateOperation);
        return op;
    }
}

