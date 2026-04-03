package tn.cashfly.jpo.controllers;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Button;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;
import tn.cashfly.jpo.entities.Utilisateur;
import tn.cashfly.jpo.utils.NavigationUtil;
import tn.cashfly.jpo.utils.SessionManager;

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
        currentUser = SessionManager.getCurrentUser();
        if (!SessionManager.isLoggedIn() || !"proprietaire".equals(SessionManager.getCurrentUserRole())) {
            navigateToLogin();
            return;
        }
        allButtons = Arrays.asList(addJPO);
        resetAllButtons();
        if (addJPO != null) addJPO.setOnAction(e -> showCalendarView());
        if (logoutBtn != null) logoutBtn.setOnAction(e -> handleLogout());
        showCalendarView();
    }

    public void showCalendarView() {
        if (contentArea == null) {
            return;
        }
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/CalendarViewProprietaire.fxml"));
            if (loader.getLocation() == null) {
                showWelcomeFallback();
                return;
            }
            Parent calendarView = loader.load();
            CalendarViewProprietaireController controller = loader.getController();
            if (controller != null) {
                controller.setMainController(this);
            }
            contentArea.getChildren().clear();
            contentArea.getChildren().add(calendarView);
            AnchorPane.setTopAnchor(calendarView, 0.0);
            AnchorPane.setBottomAnchor(calendarView, 0.0);
            AnchorPane.setLeftAnchor(calendarView, 0.0);
            AnchorPane.setRightAnchor(calendarView, 0.0);
            resetAllButtons();
            if (addJPO != null) addJPO.getStyleClass().add("btn-active");
        } catch (IOException e) {
            showWelcomeFallback();
        }
    }

    private void showWelcomeFallback() {
        if (contentArea == null) {
            return;
        }
        if (welcomeView != null) {
            contentArea.getChildren().clear();
            contentArea.getChildren().add(welcomeView);
            AnchorPane.setTopAnchor(welcomeView, 0.0);
            AnchorPane.setBottomAnchor(welcomeView, 0.0);
            AnchorPane.setLeftAnchor(welcomeView, 0.0);
            AnchorPane.setRightAnchor(welcomeView, 0.0);
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
        }
    }
}
