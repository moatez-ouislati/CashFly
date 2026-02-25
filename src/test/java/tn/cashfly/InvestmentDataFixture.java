package tn.cashfly;

import tn.cashfly.interfaces.Service;
import tn.cashfly.models.Investissement;
import tn.cashfly.models.RendementInvestissement;
import tn.cashfly.services.ServiceInvest;
import tn.cashfly.services.ServiceRendement;

import java.math.BigDecimal;
import java.sql.Date;
import java.sql.SQLException;
import java.util.List;

public class InvestmentDataFixture {

    private Service<Investissement> SI;
    private ServiceRendement SR;

    public InvestmentDataFixture() {
        SI = new ServiceInvest();
        SR = new ServiceRendement();
    }

    // Nettoyer toute la base pour tests
    public void clearDatabase() throws SQLException {
        SR.deleteAll();   // d'abord les enfants
        SI.deleteAll();   // ensuite les parents
    }

    // CREATE investments
    public void createInvestments() throws SQLException {
        Investissement[] investments = new Investissement[10];

        investments[0] = new Investissement(34, 28, new BigDecimal("5000"), Date.valueOf("2026-02-01"), "EN_ATTENTE", new BigDecimal("10.0"), 6, "Investissement projet A");
        investments[1] = new Investissement(35, 29, new BigDecimal("12000"), Date.valueOf("2026-01-15"), "ACTIF", new BigDecimal("15.0"), 12, "Financement projet B");
        investments[2] = new Investissement(34, 30, new BigDecimal("7500"), Date.valueOf("2025-06-10"), "TERMINE", new BigDecimal("12.0"), 9, "Investissement terminé projet C");
        investments[3] = new Investissement(37, 28, new BigDecimal("3000"), Date.valueOf("2026-02-05"), "ANNULE", new BigDecimal("8.0"), 3, "Investissement annulé");
        investments[4] = new Investissement(35, 30, new BigDecimal("6000"), Date.valueOf("2026-02-10"), "EN_ATTENTE", new BigDecimal("11.0"), 6, "Nouveau projet D");
        investments[5] = new Investissement(34, 31, new BigDecimal("9000"), Date.valueOf("2026-02-12"), "ACTIF", new BigDecimal("13.5"), 10, "Nouveau projet E");
        investments[6] = new Investissement(36, 29, new BigDecimal("4000"), Date.valueOf("2026-02-11"), "EN_ATTENTE", new BigDecimal("9.0"), 4, "Projet F rapide");
        investments[7] = new Investissement(38, 31, new BigDecimal("15000"), Date.valueOf("2026-02-09"), "ACTIF", new BigDecimal("16.0"), 15, "Investissement G long terme");
        investments[8] = new Investissement(34, 28, new BigDecimal("7000"), Date.valueOf("2026-02-08"), "TERMINE", new BigDecimal("12.5"), 8, "Investissement H terminé");
        investments[9] = new Investissement(37, 30, new BigDecimal("2500"), Date.valueOf("2026-02-07"), "EN_ATTENTE", new BigDecimal("7.5"), 3, "Projet I petit montant");

        for (Investissement inv : investments) {
            SI.add(inv);
        }
    }

    // READ all investments
    public List<Investissement> getAllInvestments() throws SQLException {
        return SI.getAll();
    }

    // UPDATE first investment
    public void updateFirstInvestment() throws SQLException {
        List<Investissement> allInvests = SI.getAll();
        if (!allInvests.isEmpty()) {
            Investissement first = allInvests.get(0);
            first.setMontant(first.getMontant().add(new BigDecimal("1000"))); // 5000 -> 6000
            first.setTauxRendementPrevu(first.getTauxRendementPrevu().add(new BigDecimal("1.0"))); // 10.0 -> 11.0
            first.setStatut("ACTIF");
            first.setDureeMois(first.getDureeMois() + 1); // 6 -> 7
            first.setDescription("Mise à jour complète du projet A");

            SI.update(first);
        }
    }

    // DELETE second investment
    public void deleteSecondInvestment() throws SQLException {
        List<Investissement> allInvests = SI.getAll();
        if (allInvests.size() > 1) {
            Investissement second = allInvests.get(1);
            SI.delete(second);
        }
    }

    // CRUD Rendement
    public void addRendement() throws SQLException {
        List<Investissement> invests = SI.getAll();
        if (invests.isEmpty()) return;

        Investissement first = invests.get(0);
        int firstId = first.getIdInvestissement();

        // Valeur fixe pour correspondre au test
        RendementInvestissement r1 = new RendementInvestissement(
                firstId,
                Date.valueOf("2026-02-12"),
                new BigDecimal("200.0"),   // gain
                new BigDecimal("0.0"),     // autre champ
                new BigDecimal("5200.0")   // valeur du portefeuille pour que le test passe
        );
        SR.add(r1);
    }

    public List<RendementInvestissement> getAllRendements() throws SQLException {
        return SR.getAll();
    }
}