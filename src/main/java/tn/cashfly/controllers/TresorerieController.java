package tn.cashfly.controllers;

import tn.cashfly.entities.TRÉSORERIE;
import tn.cashfly.services.ITresorerieService;
import tn.cashfly.services.TresorerieService;

import java.sql.SQLException;
import java.time.LocalDateTime;
import java.util.List;

/**
 * Contrôleur pour la gestion de la trésorerie.
 * Interface pour le CRUD + recherche / filtre avec Streams.
 */
public class TresorerieController {

    private final ITresorerieService tresorerieService = new TresorerieService();

    public TRÉSORERIE createTresorerie(int idEntreprise,
                                       double solde,
                                       String devise) throws SQLException {
        TRÉSORERIE t = new TRÉSORERIE(idEntreprise, solde, devise);
        t.setDerniereMaj(LocalDateTime.now());
        tresorerieService.add(t);
        return t;
    }

    public void updateTresorerie(TRÉSORERIE tresorerie) throws SQLException {
        tresorerie.setDerniereMaj(LocalDateTime.now());
        tresorerieService.update(tresorerie);
    }

    public void deleteTresorerie(int idTresorerie) throws SQLException {
        tresorerieService.delete(idTresorerie);
    }

    public TRÉSORERIE getTresorerieById(int idTresorerie) throws SQLException {
        return tresorerieService.getById(idTresorerie);
    }

    public List<TRÉSORERIE> getAllTresoreries() throws SQLException {
        return tresorerieService.getAll();
    }

    public List<TRÉSORERIE> filterTresorerieByDevise(String devise) throws SQLException {
        return tresorerieService.filterByDevise(devise);
    }

    public List<TRÉSORERIE> filterTresorerieByMinSolde(double minSolde) throws SQLException {
        return tresorerieService.filterBySoldeGreaterThan(minSolde);
    }
}

