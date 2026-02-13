package tn.cashfly.controllers;

import tn.cashfly.entities.ENTREPRISE;
import tn.cashfly.services.EntrepriseService;
import tn.cashfly.services.IEntrepriseService;

import java.sql.SQLException;
import java.time.LocalDate;
import java.util.List;

/**
 * Contrôleur pour la gestion des entreprises.
 * Cette classe représente l'interface \"Entreprise\" :
 *  - choix / création / modification / suppression d'une entreprise
 *  - recherche / filtre via les Streams (nom, secteur)
 */
public class EntrepriseController {

    private final IEntrepriseService entrepriseService = new EntrepriseService();

    public ENTREPRISE createEntreprise(String nom,
                                       String secteur,
                                       String formeJuridique,
                                       LocalDate dateCreation,
                                       double capital,
                                       int idProprietaire) throws SQLException {
        ENTREPRISE e = new ENTREPRISE(nom, secteur, formeJuridique, dateCreation, capital, idProprietaire);
        entrepriseService.add(e);
        return e;
    }

    public void updateEntreprise(ENTREPRISE entreprise) throws SQLException {
        entrepriseService.update(entreprise);
    }

    public void deleteEntreprise(int idEntreprise) throws SQLException {
        entrepriseService.delete(idEntreprise);
    }

    public ENTREPRISE getEntrepriseById(int idEntreprise) throws SQLException {
        return entrepriseService.getById(idEntreprise);
    }

    public List<ENTREPRISE> getAllEntreprises() throws SQLException {
        return entrepriseService.getAll();
    }

    public List<ENTREPRISE> searchEntreprisesByName(String keyword) throws SQLException {
        return entrepriseService.searchByName(keyword);
    }

    public List<ENTREPRISE> filterEntreprisesBySecteur(String secteur) throws SQLException {
        return entrepriseService.filterBySecteur(secteur);
    }
}

