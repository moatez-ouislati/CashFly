package tn.cashfly.services;

import tn.cashfly.entities.Entreprise;

import java.sql.SQLException;
import java.util.List;

public interface IEntrepriseService {

    // CRUD
    void add(Entreprise entreprise) throws SQLException;

    void update(Entreprise entreprise) throws SQLException;

    void delete(int idEntreprise) throws SQLException;

    Entreprise getById(int idEntreprise) throws SQLException;

    List<Entreprise> getAll() throws SQLException;

    // Recherche / filtre avec Stream
    List<Entreprise> searchByName(String keyword) throws SQLException;

    List<Entreprise> filterBySecteur(String secteur) throws SQLException;
}


