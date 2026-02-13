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
import tn.cashfly.session.UserSession;
import tn.cashfly.utils.MyDataBase;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class LoginController {

    @FXML
    private TextField usernameField;

    @FXML
    private PasswordField passwordField;

    @FXML
    private Label errorLabel;

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
            openDashboard(event);
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

                        UserSession.setUser(id, fullName, emailDb, role);
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
            Parent root = FXMLLoader.load(getClass().getResource("/dashboard.fxml"));
            Stage stage = (Stage) ((javafx.scene.Node) event.getSource()).getScene().getWindow();
            stage.setTitle("Cashfly - Dashboard");
            stage.setScene(new Scene(root, 1024, 600));
            stage.setMinWidth(1024);
            stage.setMinHeight(600);
        } catch (IOException e) {
            e.printStackTrace();
            showError("Impossible de charger le tableau de bord.");
        }
    }

    private void showError(String message) {
        errorLabel.setText(message);
        errorLabel.setVisible(true);
    }
}

