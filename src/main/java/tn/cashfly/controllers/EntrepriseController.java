package tn.cashfly.controllers;
import tn.cashfly.services.GeocodingService;

import tn.cashfly.entities.Entreprise;
import tn.cashfly.services.EntrepriseService;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.Node;
import javafx.scene.control.*;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;

import tn.cashfly.session.UserSession;
import tn.cashfly.DashboardController;
import java.math.BigDecimal;
import java.net.URL;
import java.sql.SQLException;
import java.util.ResourceBundle;

public class EntrepriseController implements Initializable {

    @FXML
    private TextField txtNom;

    @FXML
    private TextField txtSecteur;

    @FXML
    private ComboBox<String> cbFormeJuridique;

    @FXML
    private TextField txtCapital;

    @FXML
    private TextField txtAdresse;

    @FXML
    private DatePicker dpDateCreation;

    @FXML
    private Button btnSave;

    @FXML
    private Button btnRetour;

    private final EntrepriseService entrepriseService = new EntrepriseService();
    private Entreprise entrepriseToEdit;

    public void setEntrepriseToEdit(Entreprise e) {
        this.entrepriseToEdit = e;
        if (e != null) {
            txtNom.setText(e.getNom());
            txtSecteur.setText(e.getSecteur());
            cbFormeJuridique.setValue(e.getFormeJuridique());
            dpDateCreation.setValue(e.getDateCreation());
            txtCapital.setText(e.getCapital().toString());
            txtAdresse.setText(e.getAdresse());
            btnSave.setText("Modifier");
        } else {
            clearFields();
            btnSave.setText("Enregistrer");
        }
    }

    // ===================== CREATE / UPDATE =====================
    @FXML
    private void createEntreprise(ActionEvent event) {

        if (!champsValides()) {
            showAlert(Alert.AlertType.ERROR,
                    "Erreur",
                    "Tous les champs sont obligatoires.");
            return;
        }

        try {
            double latitude = 36.8065; // Default Tunis
            double longitude = 10.1815;
            String adresse = txtAdresse.getText();

            // Geocode only if address changed or new
            if (entrepriseToEdit == null || !adresse.equals(entrepriseToEdit.getAdresse())) {
                double[] coords = GeocodingService.getCoordinates(adresse);
                if (coords[0] != 0 || coords[1] != 0) {
                    latitude = coords[0];
                    longitude = coords[1];
                }
            } else {
                latitude = entrepriseToEdit.getLatitude();
                longitude = entrepriseToEdit.getLongitude();
            }

            if (entrepriseToEdit == null) {
                // CREATE
                Entreprise e = new Entreprise(
                        0,
                        txtNom.getText(),
                        txtSecteur.getText(),
                        cbFormeJuridique.getValue(),
                        dpDateCreation.getValue(),
                        new BigDecimal(txtCapital.getText()),
                        UserSession.getUserId(),
                        latitude,
                        longitude,
                        adresse
                );
                entrepriseService.ajouter(e);
                showAlert(Alert.AlertType.INFORMATION, "Succès", "Entreprise ajoutée avec succès.");
            } else {
                // UPDATE
                entrepriseToEdit.setNom(txtNom.getText());
                entrepriseToEdit.setSecteur(txtSecteur.getText());
                entrepriseToEdit.setFormeJuridique(cbFormeJuridique.getValue());
                entrepriseToEdit.setDateCreation(dpDateCreation.getValue());
                entrepriseToEdit.setCapital(new BigDecimal(txtCapital.getText()));
                entrepriseToEdit.setAdresse(adresse);
                entrepriseToEdit.setLatitude(latitude);
                entrepriseToEdit.setLongitude(longitude);

                entrepriseService.modifier(entrepriseToEdit);
                showAlert(Alert.AlertType.INFORMATION, "Succès", "Entreprise modifiée avec succès.");
            }

            clearFields();
            retour(event);

        } catch (NumberFormatException ex) {
            showAlert(Alert.AlertType.ERROR, "Erreur de saisie", "Capital invalide.");
        } catch (SQLException ex) {
            showAlert(Alert.AlertType.ERROR, "Erreur SQL", ex.getMessage());
        }
    }

    // ===================== RETOUR =====================
    @FXML
    private void retour(ActionEvent event) {
        if (DashboardController.getInstance() != null) {
            DashboardController.getInstance().showEntreprises();
        }
    }


    // ===================== VALIDATION =====================
    private boolean champsValides() {
        return !txtNom.getText().isBlank()
                && !txtSecteur.getText().isBlank()
                && cbFormeJuridique.getValue() != null
                && dpDateCreation.getValue() != null
                && !txtCapital.getText().isBlank()
                && !txtAdresse.getText().isBlank(); // 🔥 obligatoire
    }
    private void clearFields() {
        txtNom.clear();
        txtSecteur.clear();
        cbFormeJuridique.setValue(null);
        txtCapital.clear();
        txtAdresse.clear();
        dpDateCreation.setValue(null);
    }

    // ===================== UTILS =====================
    private void showAlert(Alert.AlertType type, String title, String msg) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(msg);
        alert.showAndWait();
    }

    private void changeScreen(ActionEvent event, String fxmlPath, String title) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource(fxmlPath));
            Parent root = loader.load();

            Stage stage = (Stage) ((Node) event.getSource())
                    .getScene()
                    .getWindow();

            stage.setTitle(title);
            stage.setScene(new Scene(root));
            stage.show();

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        cbFormeJuridique.getItems().addAll(
                "SARL", "SA", "EURL", "SAS", "SNC", "Auto-entrepreneur"
        );
        txtAdresse.setPromptText("Adresse * (obligatoire)");
    }
}
