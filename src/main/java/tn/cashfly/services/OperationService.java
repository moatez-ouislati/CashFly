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
        String sql = "INSERT INTO OPÉRATIONS (id_tresorerie, reference, facture, pdf_url, type, montant, categorie, date_operation, description) " +
                "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        try (PreparedStatement ps = cnx.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            ps.setInt(1, operation.getTresorerie().getIdTresorerie());
            ps.setString(2, operation.getReference());
            ps.setString(3, operation.getFacture());
            ps.setString(4, operation.getPdfUrl());
            ps.setString(5, operation.getType().name());
            ps.setDouble(6, operation.getMontant());
            ps.setString(7, operation.getCategorie());

            if (operation.getDateOperation() != null) {
                ps.setTimestamp(8, Timestamp.valueOf(operation.getDateOperation()));
            } else {
                ps.setTimestamp(8, Timestamp.valueOf(LocalDateTime.now()));
            }

            ps.setString(9, operation.getDescription());

            ps.executeUpdate();

            try (ResultSet rs = ps.getGeneratedKeys()) {
                if (rs.next()) {
                    operation.setIdOperation(rs.getInt(1));
                }
            }
            
            // Mise à jour automatique du solde de la trésorerie
            updateTresorerieBalance(operation.getTresorerie().getIdTresorerie(), operation.getMontant(), operation.getType(), true);
        }
    }

    @Override
    public void update(OPÉRATIONS operation) throws SQLException {
        // Pour l'update, on doit d'abord annuler l'ancienne opération
        OPÉRATIONS oldOp = getById(operation.getIdOperation());
        if (oldOp != null) {
            // Annuler l'impact de l'ancienne opération (reverse = true pour soustraire au lieu d'ajouter si c'est un revenu)
            updateTresorerieBalance(oldOp.getTresorerie().getIdTresorerie(), oldOp.getMontant(), oldOp.getType(), false);
        }

        String sql = "UPDATE OPÉRATIONS SET id_tresorerie = ?, reference = ?, facture = ?, pdf_url = ?, type = ?, montant = ?, categorie = ?, " +
                "date_operation = ?, description = ? WHERE id_operation = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, operation.getTresorerie().getIdTresorerie());
            ps.setString(2, operation.getReference());
            ps.setString(3, operation.getFacture());
            ps.setString(4, operation.getPdfUrl());
            ps.setString(5, operation.getType().name());
            ps.setDouble(6, operation.getMontant());
            ps.setString(7, operation.getCategorie());

            if (operation.getDateOperation() != null) {
                ps.setTimestamp(8, Timestamp.valueOf(operation.getDateOperation()));
            } else {
                ps.setTimestamp(8, Timestamp.valueOf(LocalDateTime.now()));
            }

            ps.setString(9, operation.getDescription());
            ps.setInt(10, operation.getIdOperation());

            ps.executeUpdate();
            
            // Appliquer le nouvel impact
            updateTresorerieBalance(operation.getTresorerie().getIdTresorerie(), operation.getMontant(), operation.getType(), true);
        }
    }

    @Override
    public void delete(int idOperation) throws SQLException {
        OPÉRATIONS op = getById(idOperation);
        if (op != null) {
            // Inverser l'impact avant de supprimer
            updateTresorerieBalance(op.getTresorerie().getIdTresorerie(), op.getMontant(), op.getType(), false);
        }

        String sql = "DELETE FROM OPÉRATIONS WHERE id_operation = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, idOperation);
            ps.executeUpdate();
        }
    }

    private void updateTresorerieBalance(int idTresorerie, double montant, OPÉRATIONS.TypeOperation type, boolean isNewImpact) throws SQLException {
        TRÉSORERIE t = tresorerieService.getById(idTresorerie);
        if (t == null) return;

        double currentSolde = t.getSolde();
        boolean isRevenu = type == OPÉRATIONS.TypeOperation.revenu;

        if (isNewImpact) {
            // Ajouter un revenu ou soustraire une dépense
            if (isRevenu) {
                t.setSolde(currentSolde + montant);
            } else {
                t.setSolde(currentSolde - montant);
            }
        } else {
            // Reverse operation: Inverser l'impact précédent
            // Si c'était un revenu, on le retire. Si c'était une dépense, on le rajoute.
            if (isRevenu) {
                t.setSolde(currentSolde - montant);
            } else {
                t.setSolde(currentSolde + montant);
            }
        }
        
        t.setDerniereMaj(LocalDateTime.now());
        tresorerieService.update(t);
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
                .filter(o -> o.getType() != null && o.getType().name().toLowerCase().equals(lower))
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

    @Override
    public String generateNextReference() throws SQLException {
        String sql = "SELECT reference FROM OPÉRATIONS WHERE reference LIKE 'OP-%' ORDER BY id_operation DESC LIMIT 1";
        try (PreparedStatement ps = cnx.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {
            if (rs.next()) {
                String lastRef = rs.getString("reference");
                try {
                    int numericPart = Integer.parseInt(lastRef.substring(3));
                    return String.format("OP-%05d", numericPart + 1);
                } catch (NumberFormatException | StringIndexOutOfBoundsException e) {
                    return "OP-00001";
                }
            }
        }
        return "OP-00001";
    }

    private OPÉRATIONS mapRowToOperation(ResultSet rs) throws SQLException {
        int idTresorerie = rs.getInt("id_tresorerie");
        TRÉSORERIE tresorerie = tresorerieService.getById(idTresorerie);
        return mapRowToOperation(rs, tresorerie);
    }

    private OPÉRATIONS mapRowToOperation(ResultSet rs, TRÉSORERIE tresorerie) throws SQLException {
        int id = rs.getInt("id_operation");
        String reference = rs.getString("reference");
        String facture = rs.getString("facture");
        String pdfUrl = rs.getString("pdf_url");
        String typeStr = rs.getString("type");
        OPÉRATIONS.TypeOperation type = OPÉRATIONS.TypeOperation.valueOf(typeStr);
        double montant = rs.getDouble("montant");
        String categorie = rs.getString("categorie");
        String description = rs.getString("description");

        Timestamp ts = rs.getTimestamp("date_operation");
        LocalDateTime dateOperation = ts != null ? ts.toLocalDateTime() : null;

        OPÉRATIONS op = new OPÉRATIONS(id, tresorerie, reference, facture, pdfUrl, type, montant, categorie, description, dateOperation);
        return op;
    }
}

