package controllers;

import entities.Utilisateur;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.stage.Stage;
import services.UtilisateurCrud;
import tools.SceneManager;
import tools.Session;
public class AuthentificationController {

    @FXML private TextField tfemail;
    @FXML private PasswordField tfmdp;
    @FXML private Button btn_auth, btn_annul;

    private final UtilisateurCrud utilisateurCrud = new UtilisateurCrud();

    @FXML
    private void initialize() {
        btn_auth.setOnAction(e -> login());
        //btn_annul.setOnAction(e -> annuler());
    }

    private void login() {
        String email = tfemail.getText();
        String password = tfmdp.getText();

        if (email.isEmpty() || password.isEmpty()) {
            alert("Veuillez remplir tous les champs");
            return;
        }

        Utilisateur u = utilisateurCrud.login(email, password);
        if (u == null) {
            alert("Email ou mot de passe incorrect");
            return;
        }
        Session.setCurrentUser(u);

        alert("Connexion réussie");

        if (u.isAdmin()) {
            SceneManager.switchScene("AdminDashboard.fxml");
        } else if (u.isProprietaire()) {
            SceneManager.switchScene("ProprietaireDashboard.fxml");
        } else {
            SceneManager.switchScene("InvestisseurDashboard.fxml");
        }

    }

    private void annuler() {
        Stage stage = (Stage) btn_annul.getScene().getWindow();
        stage.close();
    }

    private void alert(String msg) {
        new Alert(Alert.AlertType.INFORMATION, msg, ButtonType.OK).showAndWait();
    }
}
