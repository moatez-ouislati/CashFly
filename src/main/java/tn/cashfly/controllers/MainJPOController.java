package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.stage.Stage;

import java.io.IOException;
import java.util.Arrays;
import java.util.List;

public class MainJPOController {

    @FXML
    private Button addJPO;
    @FXML
    private Button updateJPO;
    @FXML
    private Button deleteJPO;
    @FXML
    private Button listJPO;

    private List<Button> allButtons;

    @FXML
    public void initialize() {
        allButtons = Arrays.asList(addJPO, updateJPO, deleteJPO, listJPO);

        addJPO.setOnAction(e -> navigateTo("AddJPO.fxml", addJPO));
        updateJPO.setOnAction(e -> navigateTo("UpdateJPO.fxml", updateJPO));
        deleteJPO.setOnAction(e -> navigateTo("DeleteJPO.fxml", deleteJPO));
        listJPO.setOnAction(e -> navigateTo("ListJPO.fxml", listJPO));
    }

    private void navigateTo(String fxml, Button clickedButton) {
        // Highlight clicked button
        for (Button btn : allButtons) {
            btn.getStyleClass().remove("btn-active");
        }
        clickedButton.getStyleClass().add("btn-active");

        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/" + fxml));
            Parent root = loader.load();
            Stage stage = (Stage) clickedButton.getScene().getWindow();
            Scene scene = new Scene(root);
            scene.getStylesheets().add(getClass().getResource("/tn/cashfly/Styles/custom.css").toExternalForm());
            stage.setScene(scene);
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
            showError("Erreur de navigation", "Impossible de charger: " + fxml);
        }
    }

    private void showError(String title, String message) {
        javafx.scene.control.Alert alert = new javafx.scene.control.Alert(javafx.scene.control.Alert.AlertType.ERROR);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }
}