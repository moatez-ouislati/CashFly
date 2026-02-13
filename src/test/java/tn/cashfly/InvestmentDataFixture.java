package tn.cashfly;

import tn.cashfly.interfaces.Service;
import tn.cashfly.models.Investissement;
import tn.cashfly.models.RendementInvestissement;
import tn.cashfly.services.ServiceInvest;
import tn.cashfly.services.ServiceRendement;

import java.sql.Date;
import java.util.List;

public class InvestmentDataFixture {

    private Service<Investissement> SI;
    private ServiceRendement SR;

    public InvestmentDataFixture() {
        SI = new ServiceInvest();
        SR = new ServiceRendement();
    }

    // Nettoyer toute la base pour tests
    public void clearDatabase() {
        SR.deleteAll();   // d'abord les enfants
        SI.deleteAll();   // ensuite les parents
    }

    // CREATE investments
    public void createInvestments() {
        Investissement[] investments = new Investissement[10];

        investments[0] = new Investissement(34, 28, 5000, Date.valueOf("2026-02-01"), "EN_ATTENTE", 10.0, 6, "Investissement projet A");
        investments[1] = new Investissement(35, 29, 12000, Date.valueOf("2026-01-15"), "ACTIF", 15.0, 12, "Financement projet B");
        investments[2] = new Investissement(34, 30, 7500, Date.valueOf("2025-06-10"), "TERMINE", 12.0, 9, "Investissement terminé projet C");
        investments[3] = new Investissement(37, 28, 3000, Date.valueOf("2026-02-05"), "ANNULE", 8.0, 3, "Investissement annulé");
        investments[4] = new Investissement(35, 30, 6000, Date.valueOf("2026-02-10"), "EN_ATTENTE", 11.0, 6, "Nouveau projet D");
        investments[5] = new Investissement(34, 31, 9000, Date.valueOf("2026-02-12"), "ACTIF", 13.5, 10, "Nouveau projet E");
        investments[6] = new Investissement(36, 29, 4000, Date.valueOf("2026-02-11"), "EN_ATTENTE", 9.0, 4, "Projet F rapide");
        investments[7] = new Investissement(38, 31, 15000, Date.valueOf("2026-02-09"), "ACTIF", 16.0, 15, "Investissement G long terme");
        investments[8] = new Investissement(34, 28, 7000, Date.valueOf("2026-02-08"), "TERMINE", 12.5, 8, "Investissement H terminé");
        investments[9] = new Investissement(37, 30, 2500, Date.valueOf("2026-02-07"), "EN_ATTENTE", 7.5, 3, "Projet I petit montant");

        for (Investissement inv : investments) {
            SI.add(inv);
        }
    }

    // READ all investments
    public List<Investissement> getAllInvestments() {
        return SI.getAll();
    }

    // UPDATE first investment
    public void updateFirstInvestment() {
        List<Investissement> allInvests = SI.getAll();
        if (!allInvests.isEmpty()) {
            Investissement first = allInvests.get(0);
            first.setMontant(first.getMontant() + 1000);
            first.setStatut("ACTIF");
            first.setTauxRendementPrevu(first.getTauxRendementPrevu() + 1.0);
            first.setDureeMois(first.getDureeMois() + 1);
            first.setDescription("Mise à jour complète du projet A");

            SI.update(first);
        }
    }

    // DELETE second investment
    public void deleteSecondInvestment() {
        List<Investissement> allInvests = SI.getAll();
        if (allInvests.size() > 1) {
            Investissement second = allInvests.get(1);
            SI.delete(second);
        }
    }

    // CRUD Rendement
    public void addRendement() {
        List<Investissement> invests = SI.getAll();
        if (invests.isEmpty()) return;

        // Récupérer le vrai ID du premier investissement
        int firstId = invests.get(0).getIdInvestissement();

        RendementInvestissement r1 = new RendementInvestissement(
                firstId,
                Date.valueOf("2026-02-12"),
                200.0,
                0.0,
                5200.0
        );
        SR.add(r1);
    }

    public List<RendementInvestissement> getAllRendements() {
        return SR.getAll();
    }
}