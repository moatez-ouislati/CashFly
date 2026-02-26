package com.example.gestion_entreprises.controllers;

import com.example.gestion_entreprises.entities.Document;
import com.example.gestion_entreprises.entities.Entreprise;
import com.example.gestion_entreprises.services.DocumentService;
import com.example.gestion_entreprises.services.EntrepriseService;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.*;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import javafx.stage.Stage;

import java.net.URL;
import java.sql.SQLException;
import java.util.List;
import java.util.ResourceBundle;
import java.util.stream.Collectors;

public class AfficherD implements Initializable {

    @FXML private FlowPane cardContainer;
    @FXML private TextField txtSearch;
    @FXML private HBox entrepriseContainer;

    private final DocumentService service = new DocumentService();
    private final EntrepriseService entrepriseService = new EntrepriseService();
    private List<Entreprise> entreprises;
    private ObservableList<Document> masterList;


    @Override
    public void initialize(URL url, ResourceBundle rb) {

        try {
            entreprises = entrepriseService.afficher();
        } catch (SQLException e) {
            e.printStackTrace();
        }

        load();                   // charge les documents
        loadEntrepriseButtons();  // crée les boutons entreprises

        txtSearch.textProperty().addListener((obs, o, n) -> filter());
    }

    private void loadEntrepriseButtons() {

        entrepriseContainer.getChildren().clear();

        // Bouton Tous
        Button allBtn = createEntrepriseButton("Tous", -1);
        entrepriseContainer.getChildren().add(allBtn);

        for (Entreprise e : entreprises) {
            Button btn = createEntrepriseButton(e.getNom(), e.getIdEntreprise());
            entrepriseContainer.getChildren().add(btn);
        }
    }
    private Button createEntrepriseButton(String text, int id) {

        Button btn = new Button(text);

        btn.setStyle("""
        -fx-background-color: #f1f5f9;
        -fx-background-radius: 25;
        -fx-padding: 8 20;
        -fx-font-size: 13px;
        -fx-cursor: hand;
    """);

        btn.setOnAction(e -> {
            if (id == -1) {
                displayCards(masterList);

            } else {
                filterByEntreprise(id);
            }
        });

        return btn;
    }
    private void filterByEntreprise(int idEntreprise) {

        List<Document> filtered = masterList.stream()
                .filter(d -> d.getIdEntreprise() == idEntreprise)
                .toList();

        displayCards(filtered);
    }


    private VBox createModernCard(Document d) {

        String nomEntreprise = entreprises.stream()
                .filter(e -> e.getIdEntreprise() == d.getIdEntreprise())
                .map(Entreprise::getNom)
                .findFirst()
                .orElse("Inconnue");

        VBox card = new VBox(10);
        card.setPrefWidth(260);

        card.setStyle("""
        -fx-background-color: white;
        -fx-background-radius: 15;
        -fx-padding: 20;
        -fx-effect: dropshadow(gaussian, rgba(0,0,0,0.08), 15,0,0,4);
    """);

        // Hover animation
        card.setOnMouseEntered(e -> {
            card.setScaleX(1.03);
            card.setScaleY(1.03);
        });

        card.setOnMouseExited(e -> {
            card.setScaleX(1);
            card.setScaleY(1);
        });

        // 📄 Icon
        Label icon = new Label("\uD83D\uDCC1");
        icon.setStyle("-fx-font-size: 28px;");

        // Nom
        Label nom = new Label(d.getNomFichier());
        icon.setStyle("""
    -fx-font-size: 28px;
    -fx-text-fill: #FFC44D;
""");

        // Type
        Label type = new Label("Type : " + d.getTypeDocument());
        type.setStyle("-fx-text-fill: #64748b; -fx-font-size: 12px;");

        // Entreprise
        Label entreprise = new Label("Entreprise : " + nomEntreprise);
        entreprise.setStyle("-fx-text-fill: #64748b; -fx-font-size: 12px;");

        // Date upload
        Label date = new Label("Date : " + d.getDateUpload());
        date.setStyle("-fx-text-fill: #94a3b8; -fx-font-size: 11px;");

        // Description (limitée à 70 caractères)
        String desc = d.getDescription() == null ? "" : d.getDescription();
        if (desc.length() > 70) {
            desc = desc.substring(0, 70) + "...";
        }

        Label description = new Label(desc);
        description.setWrapText(true);
        description.setStyle("-fx-font-size: 12px;");

        // Badge statut
        Label badge = new Label(d.getStatut().toString());

        String color = switch (d.getStatut()) {
            case VALIDE -> " #1E7F5C";
            case EN_ATTENTE -> "#f59e0b";
            case REJETE -> "#ef4444";
        };

        badge.setStyle("""
        -fx-background-color: %s;
        -fx-text-fill: white;
        -fx-padding: 4 12;
        -fx-background-radius: 20;
        -fx-font-size: 11px;
    """.formatted(color));

        // Boutons actions
        HBox actions = new HBox(8);

        // Modifier
        Button edit = new Button("Modifier");
        edit.setStyle("""
        -fx-background-color:  #0B3C5D;
        -fx-text-fill: white;
        -fx-background-radius: 20;
        -fx-cursor: hand;
    """);
        edit.setOnAction(e -> update(d));

        // Voir OCR
        Button view = new Button("Voir");
        view.setStyle("""
        -fx-background-color:  #0B3C5D;
        -fx-text-fill: white;
        -fx-background-radius: 20;
        -fx-cursor: hand;
    """);
        view.setOnAction(e -> showOcr(d));

        // Supprimer
        Button delete = new Button("Supprimer");
        delete.setStyle("""
        -fx-background-color:#0B3C5D  ;
        -fx-text-fill: white;
        -fx-background-radius: 20;
        -fx-cursor: hand;
    """);
        delete.setOnAction(e -> delete(d));

        actions.getChildren().addAll(edit, view, delete);

        card.getChildren().addAll(
                icon,
                nom,
                type,
                entreprise,
                date,
                description,
                badge,
                actions
        );

        return card;
    }
    private void showOcr(Document d) {

        Dialog<Void> dialog = new Dialog<>();
        dialog.setTitle("Texte OCR");
        dialog.setHeaderText("Document : " + d.getNomFichier());

        TextArea textArea = new TextArea();
        textArea.setText(d.getTexteOcr());
        textArea.setWrapText(true);
        textArea.setEditable(false);

        textArea.setPrefWidth(600);
        textArea.setPrefHeight(400);

        dialog.getDialogPane().setContent(textArea);
        dialog.getDialogPane().getButtonTypes().add(ButtonType.CLOSE);

        dialog.showAndWait();
    }

