package tn.cashfly.services;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import tn.cashfly.entities.Investissement;
import tn.cashfly.entities.RendementInvestissement;
import tn.cashfly.utils.MyDataBase;

import java.math.BigDecimal;
import java.math.RoundingMode;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ServiceRendement implements CrudInterface<RendementInvestissement> {
    private static final Logger log = LoggerFactory.getLogger(ServiceRendement.class);
    private Connection connection;

    public ServiceRendement() {
        connection = MyDataBase.getInstance().getCnx();
        ensureTableExists();
    }

    @Override
    public void ajouter(RendementInvestissement r) throws SQLException {
        String req = "INSERT INTO rendement_investissement (id_investissement, date_calcul, gain, perte, valeur_portefeuille) VALUES (?,?,?,?,?)";

        try (PreparedStatement ps = connection.prepareStatement(req)) {
            ps.setInt(1, r.getIdInvestissement());
            ps.setDate(2, r.getDateCalcul());
            ps.setBigDecimal(3, r.getGain());
            ps.setBigDecimal(4, r.getPerte());
            ps.setBigDecimal(5, r.getValeurPortefeuille());

            ps.executeUpdate();
        }
    }

    public void generateProjections(Investissement i) throws SQLException {
        BigDecimal montantInitial = i.getMontant();
        BigDecimal tauxAnnuel = i.getTauxRendementPrevu();
        int dureeMois = i.getDureeMois();
        Date dateDebut = i.getDateInvestissement();

        if (montantInitial == null || tauxAnnuel == null || dureeMois <= 0) {
            return;
        }

        BigDecimal gainMensuel = montantInitial
                .multiply(tauxAnnuel.divide(new BigDecimal("100"), 4, RoundingMode.HALF_UP))
                .divide(new BigDecimal("12"), 4, RoundingMode.HALF_UP);

        for (int m = 1; m <= dureeMois; m++) {
            java.util.Calendar cal = java.util.Calendar.getInstance();
            cal.setTime(dateDebut);
            cal.add(java.util.Calendar.MONTH, m);
            Date dateCalcul = new Date(cal.getTimeInMillis());

            BigDecimal valeurPortefeuille = montantInitial.add(gainMensuel.multiply(new BigDecimal(m)));

            RendementInvestissement r = new RendementInvestissement();
            r.setIdInvestissement(i.getIdInvestissement());
            r.setDateCalcul(dateCalcul);
            r.setGain(gainMensuel);
            r.setPerte(BigDecimal.ZERO);
            r.setValeurPortefeuille(valeurPortefeuille);

            ajouter(r);
        }
    }

    @Override
    public List<RendementInvestissement> afficher() throws SQLException {
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

    @Override
    public void modifier(RendementInvestissement r) throws SQLException {
        String req = "UPDATE rendement_investissement SET id_investissement=?, date_calcul=?, gain=?, perte=?, valeur_portefeuille=? WHERE id_rendement=?";
        try (PreparedStatement ps = connection.prepareStatement(req)) {
            ps.setInt(1, r.getIdInvestissement());
            ps.setDate(2, r.getDateCalcul());
            ps.setBigDecimal(3, r.getGain());
            ps.setBigDecimal(4, r.getPerte());
            ps.setBigDecimal(5, r.getValeurPortefeuille());
            ps.setInt(6, r.getIdRendement());
            ps.executeUpdate();
        }
    }

    @Override
    public void supprimer(RendementInvestissement r) throws SQLException {
        String req = "DELETE FROM rendement_investissement WHERE id_rendement=?";
        try (PreparedStatement ps = connection.prepareStatement(req)) {
            ps.setInt(1, r.getIdRendement());
            ps.executeUpdate();
        }
    }

    private void ensureTableExists() {
        if (connection == null) {
            return;
        }
        String ddl = """
                CREATE TABLE IF NOT EXISTS rendement_investissement (
                    id_rendement INT AUTO_INCREMENT PRIMARY KEY,
                    id_investissement INT NOT NULL,
                    date_calcul DATE NOT NULL,
                    gain DECIMAL(15, 2) DEFAULT 0,
                    perte DECIMAL(15, 2) DEFAULT 0,
                    valeur_portefeuille DECIMAL(15, 2) NOT NULL,
                    CONSTRAINT fk_rend_inv_invest FOREIGN KEY (id_investissement)
                        REFERENCES investissement(id_investissement) ON DELETE CASCADE
                )
                """;
        try (Statement st = connection.createStatement()) {
            st.executeUpdate(ddl);
        } catch (SQLException e) {
            log.error("Erreur lors de la création de la table rendement_investissement", e);
        }
    }
}
