package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.stage.FileChooser;
import javafx.stage.Stage;
import tn.cashfly.entities.JPO;
import tn.cashfly.services.ServiceJPO;
import tn.cashfly.utils.ImageStorage;
import tn.cashfly.utils.NavigationUtil;
import tn.cashfly.utils.SessionManager;

import java.io.File;
import java.io.IOException;
import java.sql.SQLException;
import java.time.LocalDate;
import java.time.ZoneId;
import java.util.Date;
import java.util.regex.Pattern;

public class AddJPOController {

    @FXML private TextField titreField;
    @FXML private DatePicker dateField;
    @FXML private TextField lieuField;
    @FXML private TextArea descriptionField;
    @FXML private Spinner<Integer> maxParticipantsSpinner;
    @FXML private ImageView imagePreview;
    @FXML private Label imageNameLabel;
    @FXML private Label titreError;
    @FXML private Label dateError;
    @FXML private Label lieuError;
    @FXML private Label maxParticipantsError;
    @FXML private Button saveButton;
    @FXML private Button backButton;

    private ServiceJPO serviceJPO;
    private File selectedImageFile;
    private static final Pattern ONLY_NUMBERS = Pattern.compile("^[0-9]+$");
    private static final int MIN_LENGTH = 3;

    @FXML
    public void initialize() {
        serviceJPO = new ServiceJPO();
        setupValidation();

        // Initialize spinner
        SpinnerValueFactory<Integer> valueFactory =
                new SpinnerValueFactory.IntegerSpinnerValueFactory(1, 1000, 50);
        maxParticipantsSpinner.setValueFactory(valueFactory);
        maxParticipantsSpinner.setEditable(true);
    }

    @FXML
    private void handleLogout() {
        SessionManager.clearSession();
        try {
            NavigationUtil.navigateTo((Stage) backButton.getScene().getWindow(), "RoleSelector.fxml");
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void setupValidation() {
        titreField.textProperty().addListener((obs, old, newVal) -> validateTitreRealTime(newVal));
        lieuField.textProperty().addListener((obs, old, newVal) -> validateLieuRealTime(newVal));
        dateField.valueProperty().addListener((obs, old, newVal) -> {
            if (newVal != null && newVal.isBefore(LocalDate.now())) {
                showError(dateError, "La date ne peut pas être dans le passé");
            } else {
                hideError(dateError);
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
    private void handleSelectImage() {
        FileChooser chooser = new FileChooser();
        chooser.getExtensionFilters().add(
                new FileChooser.ExtensionFilter("Images", "*.png", "*.jpg", "*.jpeg", "*.gif")
        );
        selectedImageFile = chooser.showOpenDialog(titreField.getScene().getWindow());

        if (selectedImageFile != null) {
            imageNameLabel.setText(selectedImageFile.getName());
            imagePreview.setImage(new Image(selectedImageFile.toURI().toString()));
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
            jpo.setMaxParticipants(maxParticipantsSpinner.getValue());

            if (selectedImageFile != null) {
                String savedPath = ImageStorage.saveImage(selectedImageFile);
                jpo.setImagePath(savedPath);
            }

            serviceJPO.add(jpo);

            showAlert(Alert.AlertType.INFORMATION, "Succès", "JPO créée avec succès!",
                    "L'événement '" + jpo.getTitre() + "' a été enregistré avec l'ID: " + jpo.getId_evenement());

            handleClear();
            handleBack();

        } catch (SQLException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur de base de données", e.getMessage());
        } catch (IOException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur de sauvegarde d'image", e.getMessage());
        }
    }

    @FXML
    private void handleClear() {
        titreField.clear();
        dateField.setValue(null);
        lieuField.clear();
        descriptionField.clear();
        maxParticipantsSpinner.getValueFactory().setValue(50);
        imagePreview.setImage(null);
        imageNameLabel.setText("Aucune image sélectionnée");
        selectedImageFile = null;
        hideAllErrors();
    }

    @FXML
    private void handleBack() {
        try {
            NavigationUtil.navigateTo((Stage) backButton.getScene().getWindow(), "MainJPO.fxml");
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private boolean validateAll() {
        boolean valid = true;

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

        if (dateField.getValue() == null) {
            showError(dateError, "La date est obligatoire");
            valid = false;
        } else if (dateField.getValue().isBefore(LocalDate.now())) {
            showError(dateError, "La date ne peut pas être dans le passé");
            valid = false;
        }

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
        hideError(maxParticipantsError);
    }

    private void showAlert(Alert.AlertType type, String title, String header, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(header);
        alert.setContentText(content);
        alert.showAndWait();
    }
}