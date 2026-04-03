package tn.cashfly.services;

import tn.cashfly.entities.TRÉSORERIE;

import java.sql.SQLException;
import java.util.List;

public interface ITresorerieService {

    // CRUD
    void add(TRÉSORERIE tresorerie) throws SQLException;

    void update(TRÉSORERIE tresorerie) throws SQLException;

    void delete(int idTresorerie) throws SQLException;

    TRÉSORERIE getById(int idTresorerie) throws SQLException;

    List<TRÉSORERIE> getAll() throws SQLException;

    // Recherche / filtre avec Stream
    List<TRÉSORERIE> filterByDevise(String devise) throws SQLException;

    List<TRÉSORERIE> filterBySoldeGreaterThan(double minSolde) throws SQLException;

    String generateNextNumeroCompte() throws SQLException;
}


