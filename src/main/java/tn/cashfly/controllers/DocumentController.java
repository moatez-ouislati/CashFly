package tn.cashfly.controllers;
import javafx.stage.FileChooser;
import java.io.File;
import java.time.LocalDateTime;
import tn.cashfly.services.OCRService;
import tn.cashfly.entities.Document;
import tn.cashfly.entities.Entreprise;
import tn.cashfly.services.DocumentService;
import tn.cashfly.services.EntrepriseService;

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

import tn.cashfly.DashboardController;
import tn.cashfly.session.UserSession;
import tn.cashfly.tools.NotificationService;
import tn.cashfly.controllers.NotificationType;

import java.io.IOException;
import java.net.URL;
import java.sql.SQLException;
import java.util.List;
import java.util.ResourceBundle;

public class DocumentController implements Initializable {

    @FXML private ChoiceBox<Document.TypeDocument> cbTypeDocument;
    @FXML private ChoiceBox<Document.Statut> cbStatut;
    @FXML private ChoiceBox<String> cbEntreprise; // 🔥 NOW STRING (NAME)
    @FXML private TextField txtNomFichier;
    @FXML private TextField txtCheminFichier;
    @FXML private TextField txtDescription;
    @FXML private Button btnSave;
    @FXML private Button btnRetour;

    private final DocumentService documentService = new DocumentService();
    private final EntrepriseService entrepriseService = new EntrepriseService();

    private Document documentToEdit;
    private List<Entreprise> entreprisesCache; // store entreprises

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {

        cbTypeDocument.getItems().setAll(Document.TypeDocument.values());
        cbStatut.getItems().setAll(Document.Statut.values());

        try {
            Integer userId = UserSession.getUserId();
            List<Entreprise> allEntreprises = entrepriseService.afficher();
            
            if (userId != null) {
                entreprisesCache = allEntreprises.stream()
                        .filter(e -> e.getIdProprietaire() == userId)
                        .collect(java.util.stream.Collectors.toList());
            } else {
                entreprisesCache = allEntreprises;
            }

            cbEntreprise.getItems().clear();

            for (Entreprise e : entreprisesCache) {
                cbEntreprise.getItems().add(e.getNom());
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }
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

    public void setDocumentToEdit(Document d) {
        this.documentToEdit = d;
        if (d != null) {
            cbTypeDocument.setValue(d.getTypeDocument());
            cbStatut.setValue(d.getStatut());
            txtNomFichier.setText(d.getNomFichier());
            txtCheminFichier.setText(d.getCheminFichier());
            txtDescription.setText(d.getDescription());
            btnSave.setText("Modifier");

            // Select enterprise in ChoiceBox
            if (entreprisesCache != null) {
                entreprisesCache.stream()
                        .filter(e -> e.getIdEntreprise() == d.getIdEntreprise())
                        .findFirst()
                        .ifPresent(e -> cbEntreprise.setValue(e.getNom()));
            }
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

            // 🔥 OCR (only if path changed or new document)
            String texteExtrait = (documentToEdit != null) ? documentToEdit.getTexteOcr() : "";
            
            if (documentToEdit == null || !documentToEdit.getCheminFichier().equals(txtCheminFichier.getText())) {
                OCRService ocrService = new OCRService();
                try {
                    texteExtrait = ocrService.extractText(txtCheminFichier.getText());
                } catch (Throwable ocrEx) {
                    System.err.println("OCR Error: " + ocrEx.getMessage());
                    javafx.application.Platform.runLater(() -> {
                        new Alert(Alert.AlertType.WARNING, 
                            "L'extraction de texte (OCR) a échoué car Tesseract n'est pas configuré sur cette machine.\n\n" +
                            "Le document sera quand même enregistré."
                        ).show();
                    });
                }
            }

            if (documentToEdit == null) {
                Document document = new Document(
                        0,
                        entrepriseId,
                        UserSession.getUserId(),
                        cbTypeDocument.getValue(),
                        txtNomFichier.getText(),
                        txtCheminFichier.getText(),
                        txtDescription.getText(),
                        LocalDateTime.now(),
                        cbStatut.getValue(),
                        texteExtrait
                );

                documentService.ajouter(document);
                NotificationService.info("Document ajouté : " + document.getNomFichier());
            } else {
                documentToEdit.setIdUtilisateur(UserSession.getUserId());
                documentToEdit.setIdEntreprise(entrepriseId);
                documentToEdit.setTypeDocument(cbTypeDocument.getValue());
                documentToEdit.setNomFichier(txtNomFichier.getText());
                documentToEdit.setCheminFichier(txtCheminFichier.getText());
                documentToEdit.setDescription(txtDescription.getText());
                documentToEdit.setStatut(cbStatut.getValue());
                documentToEdit.setTexteOcr(texteExtrait);
                
                documentService.modifier(documentToEdit);
                NotificationService.info("Document modifié : " + documentToEdit.getNomFichier());
            }

            clearFields();
            if (documentToEdit != null) {
                retour(null);
            }

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @FXML
    private void retour(ActionEvent event) {
        if (DashboardController.getInstance() != null) {
            DashboardController.getInstance().showDocuments();
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
