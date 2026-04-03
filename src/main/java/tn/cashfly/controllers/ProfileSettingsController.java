package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.scene.control.*;
import tn.cashfly.entities.Utilisateur;
import tn.cashfly.services.UtilisateurCrud;
import tn.cashfly.tools.Session;
import tn.cashfly.session.UserSession;

public class ProfileSettingsController {

    @FXML private TextField tfCin;
    @FXML private TextField tfNom;
    @FXML private TextField tfPrenom;
    @FXML private TextField tfEmail;
    @FXML private TextField tfTel;

    @FXML private TextField tfReclamationCin;
    @FXML private TextField tfReclamationMessage;

    @FXML private PasswordField pfNewPassword;
    @FXML private PasswordField pfConfirmPassword;

    private final UtilisateurCrud crud = new UtilisateurCrud();
    private Utilisateur currentUser;

    @FXML
    public void initialize() {
        currentUser = Session.getCurrentUser();
        if (currentUser != null) {
            loadProfile();
        }
    }

    private void loadProfile() {
        tfCin.setText(String.valueOf(currentUser.getCin()));
        tfNom.setText(currentUser.getNom());
        tfPrenom.setText(currentUser.getPrenom());
        tfEmail.setText(currentUser.getEmail());
        tfTel.setText(currentUser.getTel());
    }

    @FXML
    private void saveProfile() {
        try {
            currentUser.setCin(Integer.parseInt(tfCin.getText().trim()));
            currentUser.setNom(tfNom.getText().trim());
            currentUser.setPrenom(tfPrenom.getText().trim());
            currentUser.setEmail(tfEmail.getText().trim());
            currentUser.setTel(tfTel.getText().trim());

            crud.modifier(currentUser);
            
            // Sync with UserSession
            UserSession.setUser(
                currentUser.getId(), 
                currentUser.getNom() + " " + currentUser.getPrenom(), 
                currentUser.getEmail(), 
                currentUser.getRoles(), 
                UserSession.isKycEnrolled()
            );

            // Update Dashboard sidebar if instance exists
            if (tn.cashfly.DashboardController.getInstance() != null) {
                tn.cashfly.DashboardController.getInstance().updateUserInfo();
            }

            alert(Alert.AlertType.INFORMATION, "Succès", "Profil mis à jour avec succès.");
        } catch (Exception e) {
            alert(Alert.AlertType.ERROR, "Erreur", "Impossible de mettre à jour le profil: " + e.getMessage());
        }
    }

    @FXML
    private void changePassword() {
        String newPass = pfNewPassword.getText();
        String confirmPass = pfConfirmPassword.getText();

        if (newPass.isEmpty() || confirmPass.isEmpty()) {
            alert(Alert.AlertType.WARNING, "Attention", "Veuillez remplir les deux champs de mot de passe.");
            return;
        }

        if (!newPass.equals(confirmPass)) {
            alert(Alert.AlertType.ERROR, "Erreur", "Les mots de passe ne correspondent pas.");
            return;
        }

        try {
            crud.changerMotDePasse(currentUser.getId(), newPass);
            pfNewPassword.clear();
            pfConfirmPassword.clear();
            alert(Alert.AlertType.INFORMATION, "Succès", "Mot de passe changé avec succès.");
        } catch (Exception e) {
            alert(Alert.AlertType.ERROR, "Erreur", "Impossible de changer le mot de passe: " + e.getMessage());
        }
    }

    @FXML
    private void sendCinReclamation() {
        String newCin = tfReclamationCin != null ? tfReclamationCin.getText().trim() : "";
        String message = tfReclamationMessage != null ? tfReclamationMessage.getText().trim() : "";

        if (newCin.isEmpty() || message.isEmpty()) {
            alert(Alert.AlertType.WARNING, "Attention", "Veuillez saisir le nouveau CIN souhaité et le motif de la réclamation.");
            return;
        }

        if (currentUser != null) {
            crud.ajouterReclamationCin(currentUser.getId(), newCin, message);
        }

        String summary = "Votre réclamation a été enregistrée et transmise à l'administrateur.\n\n"
                + "CIN actuel : " + tfCin.getText().trim() + "\n"
                + "Nouveau CIN demandé : " + newCin + "\n"
                + "Motif : " + message + "\n\n"
                + "Vous serez contacté après vérification.";

        tfReclamationCin.clear();
        tfReclamationMessage.clear();

        alert(Alert.AlertType.INFORMATION, "Réclamation envoyée", summary);
    }

    private void alert(Alert.AlertType type, String title, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(content);
        alert.showAndWait();
    }
}
