package tn.cashfly.controllers;

import tn.cashfly.entities.OPÉRATIONS;
import tn.cashfly.entities.TRÉSORERIE;
import tn.cashfly.services.IOperationService;
import tn.cashfly.services.OperationService;

import java.sql.SQLException;
import java.time.LocalDateTime;
import java.util.List;

/**
 * Contrôleur pour la gestion des opérations (revenus / dépenses).
 * Interface pour le CRUD + recherche / filtre avec Streams.
 */
public class OperationController {

    private final IOperationService operationService = new OperationService();

    public OPÉRATIONS createOperation(TRÉSORERIE tresorerie,
                                      String reference,
                                      String facture,
                                      String pdfUrl,
                                      OPÉRATIONS.TypeOperation type,
                                      double montant,
                                      String categorie,
                                      String description) throws SQLException {
        OPÉRATIONS op = new OPÉRATIONS(tresorerie, reference, facture, pdfUrl, type, montant, categorie, description);
        op.setDateOperation(LocalDateTime.now());
        operationService.add(op);
        return op;
    }

    public void updateOperation(OPÉRATIONS operation) throws SQLException {
        operation.setDateOperation(LocalDateTime.now());
        operationService.update(operation);
    }

    public void deleteOperation(int idOperation) throws SQLException {
        operationService.delete(idOperation);
    }

    public OPÉRATIONS getOperationById(int idOperation) throws SQLException {
        return operationService.getById(idOperation);
    }

    public List<OPÉRATIONS> getAllOperations() throws SQLException {
        return operationService.getAll();
    }

    public List<OPÉRATIONS> getOperationsByTresorerie(TRÉSORERIE tresorerie) throws SQLException {
        return operationService.getByTresorerie(tresorerie);
    }

    // ====== Filtres / recherche avec Streams ======

    public List<OPÉRATIONS> filterOperationsByType(String type) throws SQLException {
        return operationService.filterByType(type);
    }

    public List<OPÉRATIONS> filterOperationsByAmountRange(double min, double max) throws SQLException {
        return operationService.filterByAmountRange(min, max);
    }

    public List<OPÉRATIONS> filterOperationsByDateRange(LocalDateTime from, LocalDateTime to) throws SQLException {
        return operationService.filterByDateRange(from, to);
    }

    public List<OPÉRATIONS> searchOperationsByKeyword(String keyword) throws SQLException {
        return operationService.searchByCategorieOrDescription(keyword);
    }

    public String generateNextReference() throws SQLException {
        return operationService.generateNextReference();
    }
}

