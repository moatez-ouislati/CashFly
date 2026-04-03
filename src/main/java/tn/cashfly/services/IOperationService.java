package tn.cashfly.services;

import tn.cashfly.entities.OPÉRATIONS;
import tn.cashfly.entities.TRÉSORERIE;

import java.sql.SQLException;
import java.time.LocalDateTime;
import java.util.List;

public interface IOperationService {

    // CRUD
    void add(OPÉRATIONS operation) throws SQLException;

    void update(OPÉRATIONS operation) throws SQLException;

    void delete(int idOperation) throws SQLException;

    OPÉRATIONS getById(int idOperation) throws SQLException;

    List<OPÉRATIONS> getAll() throws SQLException;

    // Spécifique
    List<OPÉRATIONS> getByTresorerie(TRÉSORERIE tresorerie) throws SQLException;

    // Recherche / filtre avec Stream
    List<OPÉRATIONS> filterByType(String type) throws SQLException; // \"revenu\" ou \"depense\"

    List<OPÉRATIONS> filterByAmountRange(double min, double max) throws SQLException;

    List<OPÉRATIONS> filterByDateRange(LocalDateTime from, LocalDateTime to) throws SQLException;

    List<OPÉRATIONS> searchByCategorieOrDescription(String keyword) throws SQLException;

    String generateNextReference() throws SQLException;
}


