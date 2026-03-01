package tn.cashfly.services;

import tn.cashfly.models.RendementInvestissement;
import tn.cashfly.utils.CashFlyDB;

import java.math.BigDecimal;
import java.math.RoundingMode;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

import tn.cashfly.interfaces.Service;

public class ServiceRendement implements Service<RendementInvestissement> {
    private Connection connection;

    public ServiceRendement() {
        connection = CashFlyDB.getInstance().getConnection();
    }

    // CREATE Rendement
    @Override
    public void add(RendementInvestissement r) throws SQLException {
        String req = "INSERT INTO rendement_investissement (id_investissement, date_calcul, gain, perte, valeur_portefeuille) VALUES (?,?,?,?,?)";

        try (PreparedStatement ps = connection.prepareStatement(req)) {
            ps.setInt(1, r.getIdInvestissement());
            ps.setDate(2, r.getDateCalcul());
            ps.setBigDecimal(3, r.getGain());
            ps.setBigDecimal(4, r.getPerte());
            ps.setBigDecimal(5, r.getValeurPortefeuille());

            ps.executeUpdate();
            System.out.println("Rendement ajouté");
        }
    }

    /**
     * Génère automatiquement des projections mensuelles pour un investissement.
     *
     * @param i L'investissement pour lequel générer les rendements.
     */
    public void generateProjections(tn.cashfly.models.Investissement i) throws SQLException {
        BigDecimal montantInitial = i.getMontant();
        BigDecimal tauxAnnuel = i.getTauxRendementPrevu();
        int dureeMois = i.getDureeMois();
        Date dateDebut = i.getDateInvestissement();

        if (montantInitial == null || tauxAnnuel == null || dureeMois <= 0) {
            return;
        }

        // Calcul du gain mensuel : (Montant * (Taux / 100)) / 12
        BigDecimal gainMensuel = montantInitial
                .multiply(tauxAnnuel.divide(new BigDecimal("100"), 4, RoundingMode.HALF_UP))
                .divide(new BigDecimal("12"), 4, RoundingMode.HALF_UP);

        for (int m = 1; m <= dureeMois; m++) {
            // Calcul de la date : dateDebut + m mois
            java.util.Calendar cal = java.util.Calendar.getInstance();
            cal.setTime(dateDebut);
            cal.add(java.util.Calendar.MONTH, m);
            Date dateCalcul = new Date(cal.getTimeInMillis());

            // Valeur portefeuille (simplifiée) : Montant Initial + Gains cumulés
            BigDecimal valeurPortefeuille = montantInitial.add(gainMensuel.multiply(new BigDecimal(m)));

            RendementInvestissement r = new RendementInvestissement();
            r.setIdInvestissement(i.getIdInvestissement());
            r.setDateCalcul(dateCalcul);
            r.setGain(gainMensuel);
            r.setPerte(BigDecimal.ZERO);
            r.setValeurPortefeuille(valeurPortefeuille);

            add(r);
        }
        System.out.println("Projections générées pour l'investissement ID: " + i.getIdInvestissement());
    }

    // This To READ
    @Override
    public List<RendementInvestissement> getAll() throws SQLException {
        List<RendementInvestissement> list = new ArrayList<>();

        try (Statement st = connection.createStatement();
             ResultSet rs = st.executeQuery("SELECT * FROM rendement_investissement")) {

            while (rs.next()) {
                RendementInvestissement r = new RendementInvestissement();
                r.setIdRendement(rs.getInt("id_rendement"));
                r.setIdInvestissement(rs.getInt("id_investissement"));
                r.setDateCalcul(rs.getDate("date_calcul"));
                r.setGain(rs.getBigDecimal("gain"));
                r.setPerte(rs.getBigDecimal("perte"));
                r.setValeurPortefeuille(rs.getBigDecimal("valeur_portefeuille"));

                list.add(r);
            }
        }

        return list;
    }

    // UPDATE
    @Override
    public void update(RendementInvestissement r) throws SQLException {
        String req = "UPDATE rendement_investissement SET gain=?, perte=?, valeur_portefeuille=? WHERE id_rendement=?";
        try (PreparedStatement ps = connection.prepareStatement(req)) {
            ps.setBigDecimal(1, r.getGain());
            ps.setBigDecimal(2, r.getPerte());
            ps.setBigDecimal(3, r.getValeurPortefeuille());
            ps.setInt(4, r.getIdRendement());

            ps.executeUpdate();
            System.out.println("Rendement mis à jour");
        }
    }

    // DELETE
    @Override
    public void delete(RendementInvestissement r) throws SQLException {
        String req = "DELETE FROM rendement_investissement WHERE id_rendement=?";
        try (PreparedStatement ps = connection.prepareStatement(req)) {
            ps.setInt(1, r.getIdRendement());
            ps.executeUpdate();
            System.out.println("Rendement supprimé");
        }
    }

    // DELETE ALL
    @Override
    public void deleteAll() throws SQLException {
        String req = "DELETE FROM rendement_investissement";
        try (Statement st = connection.createStatement()) {
            st.executeUpdate(req);
            System.out.println("Tous les rendements ont été supprimés.");
        }
    }
}