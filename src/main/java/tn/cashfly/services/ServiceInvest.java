package tn.cashfly.services;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import tn.cashfly.entities.Investissement;
import tn.cashfly.utils.MyDataBase;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ServiceInvest implements CrudInterface<Investissement> {

    private static final Logger log = LoggerFactory.getLogger(ServiceInvest.class);
    private Connection connection;

    public ServiceInvest() {
        connection = MyDataBase.getInstance().getCnx();
    }

    @Override
    public void ajouter(Investissement i) throws SQLException {
        if (connection == null) {
            throw new SQLException("Erreur : Pas de connexion à la base de données.");
        }
        String req = "INSERT INTO investissement " +
                "(id_investisseur,id_entreprise,montant,date_investissement,statut,taux_rendement_prevu,duree_mois,description) "
                +
                "VALUES (?,?,?,?,?,?,?,?)";
        boolean oldAutoCommit = connection.getAutoCommit();
        try (PreparedStatement ps = connection.prepareStatement(req, Statement.RETURN_GENERATED_KEYS)) {
            connection.setAutoCommit(false);

            ps.setInt(1, i.getIdInvestisseur());
            ps.setInt(2, i.getIdEntreprise());
            ps.setBigDecimal(3, i.getMontant());
            ps.setDate(4, i.getDateInvestissement());

            String status = i.getStatut();
            if (status == null || status.trim().isEmpty()) {
                status = "EN_ATTENTE";
            }
            ps.setString(5, status.toUpperCase().trim());

            ps.setBigDecimal(6, i.getTauxRendementPrevu());
            ps.setInt(7, i.getDureeMois());
            ps.setString(8, i.getDescription());

            int affectedRows = ps.executeUpdate();
            if (affectedRows == 0) {
                throw new SQLException("Échec de l'insertion, aucune ligne ajoutée.");
            }

            try (ResultSet generatedKeys = ps.getGeneratedKeys()) {
                if (generatedKeys.next()) {
                    i.setIdInvestissement(generatedKeys.getInt(1));
                }
            }

            ServiceRendement serviceRendement = new ServiceRendement();
            serviceRendement.generateProjections(i);

            connection.commit();
            log.info("Investissement {} inséré avec projections", i.getIdInvestissement());
        } catch (SQLException e) {
            try {
                connection.rollback();
            } catch (SQLException rb) {
                log.error("Erreur lors du rollback après échec d'ajout investissement", rb);
            }
            log.error("Erreur lors de l'ajout d'un investissement", e);
            throw e;
        } finally {
            try {
                connection.setAutoCommit(oldAutoCommit);
            } catch (SQLException ignored) {
            }
        }
    }

    @Override
    public List<Investissement> afficher() throws SQLException {
        List<Investissement> list = new ArrayList<>();
        if (connection == null) {
            throw new SQLException("Erreur : Pas de connexion à la base de données.");
        }

        Statement st = connection.createStatement();
        ResultSet rs = st.executeQuery("SELECT * FROM investissement");

        while (rs.next()) {
            Investissement i = new Investissement();
            i.setIdInvestissement(rs.getInt("id_investissement"));
            i.setIdInvestisseur(rs.getInt("id_investisseur"));
            i.setIdEntreprise(rs.getInt("id_entreprise"));
            i.setMontant(rs.getBigDecimal("montant"));
            i.setDateInvestissement(rs.getDate("date_investissement"));
            i.setStatut(rs.getString("statut"));
            i.setTauxRendementPrevu(rs.getBigDecimal("taux_rendement_prevu"));
            i.setDureeMois(rs.getInt("duree_mois"));
            i.setDescription(rs.getString("description"));

            list.add(i);
        }

        return list;
    }

    @Override
    public void modifier(Investissement i) throws SQLException {
        if (connection == null) {
            throw new SQLException("Erreur : Pas de connexion à la base de données.");
        }
        String req = "UPDATE investissement SET id_investisseur=?, id_entreprise=?, montant=?, date_investissement=?, statut=?, taux_rendement_prevu=?, duree_mois=?, description=? WHERE id_investissement=?";
        PreparedStatement ps = connection.prepareStatement(req);
        ps.setInt(1, i.getIdInvestisseur());
        ps.setInt(2, i.getIdEntreprise());
        ps.setBigDecimal(3, i.getMontant());
        ps.setDate(4, i.getDateInvestissement());
        ps.setString(5, i.getStatut());
        ps.setBigDecimal(6, i.getTauxRendementPrevu());
        ps.setInt(7, i.getDureeMois());
        ps.setString(8, i.getDescription());
        ps.setInt(9, i.getIdInvestissement());
        ps.executeUpdate();
    }

    @Override
    public void supprimer(Investissement i) throws SQLException {
        if (connection == null) {
            throw new SQLException("Erreur : Pas de connexion à la base de données.");
        }
        String req = "DELETE FROM investissement WHERE id_investissement=?";
        PreparedStatement ps = connection.prepareStatement(req);
        ps.setInt(1, i.getIdInvestissement());
        ps.executeUpdate();
    }
}
