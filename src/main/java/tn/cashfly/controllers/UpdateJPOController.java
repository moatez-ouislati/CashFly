package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.GridPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Region;
import javafx.scene.layout.VBox;
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
import java.util.List;
import java.util.regex.Pattern;

public class UpdateJPOController {

    @FXML private TextField searchField;
    @FXML private TextField idField;
    @FXML private TextField titreField;
    @FXML private DatePicker dateField;
    @FXML private TextField lieuField;
    @FXML private TextArea descriptionField;
    @FXML private Spinner<Integer> maxParticipantsSpinner;
    @FXML private ImageView currentImageView;
    @FXML private ImageView newImagePreview;
    @FXML private Label newImageNameLabel;
    @FXML private Label titreError;
    @FXML private Label dateError;
    @FXML private Label lieuError;
    @FXML private VBox formContainer;
    @FXML private Button backButton;
    @FXML private Button updateButton;

    private ServiceJPO serviceJPO;
    private JPO currentJPO;
    private File selectedImageFile;
    private String currentImagePath;
    private static final Pattern ONLY_NUMBERS = Pattern.compile("^[0-9]+$");
    private static final int MIN_LENGTH = 3;

    @FXML
    public void initialize() {
        serviceJPO = new ServiceJPO();
        setupValidation();

        SpinnerValueFactory<Integer> valueFactory =
                new SpinnerValueFactory.IntegerSpinnerValueFactory(1, 1000, 50);
        maxParticipantsSpinner.setValueFactory(valueFactory);

        formContainer.setDisable(true);
    }

    private void setupValidation() {
        titreField.textProperty().addListener((obs, old, newVal) -> validateTitreRealTime(newVal));
        lieuField.textProperty().addListener((obs, old, newVal) -> validateLieuRealTime(newVal));
        dateField.valueProperty().addListener((obs, old, newVal) -> hideError(dateError));
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
    private void handleSearch() {
        String search = searchField.getText().trim();
        if (search.isEmpty()) {
            showAlert(Alert.AlertType.WARNING, "Recherche vide", "Veuillez entrer un ID ou titre", null);
            return;
        }

        try {
            List<JPO> allJPOs = serviceJPO.getAll();
            JPO found = null;

            try {
                int id = Integer.parseInt(search);
                found = allJPOs.stream().filter(j -> j.getId_evenement() == id).findFirst().orElse(null);
            } catch (NumberFormatException e) {
                found = allJPOs.stream()
                        .filter(j -> j.getTitre().toLowerCase().contains(search.toLowerCase()))
                        .findFirst().orElse(null);
            }

            if (found != null) {
                loadJPO(found);
            } else {
                showAlert(Alert.AlertType.WARNING, "Non trouvé", "Aucune JPO trouvée", null);
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
        currentImagePath = jpo.getImagePath();

        maxParticipantsSpinner.getValueFactory().setValue(jpo.getMaxParticipants());

        if (jpo.getDate_evenement() != null) {
            LocalDate localDate = new java.util.Date(jpo.getDate_evenement().getTime()).toInstant()
                    .atZone(ZoneId.systemDefault()).toLocalDate();
            dateField.setValue(localDate);
        }

        if (currentImagePath != null && !currentImagePath.isEmpty()) {
            currentImageView.setImage(ImageStorage.loadImage(currentImagePath));
        } else {
            currentImageView.setImage(ImageStorage.loadImage(null));
        }

        formContainer.setDisable(false);
        hideAllErrors();
    }

    @FXML
    private void handleSelectImage() {
        FileChooser chooser = new FileChooser();
        chooser.getExtensionFilters().add(
                new FileChooser.ExtensionFilter("Images", "*.png", "*.jpg", "*.jpeg", "*.gif")
        );
        selectedImageFile = chooser.showOpenDialog(titreField.getScene().getWindow());

        if (selectedImageFile != null) {
            newImageNameLabel.setText(selectedImageFile.getName());
            newImagePreview.setImage(new Image(selectedImageFile.toURI().toString()));
        }
    }

    @FXML
    private void handleUpdate() {
        if (!validateAll()) return;

        try {
            currentJPO.setTitre(titreField.getText().trim());
            currentJPO.setDate_evenement(Date.from(dateField.getValue().atStartOfDay(ZoneId.systemDefault()).toInstant()));
            currentJPO.setLieu(lieuField.getText().trim());
            currentJPO.setDescription(descriptionField.getText().trim());
            currentJPO.setMaxParticipants(maxParticipantsSpinner.getValue());

            if (selectedImageFile != null) {
                ImageStorage.deleteImage(currentImagePath);
                String savedPath = ImageStorage.saveImage(selectedImageFile);
                currentJPO.setImagePath(savedPath);
            }

            serviceJPO.update(currentJPO);

            showAlert(Alert.AlertType.INFORMATION, "Succès", "JPO mise à jour!",
                    "L'événement '" + currentJPO.getTitre() + "' a été modifié.");
            handleBack();

        } catch (SQLException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur de base de données", e.getMessage());
        } catch (IOException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur de sauvegarde d'image", e.getMessage());
        }
    }

    @FXML
    private void handleClear() {
        if (currentJPO != null) loadJPO(currentJPO);
    }

    @FXML
    private void handleBack() {
        try {
            NavigationUtil.navigateTo((Stage) backButton.getScene().getWindow(), "MainJPO.fxml");
        } catch (IOException e) {
            e.printStackTrace();
        }
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
    }

    private void showAlert(Alert.AlertType type, String title, String header, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(header);
        alert.setContentText(content);
        alert.showAndWait();
    }
}