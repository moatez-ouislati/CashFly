package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.scene.control.Label;
import javafx.scene.control.PasswordField;
import javafx.scene.control.TextField;
import javafx.stage.Stage;
import tn.cashfly.entities.Utilisateur;
import tn.cashfly.services.ServiceUtilisateur;
import tn.cashfly.utils.NavigationUtil;
import tn.cashfly.utils.SessionManager;

import java.io.IOException;
import java.sql.SQLException;

public class RoleSelectorController {

    @FXML private TextField emailField;
    @FXML private PasswordField passwordField;
    @FXML private Label errorLabel;

    private ServiceUtilisateur serviceUtilisateur;

    @FXML
    public void initialize() {
        serviceUtilisateur = new ServiceUtilisateur();
        errorLabel.setVisible(false);
    }

    @FXML
    private void handleLogin() {
        String email = emailField.getText().trim();
        String password = passwordField.getText();

        if (email.isEmpty() || password.isEmpty()) {
            showError("Veuillez remplir tous les champs");
            return;
        }

        try {
            Utilisateur user = serviceUtilisateur.authenticate(email, password);

            if (user != null) {
                SessionManager.setCurrentUser(user);
                redirectBasedOnRole(user.getRole());
            } else {
                showError("Email ou mot de passe incorrect");
            }

        } catch (SQLException e) {
            showError("Erreur de connexion à la base de données");
        }
    }

    @FXML
    private void openPME() {
        navigateTo("MainJPO.fxml", "proprietaire");
    }

    @FXML
    private void openInvestor() {
        navigateTo("InvestorMain.fxml", "investisseur");
    }

    @FXML
    private void openAdmin() {
        // Future implementation
    }

    private void redirectBasedOnRole(String role) {
        String target = switch (role) {
            case "investisseur" -> "InvestorMain.fxml";
            case "proprietaire" -> "MainJPO.fxml";
            default -> "RoleSelector.fxml";
        };

        try {
            Stage stage = (Stage) emailField.getScene().getWindow();
            NavigationUtil.navigateTo(stage, target);
        } catch (IOException e) {
        }
    }

    private void navigateTo(String fxml, String demoRole) {
        try {
            // For demo purposes, create a mock user if not logged in
            if (!SessionManager.isLoggedIn()) {
                Utilisateur demoUser = new Utilisateur();
                demoUser.setIdUtilisateur(1);
                demoUser.setNomComplet("Utilisateur " + demoRole);
                demoUser.setEmail("demo@cashfly.tn");
                demoUser.setRole(demoRole);
                SessionManager.setCurrentUser(demoUser);
            }

            Stage stage = (Stage) emailField.getScene().getWindow();
            NavigationUtil.navigateTo(stage, fxml);

        } catch (IOException e) {
        }
    }

    private void showError(String message) {
        errorLabel.setText(message);
        errorLabel.setVisible(true);
    }
}
