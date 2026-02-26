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

    private Parent currentContentView;
    private String currentViewTitle;
    private Parent previousContentView;
    private String previousViewTitle;

    @FXML
    public void initialize() {
        currentUser = SessionManager.getCurrentUser();

        if (currentUser == null || !"investisseur".equals(currentUser.getRole())) {
            handleLogout();
            return;
        }

        welcomeLabel.setText("Bienvenue, " + currentUser.getNomComplet() + " !");
        consulterBtn.setOnAction(e -> navigateToAllEvents());
        gererBtn.setOnAction(e -> navigateToMyEvents());
        logoutBtn.setOnAction(e -> handleLogout());

        // Initialize tracking
        this.currentContentView = welcomeView;
        this.currentViewTitle = "Accueil";
    }

    /**
     * NEW: Get current content view for tracking
     */
    public Parent getCurrentContentView() {
        return currentContentView;
    }

    /**
     * NEW: Get current view title for tracking
     */
    public String getCurrentViewTitle() {
        return currentViewTitle;
    }

    /**
     * NEW: Navigate to a specific view and track history
     */
    private void navigateToView(Parent newView, String title, Button activeButton) {
        // Save current as previous before switching
        this.previousContentView = this.currentContentView;
        this.previousViewTitle = this.currentViewTitle;

        // Update current
        this.currentContentView = newView;
        this.currentViewTitle = title;

        // Show in content area
        contentArea.getChildren().clear();
        contentArea.getChildren().add(newView);
        AnchorPane.setTopAnchor(newView, 0.0);
        AnchorPane.setBottomAnchor(newView, 0.0);
        AnchorPane.setLeftAnchor(newView, 0.0);
        AnchorPane.setRightAnchor(newView, 0.0);

        // Update button styles
        resetButtonStyles();
        if (activeButton != null) {
            activeButton.getStyleClass().add("btn-active");
        }
    }

    /**
     * NEW: Navigate back to previous view
     */
    public void showPreviousView(Parent previousView, String title) {
        // Swap current and previous
        Parent tempView = this.currentContentView;
        String tempTitle = this.currentViewTitle;

        this.currentContentView = previousView;
        this.currentViewTitle = title;
        this.previousContentView = tempView;
        this.previousViewTitle = tempTitle;

        // Show the previous view
        contentArea.getChildren().clear();
        contentArea.getChildren().add(previousView);
        AnchorPane.setTopAnchor(previousView, 0.0);
        AnchorPane.setBottomAnchor(previousView, 0.0);
        AnchorPane.setLeftAnchor(previousView, 0.0);
        AnchorPane.setRightAnchor(previousView, 0.0);

        // Update button styles based on title
        resetButtonStyles();
        if (title != null) {
            if (title.contains("Tous") || title.contains("événements")) {
                consulterBtn.getStyleClass().add("btn-active");
            } else if (title.contains("Mes") || title.contains("inscriptions")) {
                gererBtn.getStyleClass().add("btn-active");
            }
        }
    }

    @FXML
    public void showWelcome() {
        navigateToView(welcomeView, "Accueil", null);
    }

    @FXML
    public void navigateToAllEvents() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/InvestorListAllJPO.fxml"));
            Parent allEventsView = loader.load();
            InvestorListAllJPOController controller = loader.getController();
            controller.setMainController(this);

            navigateToView(allEventsView, "Tous les événements", consulterBtn);
        } catch (IOException e) {
            e.printStackTrace();
            showError("Impossible de charger la page des événements");
        }
    }

    @FXML
    public void navigateToMyEvents() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/InvestorListMyJPO.fxml"));
            Parent myEventsView = loader.load();
            InvestorListMyJPOController controller = loader.getController();
            controller.setMainController(this);

            navigateToView(myEventsView, "Mes inscriptions", gererBtn);
        } catch (IOException e) {
            e.printStackTrace();
            showError("Impossible de charger la page de vos inscriptions");
        }
    }

    /**
     * NEW: Show event detail with proper back navigation tracking
     */
    public void showEventDetail(Parent detailView) {
        contentArea.getChildren().clear();
        contentArea.getChildren().add(detailView);

        // Anchor the loaded view
        AnchorPane.setTopAnchor(detailView, 0.0);
        AnchorPane.setBottomAnchor(detailView, 0.0);
        AnchorPane.setLeftAnchor(detailView, 0.0);
        AnchorPane.setRightAnchor(detailView, 0.0);

        resetButtonStyles();
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