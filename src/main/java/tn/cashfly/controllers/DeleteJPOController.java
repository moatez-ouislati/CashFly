package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;
import tn.cashfly.entities.JPO;
import tn.cashfly.services.ServiceJPO;

import java.io.IOException;
import java.sql.SQLException;
import java.util.List;

public class DeleteJPOController {

    @FXML
    private Button backButton;
    @FXML
    private TextField searchField;
    @FXML
    private VBox resultCard;
    @FXML
    private Label idLabel;
    @FXML
    private Label titreLabel;
    @FXML
    private Label dateLabel;
    @FXML
    private Label lieuLabel;
    @FXML
    private Label confirmLabel;

    private ServiceJPO serviceJPO;
    private JPO currentJPO;

    @FXML
    public void initialize() {
        serviceJPO = new ServiceJPO();
        resultCard.setVisible(false);
    }

    @FXML
    private void handleSearch() {
        String search = searchField.getText().trim();
        if (search.isEmpty()) {
            showAlert(Alert.AlertType.WARNING, "Recherche vide", "Veuillez entrer un ID ou titre", null);
            return;
        }

        try {
            List<JPO> allJPOs = serviceJPO.getAll();
            JPO found = null;

            // Try to find by ID first
            try {
                int id = Integer.parseInt(search);
                found = allJPOs.stream().filter(j -> j.getId_evenement() == id).findFirst().orElse(null);
            } catch (NumberFormatException e) {
                // Search by title
                found = allJPOs.stream()
                        .filter(j -> j.getTitre().toLowerCase().contains(search.toLowerCase()))
                        .findFirst().orElse(null);
            }

            if (found != null) {
                displayJPO(found);
            } else {
                showAlert(Alert.AlertType.WARNING, "Non trouvé", "Aucune JPO trouvée",
                        "Aucun événement ne correspond à votre recherche.");
                resultCard.setVisible(false);
            }

        } catch (SQLException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur de base de données", e.getMessage());
        }
    }

    private void displayJPO(JPO jpo) {
        currentJPO = jpo;
        idLabel.setText(String.valueOf(jpo.getId_evenement()));
        titreLabel.setText(jpo.getTitre());
        dateLabel.setText(jpo.getDate_evenement().toString());
        lieuLabel.setText(jpo.getLieu());

        resultCard.setVisible(true);
    }

    @FXML
    private void handleDelete() {
        if (currentJPO == null) return;

        try {
            serviceJPO.delete(currentJPO);

            showAlert(Alert.AlertType.INFORMATION, "Succès", "JPO supprimée",
                    "L'événement '" + currentJPO.getTitre() + "' a été supprimé définitivement.");

            resultCard.setVisible(false);
            searchField.clear();
            currentJPO = null;

        } catch (SQLException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur de suppression", e.getMessage());
        }
    }

    @FXML
    private void handleCancel() {
        resultCard.setVisible(false);
        searchField.clear();
        currentJPO = null;
    }

    @FXML
    private void handleBack() {
        navigateToMain();
    }

    private void navigateToMain() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/MainJPO.fxml"));
            Parent root = loader.load();
            Stage stage = (Stage) backButton.getScene().getWindow();
            Scene scene = new Scene(root);
            scene.getStylesheets().add(getClass().getResource("/tn/cashfly/Styles/custom.css").toExternalForm());
            stage.setScene(scene);
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void showAlert(Alert.AlertType type, String title, String header, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(header);
        alert.setContentText(content);
        alert.showAndWait();
    }
}