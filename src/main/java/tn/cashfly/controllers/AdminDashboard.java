package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.ToggleButton;
import javafx.scene.layout.StackPane;
import javafx.stage.Stage;
import javafx.animation.FadeTransition;
import javafx.util.Duration;
import tn.cashfly.entities.Utilisateur;
import tn.cashfly.tools.Session;
import tn.cashfly.services.UtilisateurCrud;

import java.io.IOException;

public class AdminDashboard {

    @FXML private StackPane contentRoot;
    @FXML private Button btnUsers, btnStatistiques, btnProfile, btnLogout;
    @FXML private Label pageTitle, pageSubtitle, userNameLabel, userEmailLabel;
    @FXML private Label totalUsersLabel, totalInvestLabel, totalPropLabel;
    @FXML private ToggleButton darkToggle;

    private final UtilisateurCrud crud = new UtilisateurCrud();
    private boolean darkMode = false;

    @FXML
    public void initialize() {
        // Guard session
        Utilisateur admin = Session.getCurrentUser();
        if (admin == null || !admin.isAdmin()) {
            handleLogout();
            return;
        }

        userNameLabel.setText(admin.getNom() + " " + admin.getPrenom());
        userEmailLabel.setText(admin.getEmail());

        updateStats();
        
        darkToggle.setOnAction(e -> toggleTheme());
        
        showUsers(); // Default view
    }

    private void toggleTheme() {
        Scene scene = darkToggle.getScene();
        if (scene == null) return;

        scene.getStylesheets().clear();
        scene.getStylesheets().add(getClass().getResource("/style.css").toExternalForm());

        if (!darkMode) {
            scene.getStylesheets().add(getClass().getResource("/dark.css").toExternalForm());
            darkToggle.setText("Clair");
        } else {
            scene.getStylesheets().add(getClass().getResource("/light.css").toExternalForm());
            darkToggle.setText("Sombre");
        }
        darkMode = !darkMode;
    }

    @FXML
    public void showUsers() {
        loadView("/admin_users_view.fxml");
        updateActiveButton(btnUsers);
        pageTitle.setText("Gestion des Utilisateurs");
        pageSubtitle.setText("Ajoutez, modifiez ou supprimez des comptes utilisateurs.");
        updateStats();
    }

    @FXML
    public void showStats() {
        loadView("/admin_stats_view.fxml");
        updateActiveButton(btnStatistiques);
        pageTitle.setText("Statistiques Système");
        pageSubtitle.setText("Analyse de la répartition des utilisateurs par rôle.");
        updateStats();
    }

    @FXML
    public void showProfile() {
        loadView("/profile_settings.fxml");
        updateActiveButton(btnProfile);
        pageTitle.setText("Mon Profil");
        pageSubtitle.setText("Gérez vos informations personnelles d'administrateur.");
    }

    @FXML
    public void handleLogout() {
        Session.clear();
        try {
            Parent root = FXMLLoader.load(getClass().getResource("/authentification.fxml"));
            Stage stage = (Stage) btnLogout.getScene().getWindow();
            stage.setScene(new Scene(root));
            stage.setTitle("Cashfly - Connexion");
            stage.centerOnScreen();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void updateActiveButton(Button activeBtn) {
        btnUsers.getStyleClass().remove("active-nav-btn");
        btnStatistiques.getStyleClass().remove("active-nav-btn");
        btnProfile.getStyleClass().remove("active-nav-btn");
        if (activeBtn != null) activeBtn.getStyleClass().add("active-nav-btn");
    }

    private void loadView(String fxmlPath) {
        try {
            Node view = FXMLLoader.load(getClass().getResource(fxmlPath));
            contentRoot.getChildren().setAll(view);
            
            FadeTransition ft = new FadeTransition(Duration.millis(300), view);
            ft.setFromValue(0);
            ft.setToValue(1);
            ft.play();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void updateStats() {
        var users = crud.afficher();
        totalUsersLabel.setText(String.valueOf(users.size()));
        totalInvestLabel.setText(String.valueOf(users.stream().filter(Utilisateur::isInvestisseur).count()));
        totalPropLabel.setText(String.valueOf(users.stream().filter(Utilisateur::isProprietaire).count()));
    }
}
