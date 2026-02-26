package com.example.gestion_entreprises.controllers;
import javafx.stage.FileChooser;
import java.io.File;
import java.time.LocalDateTime;
import com.example.gestion_entreprises.services.OCRService;
import com.example.gestion_entreprises.entities.Document;
import com.example.gestion_entreprises.entities.Entreprise;
import com.example.gestion_entreprises.services.DocumentService;
import com.example.gestion_entreprises.services.EntrepriseService;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.StackPane;
import javafx.geometry.Pos;
import javafx.stage.Stage;

import java.io.IOException;
import java.net.URL;
import java.sql.SQLException;
import java.util.List;
import java.util.ResourceBundle;
import javafx.stage.FileChooser;
import java.io.File;
import javafx.stage.FileChooser;
import java.io.File;

public class DocumentController implements Initializable {

    @FXML private ChoiceBox<Document.TypeDocument> cbTypeDocument;
    @FXML private ChoiceBox<Document.Statut> cbStatut;
    @FXML private ChoiceBox<String> cbEntreprise; // 🔥 NOW STRING (NAME)
    @FXML private TextField txtNomFichier;
    @FXML private TextField txtCheminFichier;
    @FXML private TextField txtDescription;
    @FXML private Button btnSave;
    @FXML private Button btnRetour;
    @FXML private Label notificationsIcon;

    private final DocumentService documentService = new DocumentService();
    private final EntrepriseService entrepriseService = new EntrepriseService();

    private List<Entreprise> entreprisesCache; // store entreprises
    private NotificationsStage notificationsPanel;

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {

        cbTypeDocument.getItems().setAll(Document.TypeDocument.values());
        cbStatut.getItems().setAll(Document.Statut.values());

        try {
            entreprisesCache = entrepriseService.afficher();
            cbEntreprise.getItems().clear();

            for (Entreprise e : entreprisesCache) {
                cbEntreprise.getItems().add(e.getNom());
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }

        btnSave.sceneProperty().addListener((obs, oldScene, newScene) -> {
            if (newScene != null) {
                StackPane root = (StackPane) newScene.getRoot();
                notificationsPanel = new NotificationsStage();
                root.getChildren().add(notificationsPanel);
                StackPane.setAlignment(notificationsPanel, Pos.CENTER_RIGHT);
            }
        });

        notificationsIcon.setOnMouseClicked(e -> {
            if (notificationsPanel != null) {
                notificationsPanel.toggle();
            }
        });
    }
    @FXML
    private void chooseFile() {

        FileChooser fileChooser = new FileChooser();

        fileChooser.getExtensionFilters().addAll(
                new FileChooser.ExtensionFilter("Images", "*.png", "*.jpg", "*.jpeg"),
                new FileChooser.ExtensionFilter("PDF", "*.pdf")
        );

        File file = fileChooser.showOpenDialog(btnSave.getScene().getWindow());

        if (file != null) {
            txtCheminFichier.setText(file.getAbsolutePath());
        }
    }

    @FXML

    private void createDocument(ActionEvent event) {

        if (!champsValides()) {
            new Alert(
                    Alert.AlertType.ERROR,
                    "Tous les champs obligatoires doivent être remplis."
            ).show();
            return;
        }

        try {
            if (cbEntreprise.getValue() == null) {
                new Alert(
                        Alert.AlertType.ERROR,
                        "Veuillez sélectionner une entreprise."
                ).show();
                return;
            }

            int entrepriseId = entreprisesCache.stream()
                    .filter(e -> e.getNom().equals(cbEntreprise.getValue()))
                    .findFirst()
                    .map(Entreprise::getIdEntreprise)
                    .orElseThrow();

            // 🔥 OCR
            OCRService ocrService = new OCRService();
            String texteExtrait = ocrService.extractText(txtCheminFichier.getText());

            Document document = new Document(
                    0,
                    entrepriseId,
                    cbTypeDocument.getValue(),
                    txtNomFichier.getText(),
                    txtCheminFichier.getText(),
                    txtDescription.getText(),
                    LocalDateTime.now(),
                    cbStatut.getValue(),
                    texteExtrait
            );

            documentService.ajouter(document);

            if (notificationsPanel != null) {
                notificationsPanel.addNote(
                        "Document ajouté : " + document.getNomFichier(),
                        NotificationType.INFO
                );
                OCRService ocr = new OCRService();
                String extractedText = ocr.extractText(txtCheminFichier.getText());

                document.setTexteOcr(extractedText);
            }

            clearFields();

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @FXML
    private void retour(ActionEvent event) {

        try {
            FXMLLoader loader = new FXMLLoader(
                    getClass().getResource(
                            "/com/example/gestion_entreprises/MainMenu.fxml"
                    )
            );

            Parent root = loader.load();

            Stage stage = (Stage) btnRetour.getScene().getWindow();

            stage.setScene(new Scene(root));
            stage.setTitle("CashFly");
            stage.show();

        } catch (IOException e) {
            e.printStackTrace();
        }
    }

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
}