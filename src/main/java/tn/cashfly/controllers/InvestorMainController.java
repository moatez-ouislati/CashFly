package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;
import tn.cashfly.entities.Utilisateur;
import tn.cashfly.utils.NavigationUtil;
import tn.cashfly.utils.SessionManager;

import java.io.IOException;

public class InvestorMainController {

    @FXML private Button consulterBtn;
    @FXML private Button gererBtn;
    @FXML private Button logoutBtn;
    @FXML private AnchorPane contentArea;
    @FXML private VBox welcomeView;
    @FXML private Label welcomeLabel;

    private Utilisateur currentUser;

    @FXML
    public void initialize() {
        currentUser = SessionManager.getCurrentUser();

        if (currentUser == null || !"investisseur".equals(currentUser.getRole())) {
            handleLogout();
            return;
        }

        // Set welcome message
        welcomeLabel.setText("Bienvenue, " + currentUser.getNomComplet() + " !");

        // Setup button actions
        consulterBtn.setOnAction(e -> navigateToAllEvents());
        gererBtn.setOnAction(e -> navigateToMyEvents());
        logoutBtn.setOnAction(e -> handleLogout());
    }

    @FXML
    private void showWelcome() {
        contentArea.getChildren().clear();
        contentArea.getChildren().add(welcomeView);
        AnchorPane.setTopAnchor(welcomeView, 0.0);
        AnchorPane.setBottomAnchor(welcomeView, 0.0);
        AnchorPane.setLeftAnchor(welcomeView, 0.0);
        AnchorPane.setRightAnchor(welcomeView, 0.0);

        resetButtonStyles();
    }

    /**
     * Navigate to All Events page - Called from FXML onMouseClicked
     */
    @FXML
    public void navigateToAllEvents() {
        try {
            Parent allEventsView = FXMLLoader.load(getClass().getResource("/tn/cashfly/InvestorListAllJPO.fxml"));
            contentArea.getChildren().clear();
            contentArea.getChildren().add(allEventsView);

            // Anchor the loaded view
            AnchorPane.setTopAnchor(allEventsView, 0.0);
            AnchorPane.setBottomAnchor(allEventsView, 0.0);
            AnchorPane.setLeftAnchor(allEventsView, 0.0);
            AnchorPane.setRightAnchor(allEventsView, 0.0);

            resetButtonStyles();
            consulterBtn.getStyleClass().add("btn-active");
        } catch (IOException e) {
            e.printStackTrace();
            showError("Impossible de charger la page des événements");
        }
    }

    /**
     * Navigate to My Events page - Called from FXML onMouseClicked
     */
    @FXML
    public void navigateToMyEvents() {
        try {
            Parent myEventsView = FXMLLoader.load(getClass().getResource("/tn/cashfly/InvestorListMyJPO.fxml"));
            contentArea.getChildren().clear();
            contentArea.getChildren().add(myEventsView);

            // Anchor the loaded view
            AnchorPane.setTopAnchor(myEventsView, 0.0);
            AnchorPane.setBottomAnchor(myEventsView, 0.0);
            AnchorPane.setLeftAnchor(myEventsView, 0.0);
            AnchorPane.setRightAnchor(myEventsView, 0.0);

            resetButtonStyles();
            gererBtn.getStyleClass().add("btn-active");
        } catch (IOException e) {
            e.printStackTrace();
            showError("Impossible de charger la page de vos inscriptions");
        }
    }

    private void resetButtonStyles() {
        consulterBtn.getStyleClass().remove("btn-active");
        gererBtn.getStyleClass().remove("btn-active");
    }

    @FXML
    private void handleLogout() {
        SessionManager.clearSession();
        try {
            NavigationUtil.navigateTo((Stage) logoutBtn.getScene().getWindow(), "RoleSelector.fxml");
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void showError(String message) {
        System.err.println("Error: " + message);
    }
}