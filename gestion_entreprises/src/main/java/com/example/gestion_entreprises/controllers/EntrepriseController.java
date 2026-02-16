package com.example.gestion_entreprises.controllers;

import com.example.gestion_entreprises.entities.Entreprise;
import com.example.gestion_entreprises.services.EntrepriseService;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.Node;
import javafx.scene.control.*;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;

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
    private TextField txtFormeJuridique;

    @FXML
    private TextField txtCapital;

    @FXML
    private TextField txtIdProprietaire;

    @FXML
    private DatePicker dpDateCreation;

    @FXML
    private Button btnSave;

    @FXML
    private Button btnRetour;

    private final EntrepriseService entrepriseService = new EntrepriseService();

    // ===================== CREATE =====================
    @FXML
    private void createEntreprise(ActionEvent event) {

        if (!champsValides()) {
            showAlert(Alert.AlertType.ERROR,
                    "Erreur",
                    "Tous les champs sont obligatoires.");
            return;
        }

        try {
            Entreprise e = new Entreprise(
                    0,
                    txtNom.getText(),
                    txtSecteur.getText(),
                    txtFormeJuridique.getText(),
                    dpDateCreation.getValue(),
                    new BigDecimal(txtCapital.getText()),
                    Integer.parseInt(txtIdProprietaire.getText())
            );

            entrepriseService.ajouter(e);

            showAlert(Alert.AlertType.INFORMATION,
                    "Succès",
                    "Entreprise ajoutée avec succès.");

            clearFields();

        } catch (NumberFormatException ex) {
            showAlert(Alert.AlertType.ERROR,
                    "Erreur de saisie",
                    "Capital ou ID propriétaire invalide.");
        } catch (SQLException ex) {
            showAlert(Alert.AlertType.ERROR,
                    "Erreur SQL",
                    ex.getMessage());
        }
    }

    // ===================== RETOUR =====================
    @FXML
    private void retour(ActionEvent event) {
        changeScreen(event,
                "/com/example/gestion_entreprises/MainMenu.fxml",
                "Menu principal");
    }

    // ===================== VALIDATION =====================
    private boolean champsValides() {
        return !txtNom.getText().isEmpty()
                && !txtSecteur.getText().isEmpty()
                && !txtFormeJuridique.getText().isEmpty()
                && dpDateCreation.getValue() != null
                && !txtCapital.getText().isEmpty()
                && !txtIdProprietaire.getText().isEmpty();
    }

    private void clearFields() {
        txtNom.clear();
        txtSecteur.clear();
        txtFormeJuridique.clear();
        txtCapital.clear();
        txtIdProprietaire.clear();
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
        // No initialization needed for add form
    }
}
