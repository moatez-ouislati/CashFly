package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.stage.Stage;
import tn.cashfly.entities.JPO;
import tn.cashfly.services.ServiceJPO;

import java.io.IOException;
import java.sql.SQLException;
import java.time.LocalDate;
import java.time.ZoneId;
import java.util.Date;
import java.util.regex.Pattern;

public class AddJPOController {

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
    private Label descriptionError;

    private ServiceJPO serviceJPO;

    // Validation patterns
    private static final Pattern ONLY_NUMBERS = Pattern.compile("^[0-9]+$");
    private static final int MIN_LENGTH = 3;
    private static final int MAX_DESCRIPTION = 500;

    @FXML
    public void initialize() {
        serviceJPO = new ServiceJPO();
        setupRealTimeValidation();
    }

    private void setupRealTimeValidation() {
        // Titre validation
        titreField.textProperty().addListener((obs, old, newVal) -> {
            validateTitreRealTime(newVal);
        });

        // Lieu validation
        lieuField.textProperty().addListener((obs, old, newVal) -> {
            validateLieuRealTime(newVal);
        });

        // Date validation
        dateField.valueProperty().addListener((obs, old, newVal) -> {
            if (newVal != null && newVal.isBefore(LocalDate.now())) {
                showError(dateError, "La date ne peut pas être dans le passé");
            } else {
                hideError(dateError);
            }
        });

        // Description length limit
        descriptionField.textProperty().addListener((obs, old, newVal) -> {
            if (newVal.length() > MAX_DESCRIPTION) {
                descriptionField.setText(old);
                showError(descriptionError, "Maximum " + MAX_DESCRIPTION + " caractères");
            } else {
                hideError(descriptionError);
            }
        });
    }

    private void validateTitreRealTime(String value) {
        if (value == null || value.trim().isEmpty()) {
            hideError(titreError);
            return;
        }

        String trimmed = value.trim();

        if (trimmed.length() < MIN_LENGTH) {
            showError(titreError, "Minimum " + MIN_LENGTH + " caractères requis");
        } else if (ONLY_NUMBERS.matcher(trimmed).matches()) {
            showError(titreError, "Ne peut pas contenir uniquement des chiffres");
        } else {
            hideError(titreError);
        }
    }

    private void validateLieuRealTime(String value) {
        if (value == null || value.trim().isEmpty()) {
            hideError(lieuError);
            return;
        }

        String trimmed = value.trim();

        if (trimmed.length() < MIN_LENGTH) {
            showError(lieuError, "Minimum " + MIN_LENGTH + " caractères requis");
        } else if (ONLY_NUMBERS.matcher(trimmed).matches()) {
            showError(lieuError, "Ne peut pas contenir uniquement des chiffres");
        } else {
            hideError(lieuError);
        }
    }

    @FXML
    private void handleSave() {
        if (!validateAll()) return;

        try {
            JPO jpo = new JPO();
            jpo.setTitre(titreField.getText().trim());
            jpo.setDate_evenement(Date.from(dateField.getValue().atStartOfDay(ZoneId.systemDefault()).toInstant()));
            jpo.setLieu(lieuField.getText().trim());
            jpo.setDescription(descriptionField.getText().trim());

            serviceJPO.add(jpo);

            showAlert(Alert.AlertType.INFORMATION, "Succès", "JPO créée avec succès!",
                    "L'événement '" + jpo.getTitre() + "' a été enregistré avec l'ID: " + jpo.getId_evenement());

            handleClear();
            handleBack();

        } catch (SQLException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur de base de données", e.getMessage());
        }
    }

    private boolean validateAll() {
        boolean valid = true;

        // Validate Titre
        String titre = titreField.getText();
        if (titre == null || titre.trim().isEmpty()) {
            showError(titreError, "Le titre est obligatoire");
            valid = false;
        } else if (titre.trim().length() < MIN_LENGTH) {
            showError(titreError, "Minimum " + MIN_LENGTH + " caractères requis");
            valid = false;
        } else if (ONLY_NUMBERS.matcher(titre.trim()).matches()) {
            showError(titreError, "Ne peut pas contenir uniquement des chiffres");
            valid = false;
        }

        // Validate Date
        if (dateField.getValue() == null) {
            showError(dateError, "La date est obligatoire");
            valid = false;
        } else if (dateField.getValue().isBefore(LocalDate.now())) {
            showError(dateError, "La date ne peut pas être dans le passé");
            valid = false;
        }

        // Validate Lieu
        String lieu = lieuField.getText();
        if (lieu == null || lieu.trim().isEmpty()) {
            showError(lieuError, "Le lieu est obligatoire");
            valid = false;
        } else if (lieu.trim().length() < MIN_LENGTH) {
            showError(lieuError, "Minimum " + MIN_LENGTH + " caractères requis");
            valid = false;
        } else if (ONLY_NUMBERS.matcher(lieu.trim()).matches()) {
            showError(lieuError, "Ne peut pas contenir uniquement des chiffres");
            valid = false;
        }

        return valid;
    }

    @FXML
    private void handleClear() {
        titreField.clear();
        dateField.setValue(null);
        lieuField.clear();
        descriptionField.clear();
        hideAllErrors();
    }

    @FXML
    private void handleBack() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/MainJPO.fxml"));
            Parent root = loader.load();
            Stage stage = (Stage) titreField.getScene().getWindow();
            stage.setScene(new Scene(root));
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void showError(Label label, String message) {
        label.setText(message);
        label.setVisible(true);
    }

    private void hideError(Label label) {
        label.setVisible(false);
    }

    private void hideAllErrors() {
        hideError(titreError);
        hideError(dateError);
        hideError(lieuError);
        hideError(descriptionError);
    }

    private void showAlert(Alert.AlertType type, String title, String header, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(header);
        alert.setContentText(content);
        alert.showAndWait();
    }
}