    private void load() {
        try {
            masterList = FXCollections.observableArrayList(service.afficher());
            displayCards(masterList);
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
    private void displayCards(List<Document> docs) {

        cardContainer.getChildren().clear();

        for (Document d : docs) {
            cardContainer.getChildren().add(createModernCard(d));
        }
    }

    private void filter() {

        String search = txtSearch.getText().toLowerCase();

        List<Document> filtered = masterList.stream()
                .filter(d -> d.getNomFichier().toLowerCase().contains(search))
                .toList();

        displayCards(filtered);
    }

    private void delete(Document d) {
        try {
            service.supprimer(d);
            load();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private void update(Document selected) {

        Dialog<ButtonType> dialog = new Dialog<>();
        dialog.setTitle("Modifier Document");
        dialog.setHeaderText("ID : " + selected.getIdDocument());

        ButtonType saveBtn =
                new ButtonType("Enregistrer", ButtonBar.ButtonData.OK_DONE);

        dialog.getDialogPane().getButtonTypes()
                .addAll(saveBtn, ButtonType.CANCEL);

        TextField tfNom = new TextField(selected.getNomFichier());
        TextField tfChemin = new TextField(selected.getCheminFichier());
        TextField tfDescription = new TextField(selected.getDescription());

        ComboBox<Document.TypeDocument> cbType = new ComboBox<>();
        cbType.getItems().addAll(Document.TypeDocument.values());
        cbType.setValue(selected.getTypeDocument());

        ComboBox<Document.Statut> cbStatut = new ComboBox<>();
        cbStatut.getItems().addAll(Document.Statut.values());
        cbStatut.setValue(selected.getStatut());

        GridPane grid = new GridPane();
        grid.setHgap(10);
        grid.setVgap(10);

        grid.add(new Label("Nom:"), 0, 0);
        grid.add(tfNom, 1, 0);

        grid.add(new Label("Type:"), 0, 1);
        grid.add(cbType, 1, 1);

        grid.add(new Label("Statut:"), 0, 2);
        grid.add(cbStatut, 1, 2);

        grid.add(new Label("Chemin:"), 0, 3);
        grid.add(tfChemin, 1, 3);

        grid.add(new Label("Description:"), 0, 4);
        grid.add(tfDescription, 1, 4);

        dialog.getDialogPane().setContent(grid);

        dialog.showAndWait().ifPresent(result -> {

            if (result == saveBtn) {
                try {

                    selected.setNomFichier(tfNom.getText());
                    selected.setTypeDocument(cbType.getValue());
                    selected.setStatut(cbStatut.getValue());
                    selected.setCheminFichier(tfChemin.getText());
                    selected.setDescription(tfDescription.getText());

                    service.modifier(selected);
                    load();

                } catch (Exception ex) {
                    new Alert(Alert.AlertType.ERROR,
                            "Erreur dans les champs.")
                            .show();
                }
            }
        });
    }

    @FXML
    private void refresh() { load(); }

    @FXML
    private void retour(javafx.event.ActionEvent e) throws Exception {
        Parent root = FXMLLoader.load(getClass().getResource("/com/example/gestion_entreprises/MainMenu.fxml"));
        Stage stage = (Stage)((Node)e.getSource()).getScene().getWindow();
        stage.setScene(new Scene(root));
    }
}