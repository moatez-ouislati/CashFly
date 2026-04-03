package tn.cashfly.controllers;

import tn.cashfly.entities.Utilisateur;
import javafx.animation.FadeTransition;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.HBox;
import javafx.scene.layout.StackPane;
import javafx.scene.layout.VBox;
import javafx.scene.shape.Circle;
import javafx.stage.Stage;
import javafx.util.Duration;
import tn.cashfly.services.UtilisateurCrud;
import tn.cashfly.tools.Session;

import java.io.IOException;
import java.net.URL;
import java.util.List;
import java.util.ResourceBundle;

public class InvestisseurDashboard {

    private static InvestisseurDashboard instance;

    public static InvestisseurDashboard getInstance() {
        return instance;
    }

    @FXML private StackPane contentRoot;
    @FXML private StackPane mainStackPane;
    @FXML private Circle notifDot;
    private NotificationsStage notificationsStage;

    @FXML private Label pageTitle;
    @FXML private Label pageSubtitle;
    @FXML private Label userNameLabel;
    @FXML private Label userEmailLabel;

    @FXML private Button btnHome;
    @FXML private Button btnInvestments;
    @FXML private Button btnRendements;
    @FXML private Button btnEvents;
    @FXML private Button btnMap;
    @FXML private Button btnProprietaires;
    @FXML private Button btnProfile;

    private final UtilisateurCrud crud = new UtilisateurCrud();
    private Utilisateur currentUser;

    @FXML
    public void initialize() {
        instance = this;
        currentUser = Session.getCurrentUser();
        updateUserInfo();
        
        // Vue par défaut
        showHome();

        // Initialize notification stage
        notificationsStage = new NotificationsStage();
        mainStackPane.getChildren().add(notificationsStage);
    }

    @FXML
    public void toggleNotifications() {
        if (notificationsStage != null) {
            notificationsStage.toggle();
            notifDot.setVisible(false);
        }
    }

    public void addNotification(String text, NotificationType type) {
        if (notificationsStage != null) {
            notificationsStage.addNote(text, type);
            if (!notifDot.isVisible()) {
                notifDot.setVisible(true);
            }
        }
    }

    private void updateUserInfo() {
        if (currentUser != null) {
            userNameLabel.setText(currentUser.getNom() + " " + currentUser.getPrenom());
            userEmailLabel.setText(currentUser.getEmail());
        }
    }

    @FXML
    public void showHome() {
        pageTitle.setText("Tableau de Bord Investisseur");
        pageSubtitle.setText("Bienvenue sur votre espace personnel.");
        updateActiveButton(btnHome);
        loadFXML("/fxml/investor_home.fxml");
    }

    @FXML
    public void showInvestments() {
        pageTitle.setText("Mes Investissements");
        pageSubtitle.setText("Consultez et gérez vos prises de participation.");
        updateActiveButton(btnInvestments);
        loadFXML("/fxml/investments.fxml");
    }

    @FXML
    public void showRendements() {
        pageTitle.setText("Analyse des Rendements");
        pageSubtitle.setText("Suivez l'évolution de vos gains et projections.");
        updateActiveButton(btnRendements);
        loadFXML("/fxml/rendements.fxml");
    }

