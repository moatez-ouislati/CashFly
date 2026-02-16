package com.example.gestion_entreprises.controllers;

import com.example.gestion_entreprises.entities.Document;
import com.example.gestion_entreprises.entities.Entreprise;
import com.example.gestion_entreprises.services.DocumentService;
import com.example.gestion_entreprises.services.EntrepriseService;

import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.*;
import javafx.scene.control.TreeItem;
import javafx.scene.layout.GridPane;

import java.net.URL;
import java.sql.SQLException;
import java.util.List;
import java.util.ResourceBundle;

public class AfficherD implements Initializable {

    @FXML
    private TreeTableView<Object> treeTable;

    @FXML
    private TreeTableColumn<Object, String> colNom;

    @FXML
    private TreeTableColumn<Object, String> colType;

    @FXML
    private TreeTableColumn<Object, String> colStatut;

    @FXML
    private TreeTableColumn<Object, String> colDate;

    @FXML
    private TextField txtSearch;

    private final EntrepriseService entrepriseService = new EntrepriseService();
    private final DocumentService documentService = new DocumentService();

    private TreeItem<Object> root;

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {

        colNom.setCellValueFactory(param -> {
            Object value = param.getValue().getValue();
            if (value instanceof Entreprise e)
                return e.nomProperty();
            if (value instanceof Document d)
                return d.nomFichierProperty();
            return null;
        });

        colType.setCellValueFactory(param -> {
            Object value = param.getValue().getValue();
            if (value instanceof Document d)
                return d.typeDocumentProperty().asString();
            return null;
        });

        colStatut.setCellValueFactory(param -> {
            Object value = param.getValue().getValue();
            if (value instanceof Document d)
                return d.statutProperty().asString();
            return null;
        });

        colDate.setCellValueFactory(param -> {
            Object value = param.getValue().getValue();
            if (value instanceof Document d && d.getDateUpload() != null)
                return d.dateUploadProperty().asString();
            return null;
        });

        loadTree();

        // 🔥 Dynamic search
        txtSearch.textProperty().addListener((obs, oldVal, newVal) -> filterTree(newVal));
    }

