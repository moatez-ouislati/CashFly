package tn.cashfly.services;

import tn.cashfly.entities.ENTREPRISE;

import java.sql.SQLException;
import java.util.List;

public interface IEntrepriseService {

    // CRUD
    void add(ENTREPRISE entreprise) throws SQLException;

    void update(ENTREPRISE entreprise) throws SQLException;

    void delete(int idEntreprise) throws SQLException;

    ENTREPRISE getById(int idEntreprise) throws SQLException;

    List<ENTREPRISE> getAll() throws SQLException;

    // Recherche / filtre avec Stream
    List<ENTREPRISE> searchByName(String keyword) throws SQLException;

    List<ENTREPRISE> filterBySecteur(String secteur) throws SQLException;
}

