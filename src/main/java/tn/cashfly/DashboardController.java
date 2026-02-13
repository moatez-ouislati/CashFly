package tn.cashfly;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.StackPane;
import tn.cashfly.session.UserSession;
import tn.cashfly.utils.MyDataBase;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class DashboardController {

    @FXML
    private StackPane contentRoot;

    @FXML
    private Button btnEntreprises;

    @FXML
    private Button btnTresorerie;

    @FXML
    private Button btnOperations;

    @FXML
    private Label userNameLabel;

    @FXML
    private Label userEmailLabel;

    @FXML
    private Label totalEntreprisesLabel;

    @FXML
    private Label totalTresoreriesLabel;

    @FXML
    private Label totalOperationsLabel;

    @FXML
    private Label totalRevenusLabel;

    @FXML
    private Label totalDepensesLabel;

    @FXML
    public void initialize() {
        updateUserInfo();
        updateStats();
        // Vue par défaut : entreprises
        showEntreprises();
    }

    @FXML
    public void showEntreprises() {
        loadView("/entreprises.fxml");
        updateStats();
    }

    @FXML
    public void showTresorerie() {
        if (UserSession.getCurrentEntrepriseId() == null) {
            showInfo("Veuillez d'abord sélectionner une entreprise dans l'onglet Entreprises.");
            return;
        }
        loadView("/tresorerie.fxml");
        updateStats();
    }

    @FXML
    public void showOperations() {
        if (UserSession.getCurrentEntrepriseId() == null) {
            showInfo("Veuillez d'abord sélectionner une entreprise.");
            return;
        }
        if (UserSession.getCurrentTresorerieId() == null) {
            showInfo("Veuillez d'abord sélectionner une trésorerie dans l'onglet Trésorerie.");
            return;
        }
        loadView("/operations.fxml");
        updateStats();
    }

    private void loadView(String fxmlPath) {
        try {
            Node view = FXMLLoader.load(getClass().getResource(fxmlPath));
            contentRoot.getChildren().setAll(view);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void updateUserInfo() {
        if (UserSession.getUserId() != null) {
            userNameLabel.setText(UserSession.getFullName());
            userEmailLabel.setText(UserSession.getEmail());
        } else {
            userNameLabel.setText("Invite");
            userEmailLabel.setText("");
        }
    }

    private void updateStats() {
        if (UserSession.getUserId() == null) {
            totalEntreprisesLabel.setText("0");
            totalTresoreriesLabel.setText("0");
            totalOperationsLabel.setText("0");
            totalRevenusLabel.setText("0 TND");
            totalDepensesLabel.setText("0 TND");
            return;
        }

        int userId = UserSession.getUserId();

        try {
            Connection cnx = MyDataBase.getInstance().getCnx();
            if (cnx == null || cnx.isClosed()) {
                return;
            }

            // Total entreprises du propriétaire
            try (PreparedStatement ps = cnx.prepareStatement(
                    "SELECT COUNT(*) FROM entreprises WHERE id_proprietaire = ?")) {
                ps.setInt(1, userId);
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next()) {
                        totalEntreprisesLabel.setText(String.valueOf(rs.getInt(1)));
                    }
                }
            }

            // Total trésoreries liées aux entreprises du propriétaire
            try (PreparedStatement ps = cnx.prepareStatement(
                    "SELECT COUNT(*) FROM TRÉSORERIE t " +
                            "JOIN entreprises e ON t.id_entreprise = e.id_entreprise " +
                            "WHERE e.id_proprietaire = ?")) {
                ps.setInt(1, userId);
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next()) {
                        totalTresoreriesLabel.setText(String.valueOf(rs.getInt(1)));
                    }
                }
            }

            // Total opérations + revenus / dépenses
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
                        int totalOps = rs.getInt("total_ops");
                        double totalRevenus = rs.getDouble("total_revenus");
                        double totalDepenses = rs.getDouble("total_depenses");

                        totalOperationsLabel.setText(String.valueOf(totalOps));
                        totalRevenusLabel.setText(String.format("%.2f TND", totalRevenus));
                        totalDepensesLabel.setText(String.format("%.2f TND", totalDepenses));
                    }
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private void showInfo(String message) {
        javafx.scene.control.Alert alert = new javafx.scene.control.Alert(javafx.scene.control.Alert.AlertType.INFORMATION);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }
}

