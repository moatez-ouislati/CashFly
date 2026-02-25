package tn.cashfly;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Label;
import javafx.scene.control.PasswordField;
import javafx.scene.control.TextField;
import javafx.stage.Stage;
import tn.cashfly.services.KycService;
import tn.cashfly.session.UserSession;
import tn.cashfly.utils.MyDataBase;

import java.io.IOException;
import java.sql.*;

public class LoginController {

    @FXML
    private TextField usernameField;

    @FXML
    private PasswordField passwordField;

    @FXML
    private Label errorLabel;

    private final KycService kycService = new KycService();

    @FXML
    private void onLogin(ActionEvent event) {
        String username = usernameField.getText() != null ? usernameField.getText().trim() : "";
        String password = passwordField.getText() != null ? passwordField.getText() : "";

        errorLabel.setVisible(false);

        if (username.isEmpty() || password.isEmpty()) {
            showError("Veuillez saisir un nom d'utilisateur et un mot de passe.");
            return;
        }

        if (authenticate(username, password)) {
            if (!UserSession.isKycEnrolled()) {
                openKYCEnrollment(event);
            } else {
                openDashboard(event);
            }
        }
    }

    private void openKYCEnrollment(ActionEvent event) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/kyc_modal.fxml"));
            Parent root = loader.load();
            
            // Get stage
            Stage stage = (Stage) ((javafx.scene.Node) event.getSource()).getScene().getWindow();
            stage.setTitle("Enrôlement KYC Obligatoire - Cashfly");
            stage.setScene(new Scene(root));
            
            // On successful KYC enrollment, proceed to dashboard (KYCController now handles persistence)
            stage.setOnHidden(e -> {
                if (KYCController.isVerified()) {
                    openDashboard(event);
                } else {
                    // User closed without verifying
                    showError("Vous devez compléter le KYC pour accéder à votre compte.");
                }
            });
            
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
            showError("Erreur lors de l'ouverture du KYC : " + e.getMessage());
        }
    }

    /**
     * Authentifie un utilisateur en base de données.
     * ATTENTION : attend une table `utilisateurs` avec les colonnes :
     * id_utilisateur, nom_complet, email, mot_de_passe, role, date_creation.
     */
    private boolean authenticate(String username, String password) {
        try {
            Connection cnx = MyDataBase.getInstance().getCnx();
            if (cnx == null || cnx.isClosed()) {
                showError("Connexion à la base de données échouée (connexion nulle ou fermée).");
                return false;
            }

            String sql = "SELECT id_utilisateur, nom_complet, email, role " +
                    "FROM utilisateurs WHERE email = ? AND mot_de_passe = ? LIMIT 1";
            try (PreparedStatement ps = cnx.prepareStatement(sql)) {
                ps.setString(1, username);
                ps.setString(2, password);

                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next()) {
                        int id = rs.getInt("id_utilisateur");
                        String fullName = rs.getString("nom_complet");
                        String emailDb = rs.getString("email");
                        String role = rs.getString("role");

                        // Check KYC status from user_kyc table instead of column
                        boolean kycEnrolled = kycService.getKycByUserId(id) != null;

                        UserSession.setUser(id, fullName, emailDb, role, kycEnrolled);
                        return true;
                    } else {
                        showError("Email ou mot de passe incorrect.");
                        return false;
                    }
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
            showError("Erreur lors de l'authentification : " + e.getMessage());
            return false;
        }
    }

    private void openDashboard(ActionEvent event) {
        try {
            String role = UserSession.getRole();
            String fxmlPath = "/dashboard.fxml"; // Default for proprietaire

            if ("administrateur".equalsIgnoreCase(role)) {
                fxmlPath = "/admin_dashboard.fxml";
            } else if ("investisseur".equalsIgnoreCase(role)) {
                fxmlPath = "/investor_dashboard.fxml";
            }

            Parent root = FXMLLoader.load(getClass().getResource(fxmlPath));
            Stage stage = (Stage) ((javafx.scene.Node) event.getSource()).getScene().getWindow();
            stage.setTitle("Cashfly - Dashboard (" + role + ")");
            stage.setScene(new Scene(root, 1280, 800));
            stage.setMinWidth(1024);
            stage.setMinHeight(600);
            stage.centerOnScreen();
        } catch (Exception e) {
            e.printStackTrace();
            showError("Impossible de charger le tableau de bord : " + e.getMessage());
        }
    }

    private void showError(String message) {
        errorLabel.setText(message);
        errorLabel.setVisible(true);
    }
}

