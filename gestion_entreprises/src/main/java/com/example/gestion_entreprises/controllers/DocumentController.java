package com.example.gestion_entreprises.controllers;

import com.example.gestion_entreprises.entities.Document;
import com.example.gestion_entreprises.services.DocumentService;
import com.example.gestion_entreprises.services.EntrepriseService;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.fxml.FXMLLoader;
import javafx.stage.Stage;

import java.net.URL;
import java.sql.SQLException;
import java.util.ResourceBundle;

public class DocumentController implements Initializable {

    // ===================== FXML FIELDS =====================
    @FXML
    private ChoiceBox<Document.TypeDocument> cbTypeDocument;

    @FXML
    private ChoiceBox<Document.Statut> cbStatut;

    @FXML
    private ChoiceBox<Integer> cbEntreprise;

    @FXML
    private TextField txtNomFichier;

    @FXML
    private TextField txtCheminFichier;

    @FXML
    private TextField txtDescription;

    @FXML
    private Button btnSave;

    @FXML
    private Button btnRetour;

    // ===================== SERVICES =====================
    private final DocumentService documentService = new DocumentService();
    private final EntrepriseService entrepriseService = new EntrepriseService();

    // ===================== CREATE =====================
    @FXML
    private void createDocument(ActionEvent event) {

        if (!champsValides()) {
            showAlert(Alert.AlertType.ERROR,
                    "Erreur",
                    "Tous les champs sont obligatoires.");
            return;
        }

        try {
            Document document = new Document(
                    0,
                    cbEntreprise.getValue(),
                    cbTypeDocument.getValue(),
                    txtNomFichier.getText(),
                    txtCheminFichier.getText(),
                    txtDescription.getText(),
                    null, // date_upload handled by DB
                    cbStatut.getValue()
            );

            documentService.ajouter(document);

            showAlert(Alert.AlertType.INFORMATION,
                    "Succès",
                    "Document ajouté avec succès.");

            clearFields();

        } catch (SQLException e) {
            showAlert(Alert.AlertType.ERROR,
                    "Erreur SQL",
                    e.getMessage());
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
        return cbTypeDocument.getValue() != null
                && cbStatut.getValue() != null
                && cbEntreprise.getValue() != null
                && !txtNomFichier.getText().isEmpty()
                && !txtCheminFichier.getText().isEmpty();
    }

    private void clearFields() {
        cbTypeDocument.setValue(null);
        cbStatut.setValue(null);
        cbEntreprise.setValue(null);
        txtNomFichier.clear();
        txtCheminFichier.clear();
        txtDescription.clear();
    }

    // ===================== INITIALIZE =====================
    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {

        // Enums
        cbTypeDocument.getItems().setAll(Document.TypeDocument.values());
        cbStatut.getItems().setAll(Document.Statut.values());

        // Entreprises IDs
        try {
            cbEntreprise.getItems().setAll(
                    entrepriseService.listEntrepriseIds()
            );
        } catch (SQLException e) {
            e.printStackTrace();
        }
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
}
