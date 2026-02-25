package tn.cashfly;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import tn.cashfly.models.Investissement;
import tn.cashfly.models.RendementInvestissement;

import java.math.BigDecimal;
import java.sql.SQLException;
import java.util.List;

import static org.junit.jupiter.api.Assertions.*;

class CashFlyDataTest {

    private InvestmentDataFixture testData;

    @BeforeEach
    void setUp() throws SQLException {
        testData = new InvestmentDataFixture();
        testData.clearDatabase();
    }

    @Test
    void testCreateInvestments() throws SQLException {
        testData.createInvestments();
        List<Investissement> investments = testData.getAllInvestments();
        assertEquals(10, investments.size());
    }

    @Test
    void testUpdateFirstInvestment() throws SQLException {
        testData.createInvestments();
        testData.updateFirstInvestment();

        Investissement first = testData.getAllInvestments().get(0);

        assertEquals("ACTIF", first.getStatut());
        assertEquals(new BigDecimal("6000"), first.getMontant());
        assertEquals(new BigDecimal("11.0"), first.getTauxRendementPrevu());
        assertEquals("Mise à jour complète du projet A", first.getDescription());
        assertEquals(7, first.getDureeMois());
    }

    @Test
    void testDeleteSecondInvestment() throws SQLException {
        testData.createInvestments();
        testData.deleteSecondInvestment();

        List<Investissement> investments = testData.getAllInvestments();
        assertEquals(9, investments.size());
    }

    @Test
    void testAddRendement() throws SQLException {
        testData.createInvestments();
        testData.addRendement();

        List<RendementInvestissement> rendements = testData.getAllRendements();
        assertFalse(rendements.isEmpty());

        RendementInvestissement r = rendements.get(0);
        int firstInvestId = testData.getAllInvestments().get(0).getIdInvestissement();
        assertEquals(firstInvestId, r.getIdInvestissement());
        assertEquals(new BigDecimal("200.0"), r.getGain());
        assertEquals(new BigDecimal("5200.0"), r.getValeurPortefeuille());
    }
}