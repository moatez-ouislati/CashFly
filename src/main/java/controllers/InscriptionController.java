package controllers;

import entities.Utilisateur;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.stage.Stage;
import services.UtilisateurCrud;

public class InscriptionController {

    @FXML private TextField tfcin, tfnum_tel, tfnom, tfprenom, tfemail;
    @FXML private PasswordField tfmdp;
    @FXML private TextField tfshowpassword;
    @FXML private CheckBox show;
    @FXML private RadioButton rbadmin, rbInvestisseur, rbProprietaire;
    @FXML private Button btn_inscri, btn_annul;
    @FXML private Hyperlink hyperlink;

    private final UtilisateurCrud utilisateurCrud = new UtilisateurCrud();

    @FXML
    private void initialize() {

        tfshowpassword.setVisible(false);
        show.setOnAction(e -> togglePassword());

        ToggleGroup group = new ToggleGroup();
        rbadmin.setToggleGroup(group);
        rbInvestisseur.setToggleGroup(group);
        rbProprietaire.setToggleGroup(group);

        rbInvestisseur.setSelected(true); // default
    }

    @FXML
    private void savePerson() {

        String cinStr = tfcin.getText().trim();
        String tel = tfnum_tel.getText().trim();
        String nom = tfnom.getText().trim();
        String prenom = tfprenom.getText().trim();
        String email = tfemail.getText().trim();
        String password = tfmdp.getText();

        // ===== VALIDATION =====
        if (cinStr.isEmpty() || tel.isEmpty() || nom.isEmpty()
                || prenom.isEmpty() || email.isEmpty() || password.isEmpty()) {
            alert("Veuillez remplir tous les champs");
            return;
        }

        if (!cinStr.matches("^[01][0-9]{7}$")) {
            alert("CIN invalide DOIT COMMANCER PAR 0,1");
            return;
        }

        if (!tel.matches("^[0-9]{8}$")) {
            alert("Numéro téléphone invalide il doit contenir 8 chiffres");
            return;
        }

        if (!email.matches("^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+$")) {
            alert("Email invalide");
            return;
        }

        if (password.length() < 6) {
            alert("Mot de passe minimum 6 caractères");
            return;
        }

        int cin = Integer.parseInt(cinStr);

        if (utilisateurCrud.utilisateurExiste(cin, email)) {
            alert("Utilisateur existe déjà");
            return;
        }

        // ===== ROLE =====
        String role;
        if (rbadmin.isSelected()) {
            role = "[\"ROLE_ADMIN\"]";
        } else if (rbProprietaire.isSelected()) {
            role = "[\"ROLE_PROPRIETAIRE\"]";
        } else {
            role = "[\"ROLE_INVESTISSEUR\"]";
        }

        // ===== INSERT =====
        Utilisateur u = new Utilisateur(cin, tel, nom, prenom, email, password, role);
        utilisateurCrud.ajouter(u);

        alert("Inscription réussie");

        // ===== REDIRECTION (المهم) =====
        try {
            Stage stage = (Stage) btn_inscri.getScene().getWindow();

            if (rbadmin.isSelected()) {
                stage.setScene(new Scene(
                        FXMLLoader.load(getClass().getResource("/AdminDashboard.fxml"))
                ));
            } else {
                stage.setScene(new Scene(
                        FXMLLoader.load(getClass().getResource("/authentification.fxml"))
                ));
            }

        }  catch (Exception e) {
            Alert a = new Alert(Alert.AlertType.ERROR);
            a.setTitle("FXML ERROR");
            a.setHeaderText("Erreur chargement AdminDashboard.fxml");
            a.setContentText(e.toString());
            a.showAndWait();
            e.printStackTrace();
        }

    }

    // ================= REDIRECT =================
    private void redirectAfterInscription(String role) {
        try {
            Stage stage = (Stage) btn_inscri.getScene().getWindow();

            if (role.contains("ROLE_ADMIN")) {
                stage.setScene(new Scene(
                        FXMLLoader.load(getClass().getResource("/AdminDashboard.fxml"))
                ));
            } else {
                stage.setScene(new Scene(
                        FXMLLoader.load(getClass().getResource("/authentification.fxml"))
                ));
            }

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @FXML
    private void annuler() throws Exception {
        Stage stage = (Stage) btn_inscri.getScene().getWindow();
        stage.setScene(new Scene(
                FXMLLoader.load(getClass().getResource("/authentification.fxml"))
        ));
    }

    @FXML
    private void redirectToAuthPage() throws Exception {
        annuler();
    }

    // ================= PASSWORD TOGGLE =================
    private void togglePassword() {
        if (show.isSelected()) {
            tfshowpassword.setText(tfmdp.getText());
            tfshowpassword.setVisible(true);
            tfmdp.setVisible(false);
        } else {
            tfmdp.setText(tfshowpassword.getText());
            tfmdp.setVisible(true);
            tfshowpassword.setVisible(false);
        }
    }

    // ================= ALERT =================
    private void alert(String msg) {
        new Alert(Alert.AlertType.INFORMATION, msg, ButtonType.OK).show();
    }
}
