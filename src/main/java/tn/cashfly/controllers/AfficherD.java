package tn.cashfly.controllers;

import tn.cashfly.entities.Document;
import tn.cashfly.entities.Entreprise;
import tn.cashfly.services.DocumentService;
import tn.cashfly.services.EntrepriseService;
import tn.cashfly.services.OCRService;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.*;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import javafx.stage.Stage;

import tn.cashfly.DashboardController;
import tn.cashfly.session.UserSession;
import tn.cashfly.tools.NotificationService;
import tn.cashfly.controllers.NotificationType;

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
            Integer userId = UserSession.getUserId();
            List<Entreprise> allEntreprises = entrepriseService.afficher();
            if (userId != null) {
                entreprises = allEntreprises.stream()
                        .filter(e -> e.getIdProprietaire() == userId)
                        .collect(Collectors.toList());
            } else {
                entreprises = allEntreprises;
            }
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

        btn.setStyle("-fx-background-color: #f1f5f9; -fx-text-fill: #334155; -fx-background-radius: 999; -fx-border-radius: 999; -fx-border-color: #e2e8f0; -fx-border-width: 1; -fx-padding: 6 18; -fx-font-size: 12px; -fx-cursor: hand;");

        btn.setOnAction(e -> {
            // Update styles
            entrepriseContainer.getChildren().forEach(n -> {
                if (n instanceof Button) {
                    n.setStyle("-fx-background-color: #f1f5f9; -fx-text-fill: #334155; -fx-background-radius: 999; -fx-border-radius: 999; -fx-border-color: #e2e8f0; -fx-border-width: 1; -fx-padding: 6 18; -fx-font-size: 12px; -fx-cursor: hand;");
                }
            });
            btn.setStyle("-fx-background-color: #013b63; -fx-text-fill: white; -fx-background-radius: 999; -fx-border-radius: 999; -fx-border-color: transparent; -fx-padding: 6 18; -fx-font-size: 12px; -fx-font-weight: bold; -fx-cursor: hand;");

            if (id == -1) {
                displayCards(masterList);
            } else {
                filterByEntreprise(id);
            }
        });

        // Set initial active style for "Tous" button
        if (id == -1) {
            btn.setStyle("-fx-background-color: #013b63; -fx-text-fill: white; -fx-background-radius: 999; -fx-border-radius: 999; -fx-border-color: transparent; -fx-padding: 6 18; -fx-font-size: 12px; -fx-font-weight: bold; -fx-cursor: hand;");
        }

        return btn;
    }
    private void filterByEntreprise(int idEntreprise) {

        List<Document> filtered = masterList.stream()
                .filter(d -> d.getIdEntreprise() == idEntreprise)
                .collect(Collectors.toList());

        displayCards(filtered);
    }

    private void load() {
        try {
            Integer userId = UserSession.getUserId();
            List<Document> docs = service.afficher();

            if (userId != null && entreprises != null) {
                List<Integer> ownedEntrepriseIds = entreprises.stream()
                        .filter(e -> e.getIdProprietaire() == userId)
                        .map(Entreprise::getIdEntreprise)
                        .collect(Collectors.toList());

                docs = docs.stream()
                        .filter(d -> ownedEntrepriseIds.contains(d.getIdEntreprise()))
                        .collect(Collectors.toList());
            }

            masterList = FXCollections.observableArrayList(docs);
            displayCards(docs);
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private void displayCards(List<Document> docs) {
        cardContainer.getChildren().clear();
        docs.forEach(d -> cardContainer.getChildren().add(createModernCard(d)));
    }

    private void filter() {
        String search = txtSearch.getText().toLowerCase();
        List<Document> filtered = masterList.stream()
                .filter(d -> d.getNomFichier().toLowerCase().contains(search))
                .collect(Collectors.toList());
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

        card.setStyle("-fx-background-color: white; -fx-background-radius: 15; -fx-padding: 20; -fx-effect: dropshadow(gaussian, rgba(0,0,0,0.08), 15,0,0,4);");

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
        icon.setStyle("-fx-font-size: 28px; -fx-text-fill: #FFC44D;");

        // Nom
        Label nom = new Label(d.getNomFichier());
        nom.setStyle("-fx-font-weight: bold; -fx-font-size: 14px;");

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

        badge.setStyle("-fx-background-color: %s; -fx-text-fill: white; -fx-padding: 4 12; -fx-background-radius: 20; -fx-font-size: 11px;".formatted(color));

        // Boutons actions
        HBox actions = new HBox(10);

        Button edit = new Button("Modifier");
        edit.getStyleClass().addAll("btn-primary", "btn-xs", "doc-action");
        edit.setMinWidth(80);
        edit.setOnAction(e -> update(d));

        Button view = new Button("Voir");
        view.getStyleClass().addAll("btn-secondary", "btn-xs", "doc-action");
        view.setMinWidth(70);
        view.setOnAction(e -> showOcr(d));

        Button delete = new Button("Supprimer");
        delete.getStyleClass().addAll("btn-danger", "btn-xs", "doc-action");
        delete.setMinWidth(90);
        delete.setOnAction(e -> {
            try {
                String fileName = d.getNomFichier();
                service.supprimer(d);
                NotificationService.warn("Document supprimé : " + fileName);
                load();
            } catch (SQLException ex) {
                ex.printStackTrace();
                NotificationService.error("Erreur lors de la suppression du document.");
            }
        });

        actions.getChildren().addAll(edit, view, delete);

        card.getChildren().addAll(icon, nom, type, entreprise, date, description, badge, actions);
        return card;
    }

    private void update(Document d) {
        if (DashboardController.getInstance() != null) {
            DashboardController.getInstance().showModifierDocument(d);
        }
    }

    private void showOcr(Document d) {
        String texte = d.getTexteOcr();

        if (texte == null || texte.isBlank()) {
            if (d.getCheminFichier() != null && !d.getCheminFichier().isBlank()) {
                OCRService ocrService = new OCRService();
                try {
                    texte = ocrService.extractText(d.getCheminFichier());
                    d.setTexteOcr(texte);
                    try {
                        service.modifier(d);
                    } catch (SQLException ex) {
                        ex.printStackTrace();
                    }
                } catch (Exception ocrEx) {
                    texte = "Erreur OCR: " + ocrEx.getMessage();
                }
            } else {
                texte = "Chemin de fichier introuvable pour ce document.";
            }
        }

        Dialog<Void> dialog = new Dialog<>();
        dialog.setTitle("Texte Extrait (OCR) - " + d.getNomFichier());
        dialog.setHeaderText("Contenu du document analysé par OCR");

        ButtonType closeButton = new ButtonType("Fermer", ButtonBar.ButtonData.CANCEL_CLOSE);
        dialog.getDialogPane().getButtonTypes().add(closeButton);

        VBox content = new VBox(10);
        content.setPadding(new javafx.geometry.Insets(20));
        content.setPrefWidth(500);

        TextArea textArea = new TextArea();
        textArea.setText(texte != null && !texte.isBlank()
                ? texte
                : "Aucun texte n'a pu être extrait de ce document.");
        textArea.setWrapText(true);
        textArea.setEditable(false);
        textArea.setPrefRowCount(15);
        textArea.setStyle("-fx-font-family: 'Segoe UI'; -fx-font-size: 13px; -fx-control-inner-background: #f8f9fa;");

        Label infoLabel = new Label("Type: " + d.getTypeDocument() + " | Date: " + d.getDateUpload());
        infoLabel.setStyle("-fx-text-fill: #64748b; -fx-font-size: 11px;");

        content.getChildren().addAll(textArea, infoLabel);
        dialog.getDialogPane().setContent(content);

        // Apply styles to the dialog pane if possible
        dialog.getDialogPane().getStylesheets().add(getClass().getResource("/style.css").toExternalForm());
        
        dialog.showAndWait();
    }

    @FXML
    private void showAjouter(javafx.event.ActionEvent event) {
        if (DashboardController.getInstance() != null) {
            DashboardController.getInstance().showAjouterDocument();
        }
    }

    private void showAjouter() {
        if (DashboardController.getInstance() != null) {
            DashboardController.getInstance().showAjouterDocument();
        }
    }

    @FXML
    private void refresh() {
        load();
    }

    @FXML
    private void retour(javafx.event.ActionEvent event) {
        if (DashboardController.getInstance() != null) {
            DashboardController.getInstance().showHome();
        }
    }
}