    // ===================== LOAD TREE =====================
    private void loadTree() {

        root = new TreeItem<>("ROOT");
        root.setExpanded(true);

        try {
            List<Entreprise> entreprises = entrepriseService.afficher();

            for (Entreprise entreprise : entreprises) {

                TreeItem<Object> entrepriseNode = new TreeItem<>(entreprise);
                entrepriseNode.setExpanded(true);

                List<Document> docs =
                        documentService.getByEntreprise(entreprise.getIdEntreprise());

                for (Document doc : docs) {
                    entrepriseNode.getChildren().add(new TreeItem<>(doc));
                }

                root.getChildren().add(entrepriseNode);
            }

            treeTable.setRoot(root);
            treeTable.setShowRoot(false);

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // ===================== FILTER =====================
    private void filterTree(String keyword) {

        if (keyword == null || keyword.isEmpty()) {
            loadTree();
            return;
        }

        String search = keyword.toLowerCase();
        TreeItem<Object> filteredRoot = new TreeItem<>("ROOT");

        for (TreeItem<Object> entNode : root.getChildren()) {

            Entreprise ent = (Entreprise) entNode.getValue();
            TreeItem<Object> newEntNode = new TreeItem<>(ent);

            boolean entMatch = ent.getNom().toLowerCase().contains(search);

            for (TreeItem<Object> docNode : entNode.getChildren()) {
                Document doc = (Document) docNode.getValue();

                if (doc.getNomFichier().toLowerCase().contains(search)
                        || entMatch) {
                    newEntNode.getChildren().add(new TreeItem<>(doc));
                }
            }

            if (!newEntNode.getChildren().isEmpty() || entMatch) {
                newEntNode.setExpanded(true);
                filteredRoot.getChildren().add(newEntNode);
            }
        }

        treeTable.setRoot(filteredRoot);
        treeTable.setShowRoot(false);
    }

    // ===================== REFRESH =====================
    @FXML
    private void refresh() {
        txtSearch.clear();
        loadTree();
    }

    // ===================== RETOUR =====================
    @FXML
    private void retour(javafx.event.ActionEvent event) {
        try {
            javafx.fxml.FXMLLoader loader =
                    new javafx.fxml.FXMLLoader(
                            getClass().getResource("/com/example/gestion_entreprises/MainMenu.fxml")
                    );
            javafx.scene.Parent root = loader.load();

            javafx.stage.Stage stage =
                    (javafx.stage.Stage) ((javafx.scene.Node) event.getSource())
                            .getScene().getWindow();

            stage.setScene(new javafx.scene.Scene(root));
            stage.setTitle("Menu principal");
            stage.show();

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private Document getSelectedDocument() {

        TreeItem<Object> selectedItem =
                treeTable.getSelectionModel().getSelectedItem();

        if (selectedItem == null || !(selectedItem.getValue() instanceof Document)) {
            return null;
        }
        return (Document) selectedItem.getValue();
    }
    @FXML
    private void supprimerDocument() {

        Document doc = getSelectedDocument();

        if (doc == null) {
            new Alert(Alert.AlertType.WARNING,
                    "Veuillez sélectionner un document.")
                    .show();
            return;
        }

        Alert confirm = new Alert(Alert.AlertType.CONFIRMATION,
                "Supprimer ce document ?", ButtonType.YES, ButtonType.NO);

        confirm.showAndWait();

        if (confirm.getResult() == ButtonType.YES) {
            try {
                documentService.supprimer(doc);
                loadTree();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }
    @FXML
    private void updateDocument() {

        TreeItem<Object> selectedItem =
                treeTable.getSelectionModel().getSelectedItem();

        if (selectedItem == null || !(selectedItem.getValue() instanceof Document)) {
            new Alert(Alert.AlertType.WARNING,
                    "Veuillez sélectionner un document à modifier.")
                    .show();
            return;
        }

        Document selected = (Document) selectedItem.getValue();

        // ===== Dialog =====
        Dialog<ButtonType> dialog = new Dialog<>();
        dialog.setTitle("Modifier Document");
        dialog.setHeaderText("Document ID : " + selected.getIdDocument());

        ButtonType saveBtn =
                new ButtonType("Enregistrer", ButtonBar.ButtonData.OK_DONE);
        dialog.getDialogPane().getButtonTypes()
                .addAll(saveBtn, ButtonType.CANCEL);

        // ===== Fields =====
        TextField tfNom = new TextField(selected.getNomFichier());
        TextField tfChemin = new TextField(selected.getCheminFichier());
        TextField tfDescription = new TextField(selected.getDescription());

        ComboBox<Document.TypeDocument> cbType =
                new ComboBox<>();
        cbType.getItems().addAll(Document.TypeDocument.values());
        cbType.setValue(selected.getTypeDocument());

        ComboBox<Document.Statut> cbStatut =
                new ComboBox<>();
        cbStatut.getItems().addAll(Document.Statut.values());
        cbStatut.setValue(selected.getStatut());

        // ===== Layout =====
        GridPane grid = new GridPane();
        grid.setHgap(10);
        grid.setVgap(10);

        grid.add(new Label("Nom fichier:"), 0, 0);
        grid.add(tfNom, 1, 0);

        grid.add(new Label("Type document:"), 0, 1);
        grid.add(cbType, 1, 1);

        grid.add(new Label("Statut:"), 0, 2);
        grid.add(cbStatut, 1, 2);

        grid.add(new Label("Chemin fichier:"), 0, 3);
        grid.add(tfChemin, 1, 3);

        grid.add(new Label("Description:"), 0, 4);
        grid.add(tfDescription, 1, 4);

        dialog.getDialogPane().setContent(grid);

        // ===== Action =====
        dialog.showAndWait().ifPresent(result -> {
            if (result == saveBtn) {
                try {
                    selected.setNomFichier(tfNom.getText());
                    selected.setTypeDocument(cbType.getValue());
                    selected.setStatut(cbStatut.getValue());
                    selected.setCheminFichier(tfChemin.getText());
                    selected.setDescription(tfDescription.getText());

                    documentService.modifier(selected);
                    loadTree();

                    new Alert(Alert.AlertType.INFORMATION,
                            "Document modifié avec succès.")
                            .show();

                } catch (Exception e) {
                    new Alert(Alert.AlertType.ERROR,
                            "Erreur : vérifiez les champs.")
                            .show();
                }
            }
        });
    }


}
