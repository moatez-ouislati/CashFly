package tn.cashfly;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import tn.cashfly.models.Investissement;
import tn.cashfly.models.RendementInvestissement;

import java.util.List;

import static org.junit.jupiter.api.Assertions.*;

class CashFlyDataTest {

    private InvestmentDataFixture testData;

    @BeforeEach
    void setUp() {
        testData = new InvestmentDataFixture();
        testData.clearDatabase(); // nettoyer la DB avant chaque test
    }

    @Test
    void testCreateInvestments() {
        testData.createInvestments();
        List<Investissement> investments = testData.getAllInvestments();
        assertEquals(10, investments.size(), "Il doit y avoir 10 investissements après création");
    }

    @Test
    void testUpdateFirstInvestment() {
        testData.createInvestments();
        testData.updateFirstInvestment();

        List<Investissement> investments = testData.getAllInvestments();
        Investissement first = investments.get(0);

        assertEquals("ACTIF", first.getStatut(), "Le statut du premier investissement doit être ACTIF");
        assertTrue(first.getMontant() > 5000, "Le montant du premier investissement doit avoir été augmenté");
        assertEquals("Mise à jour complète du projet A", first.getDescription());
        assertEquals(7, first.getDureeMois(), "La durée en mois doit avoir été augmentée de 1");
        assertEquals(11.0, first.getTauxRendementPrevu(), "Le taux de rendement doit avoir été augmenté de 1.0");
    }

    @Test
    void testDeleteSecondInvestment() {
        testData.createInvestments();
        testData.deleteSecondInvestment();

        List<Investissement> investments = testData.getAllInvestments();
        assertEquals(9, investments.size(), "Après suppression du deuxième, il doit rester 9 investissements");
    }

    @Test
    void testAddRendement() {
        // Créer les investissements dans la base
        testData.createInvestments();

        // Ajouter un rendement pour le premier investissement créé
        testData.addRendement();

        // Récupérer tous les rendements
        List<RendementInvestissement> rendements = testData.getAllRendements();
        assertFalse(rendements.isEmpty(), "Il doit y avoir au moins un rendement ajouté");

        // Récupérer le premier rendement ajouté
        RendementInvestissement r = rendements.get(0);

        // Récupérer le vrai ID du premier investissement
        int firstInvestId = testData.getAllInvestments().get(0).getIdInvestissement();

        // Vérifications
        assertEquals(firstInvestId, r.getIdInvestissement(),
                "Le rendement doit être lié au premier investissement créé");
        assertEquals(200.0, r.getGain(), "Le gain du rendement doit être 200.0");
        assertEquals(5200.0, r.getValeurPortefeuille(), "La valeur du portefeuille doit être 5200.0");
    }
}