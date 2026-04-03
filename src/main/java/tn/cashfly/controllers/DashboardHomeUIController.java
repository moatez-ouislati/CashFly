package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.scene.control.Label;
import tn.cashfly.session.UserSession;
import tn.cashfly.utils.MyDataBase;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class DashboardHomeUIController {

    @FXML
    private Label welcomeLabel;

    @FXML
    private Label homeRevenusLabel;

    @FXML
    private Label homeDepensesLabel;

    @FXML
    private Label homeEntreprisesLabel;

    @FXML
    private Label homeTresoreriesLabel;

    @FXML
    private Label homeOpsLabel;

    @FXML
    private Label homeDocsLabel;

    @FXML
    public void initialize() {
        if (UserSession.getFullName() != null) {
            welcomeLabel.setText("Bonjour, " + UserSession.getFullName() + " 👋");
        }
        updateStats();
    }

    public void updateStats() {
        if (UserSession.getUserId() == null) return;

        int userId = UserSession.getUserId();

        try {
            Connection cnx = MyDataBase.getInstance().getCnx();
            if (cnx == null || cnx.isClosed()) return;

            // Total entreprises
            try (PreparedStatement ps = cnx.prepareStatement(
                    "SELECT COUNT(*) FROM entreprises WHERE id_proprietaire = ?")) {
                ps.setInt(1, userId);
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next()) {
                        homeEntreprisesLabel.setText(String.valueOf(rs.getInt(1)));
                    }
                }
            }

            // Total trésoreries
            try (PreparedStatement ps = cnx.prepareStatement(
                    "SELECT COUNT(*) FROM TRÉSORERIE t JOIN entreprises e ON t.id_entreprise = e.id_entreprise WHERE e.id_proprietaire = ?")) {
                ps.setInt(1, userId);
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next()) {
                        homeTresoreriesLabel.setText(String.valueOf(rs.getInt(1)));
                    }
                }
            }

            // Total opérations, revenus, dépenses
            try (PreparedStatement ps = cnx.prepareStatement(
                    "SELECT COUNT(*) AS total_ops, " +
                            "SUM(CASE WHEN o.type = 'revenu' THEN o.montant ELSE 0 END) AS total_revenus, " +
                            "SUM(CASE WHEN o.type = 'depense' THEN o.montant ELSE 0 END) AS total_depenses " +
                            "FROM OPÉRATIONS o " +
                            "JOIN TRÉSORERIE t ON o.id_tresorerie = t.id_tresorerie " +
                            "JOIN entreprises e ON t.id_entreprise = e.id_entreprise " +
                            "WHERE e.id_proprietaire = ?")) {
                ps.setInt(1, userId);
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next()) {
                        homeOpsLabel.setText(String.valueOf(rs.getInt("total_ops")));
                        homeRevenusLabel.setText(String.format("%.2f TND", rs.getDouble("total_revenus")));
                        homeDepensesLabel.setText(String.format("%.2f TND", rs.getDouble("total_depenses")));
                    }
                }
            }

            // Total documents
            try (PreparedStatement ps = cnx.prepareStatement(
                    "SELECT COUNT(*) FROM documents d " +
                            "JOIN entreprises e ON d.id_entreprise = e.id_entreprise " +
                            "WHERE e.id_proprietaire = ?")) {
                ps.setInt(1, userId);
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next()) {
                        homeDocsLabel.setText(String.valueOf(rs.getInt(1)));
                    }
                }
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
