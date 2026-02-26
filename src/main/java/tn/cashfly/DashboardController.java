package tn.cashfly;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.StackPane;
import javafx.stage.Stage;
import javafx.animation.FadeTransition;
import javafx.util.Duration;
import tn.cashfly.session.UserSession;
import tn.cashfly.utils.MyDataBase;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class DashboardController {

    private static DashboardController instance;

    public static DashboardController getInstance() {
        return instance;
    }

    @FXML
    private StackPane contentRoot;

    @FXML
    private Button btnHome;

    @FXML
    private Button btnEntreprises;

    @FXML
    private Button btnTresorerie;

    @FXML
    private Button btnOperations;

    @FXML
    private Button btnStatistiques;

    @FXML
    private Label pageTitle;

    @FXML
    private Label pageSubtitle;

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
        instance = this;
        updateUserInfo();
        updateStats();
        // Vue par défaut : Accueil
        try {
            showHome();
        } catch (Exception e) {
            System.err.println("Erreur lors de l'initialisation de la vue Accueil: " + e.getMessage());
            e.printStackTrace();
        }
    }

    @FXML
    public void showHome() {
        loadView("/dashboard_home.fxml");
        updateActiveButton(btnHome);
        pageTitle.setText("Tableau de Bord");
        pageSubtitle.setText("Aperçu général de vos performances financières.");
        updateStats();
    }

    @FXML
    public void showEntreprises() {
        loadView("/entreprises.fxml");
        updateActiveButton(btnEntreprises);
        pageTitle.setText("Entreprises");
        pageSubtitle.setText("Gérez vos entreprises et leurs informations financières.");
        updateStats();
    }

    @FXML
    public void showTresorerie() {
        if (UserSession.getCurrentEntrepriseId() == null) {
            showInfo("Veuillez d'abord sélectionner une entreprise dans l'onglet Entreprises.");
            return;
        }
        loadView("/tresorerie.fxml");
        updateActiveButton(btnTresorerie);
        pageTitle.setText("Trésorerie");
        pageSubtitle.setText("Suivi de vos comptes et flux de trésorerie.");
        updateStats();
    }

    @FXML
    public void showOperations() {
        if (UserSession.getCurrentEntrepriseId() == null) {
            showInfo("Veuillez d'abord sélectionner une entreprise.");
            return;
        }
        loadView("/operations.fxml");
        updateActiveButton(btnOperations);
        pageTitle.setText("Opérations");
        pageSubtitle.setText("Enregistrement et analyse de vos transactions.");
        updateStats();
    }

    @FXML
    public void showStatistiques() {
        if (UserSession.getCurrentEntrepriseId() == null) {
            showInfo("Veuillez d'abord sélectionner une entreprise.");
            return;
        }
        loadView("/statistiques.fxml");
        updateActiveButton(btnStatistiques);
        pageTitle.setText("Statistiques");
        pageSubtitle.setText("Visualisation de vos indicateurs de performance.");
    }

    @FXML
    public void handleLogout(javafx.event.ActionEvent event) {
        UserSession.clear();
        try {
            Parent root = FXMLLoader.load(getClass().getResource("/login.fxml"));
            Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
            stage.setScene(new Scene(root));
            stage.setTitle("Cashfly - Connexion");
            stage.centerOnScreen();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void updateActiveButton(Button activeBtn) {
        if (activeBtn == null) return;
        
        if (btnHome != null) btnHome.getStyleClass().remove("active-nav-btn");
        if (btnEntreprises != null) btnEntreprises.getStyleClass().remove("active-nav-btn");
        if (btnTresorerie != null) btnTresorerie.getStyleClass().remove("active-nav-btn");
        if (btnOperations != null) btnOperations.getStyleClass().remove("active-nav-btn");
        if (btnStatistiques != null) btnStatistiques.getStyleClass().remove("active-nav-btn");

        activeBtn.getStyleClass().add("active-nav-btn");
    }

    private void loadView(String fxmlPath) {
        try {
            Node view = FXMLLoader.load(getClass().getResource(fxmlPath));
            view.setOpacity(0);
            view.setScaleX(0.98);
            view.setScaleY(0.98);
            contentRoot.getChildren().setAll(view);
            
            FadeTransition ft = new FadeTransition(Duration.millis(300), view);
            ft.setFromValue(0);
            ft.setToValue(1);
            
            javafx.animation.ScaleTransition st = new javafx.animation.ScaleTransition(Duration.millis(300), view);
            st.setFromX(0.98);
            st.setFromY(0.98);
            st.setToX(1);
            st.setToY(1);
            
            new javafx.animation.ParallelTransition(ft, st).play();
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

    public void updateStats() {
        if (UserSession.getUserId() == null) {
            if (totalEntreprisesLabel != null) totalEntreprisesLabel.setText("0");
            if (totalTresoreriesLabel != null) totalTresoreriesLabel.setText("0");
            if (totalOperationsLabel != null) totalOperationsLabel.setText("0");
            if (totalRevenusLabel != null) totalRevenusLabel.setText("0 TND");
            if (totalDepensesLabel != null) totalDepensesLabel.setText("0 TND");
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
                    if (rs.next() && totalEntreprisesLabel != null) {
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
                    if (rs.next() && totalTresoreriesLabel != null) {
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

                        if (totalOperationsLabel != null) totalOperationsLabel.setText(String.valueOf(totalOps));
                        if (totalRevenusLabel != null) totalRevenusLabel.setText(String.format("%.2f TND", totalRevenus));
                        if (totalDepensesLabel != null) totalDepensesLabel.setText(String.format("%.2f TND", totalDepenses));
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
