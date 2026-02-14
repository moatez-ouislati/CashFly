package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;
import tn.cashfly.entities.JPO;
import tn.cashfly.services.ServiceJPO;

import java.io.IOException;
import java.sql.SQLException;
import java.time.LocalDate;
import java.time.ZoneId;
import java.util.Date;
import java.util.List;

public class UpdateJPOController {

    @FXML
    private TextField searchField;
    @FXML
    private TextField idField;
    @FXML
    private TextField titreField;
    @FXML
    private DatePicker dateField;
    @FXML
    private TextField lieuField;
    @FXML
    private TextArea descriptionField;
    @FXML
    private Label titreError;
    @FXML
    private Label dateError;
    @FXML
    private Label lieuError;
    @FXML
    private VBox formContainer;

    private ServiceJPO serviceJPO;
    private JPO currentJPO;

    @FXML
    public void initialize() {
        serviceJPO = new ServiceJPO();
        setupValidation();
    }

    private void setupValidation() {
        titreField.textProperty().addListener((obs, old, newVal) -> hideError(titreError));
        dateField.valueProperty().addListener((obs, old, newVal) -> hideError(dateError));
        lieuField.textProperty().addListener((obs, old, newVal) -> hideError(lieuError));
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
                loadJPO(found);
            } else {
                showAlert(Alert.AlertType.WARNING, "Non trouvé", "Aucune JPO trouvée",
                        "Aucun événement ne correspond à votre recherche.");
                formContainer.setDisable(true);
            }

        } catch (SQLException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur de base de données", e.getMessage());
        }
    }

    private void loadJPO(JPO jpo) {
        currentJPO = jpo;
        idField.setText(String.valueOf(jpo.getId_evenement()));
        titreField.setText(jpo.getTitre());
        lieuField.setText(jpo.getLieu());
        descriptionField.setText(jpo.getDescription());

        // Convert sql.Date to LocalDate
        if (jpo.getDate_evenement() != null) {
            LocalDate localDate = new java.sql.Date(jpo.getDate_evenement().getTime()).toLocalDate();
            dateField.setValue(localDate);
        }

        formContainer.setDisable(false);
        hideAllErrors();
    }

    @FXML
    private void handleUpdate() {
        if (!validateForm()) return;

        try {
            currentJPO.setTitre(titreField.getText().trim());
            currentJPO.setDate_evenement(Date.from(dateField.getValue().atStartOfDay(ZoneId.systemDefault()).toInstant()));
            currentJPO.setLieu(lieuField.getText().trim());
            currentJPO.setDescription(descriptionField.getText().trim());

            serviceJPO.update(currentJPO);

            showAlert(Alert.AlertType.INFORMATION, "Succès", "JPO mise à jour!",
                    "L'événement '" + currentJPO.getTitre() + "' a été modifié.");

            handleBack();

        } catch (SQLException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur de base de données", e.getMessage());
        }
    }

    @FXML
    private void handleClear() {
        if (currentJPO != null) {
            loadJPO(currentJPO); // Reload original values
        }
    }

    @FXML
    private void handleBack() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/MainJPO.fxml"));
            Parent root = loader.load();
            Stage stage = (Stage) searchField.getScene().getWindow();
            stage.setScene(new Scene(root));
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private boolean validateForm() {
        boolean valid = true;

        if (titreField.getText() == null || titreField.getText().trim().isEmpty()) {
            showError(titreError);
            valid = false;
        }

        if (dateField.getValue() == null) {
            showError(dateError);
            valid = false;
        }

        if (lieuField.getText() == null || lieuField.getText().trim().isEmpty()) {
            showError(lieuError);
            valid = false;
        }

        return valid;
    }

    private void showError(Label errorLabel) {
        errorLabel.setVisible(true);
    }

    private void hideError(Label errorLabel) {
        errorLabel.setVisible(false);
    }

    private void hideAllErrors() {
        titreError.setVisible(false);
        dateError.setVisible(false);
        lieuError.setVisible(false);
    }

    private void showAlert(Alert.AlertType type, String title, String header, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setContentText(content);
        alert.setHeaderText(header);
        alert.showAndWait();
    }
}