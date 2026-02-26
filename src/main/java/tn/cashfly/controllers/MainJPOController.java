package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Button;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;
import tn.cashfly.entities.Utilisateur;
import tn.cashfly.utils.NavigationUtil;
import tn.cashfly.utils.SessionManager;

import java.io.IOException;
import java.util.Arrays;
import java.util.List;

public class MainJPOController {

    @FXML private Button addJPO;
    @FXML private Button logoutBtn;
    @FXML private AnchorPane contentArea;
    @FXML private VBox welcomeView;

    private List<Button> allButtons;
    private Utilisateur currentUser;

    @FXML
    public void initialize() {
        System.out.println("=== MainJPOController.initialize() START ===");

        System.out.println("contentArea = " + contentArea);
        System.out.println("welcomeView = " + welcomeView);
        System.out.println("addJPO = " + addJPO);
        System.out.println("logoutBtn = " + logoutBtn);

        currentUser = SessionManager.getCurrentUser();
        System.out.println("currentUser = " + (currentUser != null ? currentUser.getNomComplet() : "null"));

        if (!SessionManager.isLoggedIn() || !"proprietaire".equals(SessionManager.getCurrentUserRole())) {
            System.out.println("Unauthorized access - redirecting to login");
            navigateToLogin();
            return;
        }

        allButtons = Arrays.asList(addJPO);
        resetAllButtons();

        if (addJPO != null) addJPO.setOnAction(e -> showCalendarView());
        if (logoutBtn != null) logoutBtn.setOnAction(e -> handleLogout());

        System.out.println("Showing calendar view...");
        showCalendarView();

        System.out.println("=== MainJPOController.initialize() END ===");
    }

    public void showCalendarView() {
        System.out.println("=== showCalendarView() START ===");

        if (contentArea == null) {
            System.err.println("ERROR: contentArea is null! Check that fx:id=\"contentArea\" exists in MainJPO.fxml");
            return;
        }

        try {
            System.out.println("Loading CalendarViewProprietaire.fxml...");
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/CalendarViewProprietaire.fxml"));

            if (loader.getLocation() == null) {
                System.err.println("ERROR: CalendarViewProprietaire.fxml not found!");
                showWelcomeFallback();
                return;
            }

            Parent calendarView = loader.load();
            System.out.println("Calendar view loaded successfully");

            CalendarViewProprietaireController controller = loader.getController();
            if (controller != null) {
                controller.setMainController(this);
                System.out.println("Calendar controller set");
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
            if (addJPO != null) addJPO.getStyleClass().add("btn-active");

            System.out.println("=== showCalendarView() SUCCESS ===");

        } catch (IOException e) {
            System.err.println("ERROR loading calendar: " + e.getMessage());
            e.printStackTrace();
            showWelcomeFallback();
        }
    }

    private void showWelcomeFallback() {
        System.out.println("=== showWelcomeFallback() ===");

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
            System.out.println("Welcome view shown as fallback");
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
        try {
            Stage stage = (Stage) (logoutBtn != null ? logoutBtn.getScene().getWindow() :
                    (addJPO != null ? addJPO.getScene().getWindow() : null));
            if (stage != null) {
                NavigationUtil.navigateTo(stage, "RoleSelector.fxml");
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void resetAllButtons() {
        if (allButtons == null) return;
        for (Button btn : allButtons) {
            if (btn != null) {
                btn.getStyleClass().remove("btn-active");
                btn.getStyleClass().add("btn-icon");
            }
        }
    }

    private void navigateToLogin() {
        try {
            Stage stage = (Stage) (addJPO != null ? addJPO.getScene().getWindow() :
                    (logoutBtn != null ? logoutBtn.getScene().getWindow() : null));
            if (stage != null) {
                NavigationUtil.navigateTo(stage, "RoleSelector.fxml");
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}