    private void loadFXML(String fxmlPath) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource(fxmlPath));
            Node view = loader.load();
            applyTransition(view);
            contentRoot.getChildren().setAll(view);
        } catch (IOException e) {
            e.printStackTrace();
            alert("Erreur lors du chargement de la vue : " + fxmlPath);
        }
    }

    @FXML
    public void showEvents() {
        pageTitle.setText("Événements JPO");
        pageSubtitle.setText("Découvrez les journées portes ouvertes et inscrivez-vous aux événements.");
        updateActiveButton(btnEvents);

        if (currentUser == null) {
            alert("Session expirée. Veuillez vous reconnecter.");
            return;
        }

        tn.cashfly.jpo.entities.Utilisateur jpoUser = new tn.cashfly.jpo.entities.Utilisateur();
        jpoUser.setIdUtilisateur(currentUser.getId());
        String fullName = (currentUser.getNom() != null ? currentUser.getNom() : "") +
                " " + (currentUser.getPrenom() != null ? currentUser.getPrenom() : "");
        jpoUser.setNomComplet(fullName.trim());
        jpoUser.setEmail(currentUser.getEmail());
        jpoUser.setRole("investisseur");
        tn.cashfly.jpo.utils.SessionManager.setCurrentUser(jpoUser);

        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/InvestorListAllJPO.fxml"));
            Node view = loader.load();
            applyTransition(view);
            contentRoot.getChildren().setAll(view);
        } catch (IOException e) {
            e.printStackTrace();
            alert("Erreur lors du chargement des événements JPO.");
        }
    }

    @FXML
    public void showMap() {
        pageTitle.setText("Carte Interactive");
        pageSubtitle.setText("Localisez les opportunités d'investissement.");
        updateActiveButton(btnMap);
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/map.fxml"));
            Node view = loader.load();
            
            // Custom smooth transition for map
            view.setOpacity(0);
            contentRoot.getChildren().setAll(view);
            
            FadeTransition ft = new FadeTransition(Duration.millis(600), view);
            ft.setFromValue(0);
            ft.setToValue(1);
            ft.play();
            
            // Map visibility fix: Wait a bit more for layout to be stable
            Platform.runLater(() -> {
                MapController mapController = loader.getController();
                if (mapController != null) {
                    // Slight delay to avoid the layout "snap"
                    new Thread(() -> {
                        try { Thread.sleep(100); } catch (InterruptedException e) {}
                        Platform.runLater(() -> mapController.forceRedraw());
                    }).start();
                }
            });
        } catch (IOException e) {
            e.printStackTrace();
            alert("Erreur lors du chargement de la carte.");
        }
    }

    @FXML
    public void showProprietaires() {
        pageTitle.setText("Partenaires & Propriétaires");
        pageSubtitle.setText("Consultez la liste des entrepreneurs Cashfly.");
        updateActiveButton(btnProprietaires);
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/ListProprietaire.fxml"));
            Node view = loader.load();
            ListProprietaireController controller = loader.getController();
            controller.setRoleToDisplay("proprietaire"); // Show Owners for Investor
            
            applyTransition(view);
            contentRoot.getChildren().setAll(view);
        } catch (IOException e) {
            e.printStackTrace();
            alert("Erreur lors du chargement des propriétaires.");
        }
    }

    @FXML
    public void showProfile() {
        pageTitle.setText("Mon Profil");
        pageSubtitle.setText("Gérez vos informations personnelles.");
        updateActiveButton(btnProfile);
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/profile_settings.fxml"));
            Node view = loader.load();
            applyTransition(view);
            contentRoot.getChildren().setAll(view);
        } catch (IOException e) {
            e.printStackTrace();
            alert("Erreur lors du chargement du profil.");
        }
    }

    @FXML
    public void handleLogout(javafx.event.ActionEvent event) {
        try {
            Session.clear();
            Parent root = FXMLLoader.load(getClass().getResource("/authentification.fxml"));
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
        if (btnInvestments != null) btnInvestments.getStyleClass().remove("active-nav-btn");
        if (btnRendements != null) btnRendements.getStyleClass().remove("active-nav-btn");
        if (btnEvents != null) btnEvents.getStyleClass().remove("active-nav-btn");
        if (btnMap != null) btnMap.getStyleClass().remove("active-nav-btn");
        if (btnProprietaires != null) btnProprietaires.getStyleClass().remove("active-nav-btn");
        if (btnProfile != null) btnProfile.getStyleClass().remove("active-nav-btn");

        activeBtn.getStyleClass().add("active-nav-btn");
    }

    private void loadPlaceholder(String message) {
        VBox placeholder = new VBox(20);
        placeholder.setAlignment(javafx.geometry.Pos.CENTER);
        Label label = new Label(message);
        label.setStyle("-fx-font-size: 18px; -fx-text-fill: #64748b; -fx-font-weight: bold;");
        placeholder.getChildren().add(label);
        
        applyTransition(placeholder);
        contentRoot.getChildren().setAll(placeholder);
    }

    private void applyTransition(Node view) {
        view.setOpacity(0);
        view.setScaleX(0.98);
        view.setScaleY(0.98);
        
        FadeTransition ft = new FadeTransition(Duration.millis(300), view);
        ft.setFromValue(0);
        ft.setToValue(1);
        
        javafx.animation.ScaleTransition st = new javafx.animation.ScaleTransition(Duration.millis(300), view);
        st.setFromX(0.98);
        st.setFromY(0.98);
        st.setToX(1);
        st.setToY(1);
        
        new javafx.animation.ParallelTransition(ft, st).play();
    }

    private void alert(String msg) {
        new Alert(Alert.AlertType.INFORMATION, msg, ButtonType.OK).showAndWait();
    }
}
