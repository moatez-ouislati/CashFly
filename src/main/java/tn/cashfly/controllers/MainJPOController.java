package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.stage.Stage;
import tn.cashfly.utils.NavigationUtil;
import tn.cashfly.utils.SessionManager;

import java.io.IOException;
import java.util.Arrays;
import java.util.List;

public class MainJPOController {

    @FXML private Button addJPO;
    @FXML private Button updateJPO;
    @FXML private Button deleteJPO;
    @FXML private Button listJPO;
    @FXML private Button logoutBtn;

    private List<Button> allButtons;

    @FXML
    public void initialize() {
        // Security check
        if (!SessionManager.isLoggedIn() || !"proprietaire".equals(SessionManager.getCurrentUserRole())) {
            navigateToLogin();
            return;
        }

        allButtons = Arrays.asList(addJPO, updateJPO, deleteJPO, listJPO);
        resetAllButtons();

        // Set up button actions
        addJPO.setOnAction(e -> navigateTo("AddJPO.fxml", addJPO));
        updateJPO.setOnAction(e -> navigateTo("UpdateJPO.fxml", updateJPO));
        deleteJPO.setOnAction(e -> navigateTo("DeleteJPO.fxml", deleteJPO));
        listJPO.setOnAction(e -> navigateTo("ListJPO.fxml", listJPO));
        logoutBtn.setOnAction(e -> handleLogout());
    }

    @FXML
    private void handleLogout() {
        SessionManager.clearSession();
        try {
            Stage stage = (Stage) logoutBtn.getScene().getWindow();
            NavigationUtil.navigateTo(stage, "RoleSelector.fxml");
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void resetAllButtons() {
        for (Button btn : allButtons) {
            btn.getStyleClass().remove("btn-active");
            btn.getStyleClass().add("btn-icon");
        }
    }

    private void navigateTo(String fxml, Button clickedButton) {
        resetAllButtons();
        clickedButton.getStyleClass().add("btn-active");
        try {
            Stage stage = (Stage) clickedButton.getScene().getWindow();
            NavigationUtil.navigateTo(stage, fxml);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void navigateToLogin() {
        try {
            Stage stage = (Stage) addJPO.getScene().getWindow();
            NavigationUtil.navigateTo(stage, "RoleSelector.fxml");
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}