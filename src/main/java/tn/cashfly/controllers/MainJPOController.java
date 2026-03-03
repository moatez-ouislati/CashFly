package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Button;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.VBox;
import tn.cashfly.entities.Utilisateur;
import tn.cashfly.tools.SceneManager;
import tn.cashfly.utils.SessionManager;

import java.io.IOException;
import java.util.Arrays;
import java.util.List;

public class MainJPOController {

    @FXML
    private Button addJPO;
    @FXML
    private Button logoutBtn;
    @FXML
    private AnchorPane contentArea;
    @FXML
    private VBox welcomeView;

    private List<Button> allButtons;
    private Utilisateur currentUser;

    @FXML
    public void initialize() {

        currentUser = SessionManager.getCurrentUser();

        if (!SessionManager.isLoggedIn() || !"proprietaire".equals(SessionManager.getCurrentUserRole())) {
            navigateToLogin();
            return;
        }

        allButtons = Arrays.asList(addJPO);
        resetAllButtons();

        if (addJPO != null)
            addJPO.setOnAction(e -> showCalendarView());
        if (logoutBtn != null)
            logoutBtn.setOnAction(e -> handleLogout());

        showCalendarView();

    }

    public void showCalendarView() {

        if (contentArea == null) {
            System.err.println("ERROR: contentArea is null! Check that fx:id=\"contentArea\" exists in MainJPO.fxml");
            return;
        }

        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/CalendarViewProprietaire.fxml"));

            if (loader.getLocation() == null) {
                System.err.println("ERROR: CalendarViewProprietaire.fxml not found!");
                showWelcomeFallback();
                return;
            }

            Parent calendarView = loader.load();

            CalendarViewProprietaireController controller = loader.getController();
            if (controller != null) {
                controller.setMainController(this);
            } else {
                System.err.println("WARNING: Calendar controller is null");
            }

            contentArea.getChildren().clear();
            contentArea.getChildren().add(calendarView);

            AnchorPane.setTopAnchor(calendarView, 0.0);
            AnchorPane.setBottomAnchor(calendarView, 0.0);
            AnchorPane.setLeftAnchor(calendarView, 0.0);
            AnchorPane.setRightAnchor(calendarView, 0.0);

            resetAllButtons();
            if (addJPO != null)
                addJPO.getStyleClass().add("btn-active");

        } catch (IOException e) {
            System.err.println("ERROR loading calendar: " + e.getMessage());
            showWelcomeFallback();
        }
    }

    private void showWelcomeFallback() {

        if (contentArea == null) {
            System.err.println("CRITICAL: contentArea is null, cannot show any view");
            return;
        }

        if (welcomeView != null) {
            contentArea.getChildren().clear();
            contentArea.getChildren().add(welcomeView);
            AnchorPane.setTopAnchor(welcomeView, 0.0);
            AnchorPane.setBottomAnchor(welcomeView, 0.0);
            AnchorPane.setLeftAnchor(welcomeView, 0.0);
            AnchorPane.setRightAnchor(welcomeView, 0.0);
        } else {
            System.err.println("WARNING: welcomeView is also null, showing empty content area");
        }

        resetAllButtons();
    }

    public void showWelcome() {
        showWelcomeFallback();
    }

    @FXML
    private void handleLogout() {
        SessionManager.clearSession();
        SceneManager.switchScene("tn/cashfly/authentification.fxml");
    }

    private void resetAllButtons() {
        if (allButtons == null)
            return;
        for (Button btn : allButtons) {
            if (btn != null) {
                btn.getStyleClass().remove("btn-active");
                btn.getStyleClass().add("btn-icon");
            }
        }
    }

    private void navigateToLogin() {
        SceneManager.switchScene("tn/cashfly/authentification.fxml");
    }
}
