package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.scene.control.Alert;
import javafx.scene.control.Label;
import javafx.scene.web.HTMLEditor;
import javafx.stage.Stage;
import javafx.stage.FileChooser;
import tn.cashfly.entities.OPÉRATIONS;
import tn.cashfly.entities.OperationNote;
import tn.cashfly.services.ExportService;
import tn.cashfly.services.NoteService;

import java.io.File;
import java.sql.SQLException;
import java.time.LocalDateTime;

public class NoteEditorController {

    @FXML
    private HTMLEditor htmlEditor;
    @FXML
    private Label titleLabel;
    @FXML
    private Label subtitleLabel;

    private OPÉRATIONS currentOperation;
    private OperationNote currentNote;
    private final NoteService noteService = new NoteService();
    private final ExportService exportService = new ExportService();

    public void setOperation(OPÉRATIONS operation) {
        this.currentOperation = operation;
        this.titleLabel.setText("Note pour l'opération #" + operation.getIdOperation());
        this.subtitleLabel.setText(operation.getType() + " - " + operation.getCategorie() + " (" + operation.getMontant() + " TND)");
        
        loadNote();
    }

    private void loadNote() {
        try {
            currentNote = noteService.getByOperationId(currentOperation.getIdOperation());
            if (currentNote != null) {
                htmlEditor.setHtmlText(currentNote.getContent());
            }
        } catch (SQLException e) {
            e.printStackTrace();
            showError("Erreur lors du chargement de la note", e);
        }
    }

    @FXML
    private void handleSave() {
        String content = htmlEditor.getHtmlText();
        try {
            if (currentNote == null) {
                // Create new note
                currentNote = new OperationNote(currentOperation.getIdOperation(), content);
                noteService.add(currentNote);
            } else {
                // Update existing note
                currentNote.setContent(content);
                currentNote.setUpdatedAt(LocalDateTime.now());
                noteService.update(currentNote);
            }
            closeStage();
        } catch (SQLException e) {
            e.printStackTrace();
            showError("Erreur lors de l'enregistrement de la note", e);
        }
    }

    private void showError(String message, Exception e) {
        Alert alert = new Alert(Alert.AlertType.ERROR);
        alert.setTitle("Erreur");
        alert.setHeaderText(message);
        alert.setContentText(e.getMessage());
        alert.showAndWait();
    }

    @FXML
    private void handleExportPDF() {
        FileChooser fileChooser = new FileChooser();
        fileChooser.setTitle("Enregistrer le rapport PDF");
        fileChooser.setInitialFileName("Note_Operation_" + currentOperation.getIdOperation() + ".pdf");
        fileChooser.getExtensionFilters().add(new FileChooser.ExtensionFilter("PDF Files", "*.pdf"));
        
        File file = fileChooser.showSaveDialog(htmlEditor.getScene().getWindow());
        if (file != null) {
            try {
                exportService.exportNoteToPDF(currentOperation, htmlEditor.getHtmlText(), file.getAbsolutePath());
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    @FXML
    private void handleCancel() {
        closeStage();
    }

    private void closeStage() {
        Stage stage = (Stage) htmlEditor.getScene().getWindow();
        stage.close();
    }
}

