package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.geometry.Pos;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import tn.cashfly.entities.Entreprise;
import tn.cashfly.entities.Investissement;
import tn.cashfly.services.ServiceInvest;
import tn.cashfly.services.EntrepriseService;
import tn.cashfly.session.UserSession;

import java.math.BigDecimal;
import java.net.URL;
import java.sql.Date;
import java.sql.SQLException;
import java.time.LocalDate;
import java.util.List;
import java.util.Optional;
import java.util.ResourceBundle;

public class InvestmentsController implements Initializable {

    @FXML
    private FlowPane cardsContainer;
    @FXML
    private TextField txtDescription;
    @FXML
    private TextField txtMontant;
    @FXML
    private TextField txtStatut;
    @FXML
    private TextField txtTaux;
    @FXML
    private TextField txtDuree;
    @FXML
    private ComboBox<Entreprise> cbEntreprise;
    @FXML
    private TextField txtSearch;
    @FXML
    private Label lblCount;

    private ServiceInvest serviceInvest;
    private EntrepriseService serviceEntreprise;
    private ObservableList<Investissement> investmentList;
    private Investissement selectedInvestissement;

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        serviceInvest = new ServiceInvest();
        serviceEntreprise = new EntrepriseService();
        investmentList = FXCollections.observableArrayList();
        
        loadData();
        loadEntreprises();

        if (txtSearch != null) {
            txtSearch.textProperty().addListener((obs, old, val) -> filterCards(val));
        }
    }

    private void loadEntreprises() {
        try {
            List<Entreprise> entreprises = serviceEntreprise.afficher();
            cbEntreprise.setItems(FXCollections.observableArrayList(entreprises));
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private void loadData() {
        try {
            investmentList.clear();
            investmentList.addAll(serviceInvest.afficher());
            renderCards(investmentList);
            if (lblCount != null)
                lblCount.setText(investmentList.size() + " investissements");
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Impossible de charger les investissements: " + e.getMessage());
        }
    }

    private void filterCards(String query) {
        if (query == null || query.isEmpty()) {
            renderCards(investmentList);
            return;
        }
        String lower = query.toLowerCase();
        ObservableList<Investissement> filtered = investmentList
                .filtered(i -> (i.getDescription() != null && i.getDescription().toLowerCase().contains(lower)) ||
                        (i.getStatut() != null && i.getStatut().toLowerCase().contains(lower)));
        renderCards(filtered);
    }

    private void renderCards(List<Investissement> list) {
        if (cardsContainer == null)
            return;
        cardsContainer.getChildren().clear();

        for (Investissement inv : list) {
            VBox card = createInvestmentCard(inv);
            cardsContainer.getChildren().add(card);
        }

        if (list.isEmpty()) {
            Label emptyLabel = new Label("Aucun investissement trouvé.");
            emptyLabel.setStyle("-fx-text-fill: #7f8c8d; -fx-font-size: 14px;");
            cardsContainer.getChildren().add(emptyLabel);
        }
    }

    private VBox createInvestmentCard(Investissement inv) {
        VBox card = new VBox(10);
        card.setPrefWidth(280);
        card.setStyle("-fx-background-color: white; -fx-background-radius: 12; " +
                "-fx-effect: dropshadow(three-pass-box, rgba(0,0,0,0.08), 10, 0, 0, 4); " +
                "-fx-padding: 18; -fx-cursor: hand;");

        HBox header = new HBox();
        header.setAlignment(Pos.CENTER_LEFT);

        String statusColor = getStatusColor(inv.getStatut());
        Label statusBadge = new Label(inv.getStatut());
        statusBadge.setStyle("-fx-background-color: " + statusColor + "22; " +
                "-fx-text-fill: " + statusColor + "; " +
                "-fx-background-radius: 20; -fx-padding: 3 10 3 10; -fx-font-size: 11px; -fx-font-weight: bold;");

        Region spacer = new Region();
        HBox.setHgrow(spacer, Priority.ALWAYS);

        Label amountLabel = new Label(String.format("%,.0f TND", inv.getMontant().doubleValue()));
        amountLabel.setStyle("-fx-font-size: 18px; -fx-font-weight: bold; -fx-text-fill: #2c3e50;");

        header.getChildren().addAll(statusBadge, spacer, amountLabel);

        Label desc = new Label(inv.getDescription());
        desc.setWrapText(true);
        desc.setStyle("-fx-font-size: 13px; -fx-text-fill: #34495e; -fx-font-weight: bold;");

        Separator sep = new Separator();
        sep.setStyle("-fx-opacity: 0.15;");

        GridPane stats = new GridPane();
        stats.setHgap(10);
        stats.setVgap(6);

        addStat(stats, 0, "📅 Date", inv.getDateInvestissement() != null ? inv.getDateInvestissement().toString() : "N/A");
        addStat(stats, 1, "📈 Taux", inv.getTauxRendementPrevu() + "%");
        addStat(stats, 2, "⏱ Durée", inv.getDureeMois() + " mois");
        addStat(stats, 3, "🏢 Entreprise #", String.valueOf(inv.getIdEntreprise()));

        HBox actions = new HBox(8);
        Button editBtn = new Button("Modifier");
        editBtn.getStyleClass().add("btn-primary");
        editBtn.setStyle("-fx-font-size: 11px; -fx-padding: 6 12;");
        
        Button deleteBtn = new Button("Supprimer");
        deleteBtn.getStyleClass().add("btn-danger");
        deleteBtn.setStyle("-fx-font-size: 11px; -fx-padding: 6 12;");

        editBtn.setOnAction(e -> {
            selectedInvestissement = inv;
            fillForm(inv);
        });
        deleteBtn.setOnAction(e -> handleDeleteCard(inv));

        actions.getChildren().addAll(editBtn, deleteBtn);

        card.getChildren().addAll(header, desc, sep, stats, actions);

        return card;
    }

    private void addStat(GridPane grid, int row, String label, String value) {
        Label lbl = new Label(label);
        lbl.setStyle("-fx-text-fill: #7f8c8d; -fx-font-size: 11px;");
        Label val = new Label(value);
        val.setStyle("-fx-text-fill: #2c3e50; -fx-font-size: 11px; -fx-font-weight: bold;");
        grid.add(lbl, 0, row);
        grid.add(val, 1, row);
    }

    private String getStatusColor(String statut) {
        if (statut == null) return "#7f8c8d";
        return switch (statut.toLowerCase()) {
            case "actif", "active", "en cours", "valide" -> "#2ecc71";
            case "terminé", "termine", "completed", "cloture" -> "#3498db";
            case "en_attente", "en attente" -> "#f39c12";
            case "annulé", "annule", "cancelled", "refuse" -> "#e74c3c";
            default -> "#7f8c8d";
        };
    }

    private void fillForm(Investissement i) {
        txtDescription.setText(i.getDescription());
        txtMontant.setText(String.valueOf(i.getMontant()));
        txtStatut.setText(i.getStatut());
        txtTaux.setText(String.valueOf(i.getTauxRendementPrevu()));
        txtDuree.setText(String.valueOf(i.getDureeMois()));
        
        // Select the enterprise in the ComboBox
        if (cbEntreprise != null && cbEntreprise.getItems() != null) {
            for (Entreprise e : cbEntreprise.getItems()) {
                if (e.getIdEntreprise() == i.getIdEntreprise()) {
                    cbEntreprise.setValue(e);
                    break;
                }
            }
        }
    }

    @FXML
    private void handleAdd(ActionEvent event) {
        try {
            if (cbEntreprise.getValue() == null) {
                showAlert(Alert.AlertType.WARNING, "Attention", "Veuillez choisir une entreprise.");
                return;
            }

            Integer investorId = UserSession.getUserId();
            if (investorId == null) {
                showAlert(Alert.AlertType.ERROR, "Session", "Session expirée. Veuillez vous reconnecter.");
                return;
            }

            Investissement i = new Investissement(
                    investorId,
                    cbEntreprise.getValue().getIdEntreprise(),
                    new BigDecimal(txtMontant.getText()),
                    Date.valueOf(LocalDate.now()),
                    txtStatut.getText(),
                    new BigDecimal(txtTaux.getText()),
                    Integer.parseInt(txtDuree.getText()),
                    txtDescription.getText());
            serviceInvest.ajouter(i);
            loadData();
            clearForm();
            showAlert(Alert.AlertType.INFORMATION, "Succès", "Investissement ajouté avec succès.");
        } catch (NumberFormatException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Veuillez vérifier les champs numériques.");
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de l'ajout: " + e.getMessage());
        }
    }

    @FXML
    private void handleUpdate(ActionEvent event) {
        if (selectedInvestissement == null) {
            showAlert(Alert.AlertType.WARNING, "Attention", "Veuillez sélectionner une carte à modifier.");
            return;
        }
        try {
            if (cbEntreprise.getValue() == null) {
                showAlert(Alert.AlertType.WARNING, "Attention", "Veuillez choisir une entreprise.");
                return;
            }

            Integer investorId = UserSession.getUserId();
            if (investorId == null) {
                showAlert(Alert.AlertType.ERROR, "Session", "Session expirée. Veuillez vous reconnecter.");
                return;
            }

            selectedInvestissement.setIdInvestisseur(investorId);
            selectedInvestissement.setIdEntreprise(cbEntreprise.getValue().getIdEntreprise());
            selectedInvestissement.setMontant(new BigDecimal(txtMontant.getText()));
            selectedInvestissement.setStatut(txtStatut.getText());
            selectedInvestissement.setTauxRendementPrevu(new BigDecimal(txtTaux.getText()));
            selectedInvestissement.setDureeMois(Integer.parseInt(txtDuree.getText()));
            selectedInvestissement.setDescription(txtDescription.getText());
            serviceInvest.modifier(selectedInvestissement);
            loadData();
            clearForm();
            showAlert(Alert.AlertType.INFORMATION, "Succès", "Investissement mis à jour.");
        } catch (NumberFormatException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Veuillez vérifier les champs numériques.");
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la mise à jour: " + e.getMessage());
        }
    }

    @FXML
    private void handleDelete(ActionEvent event) {
        if (selectedInvestissement == null) {
            showAlert(Alert.AlertType.WARNING, "Attention", "Cliquez sur Supprimer dans une carte.");
            return;
        }
        handleDeleteCard(selectedInvestissement);
    }

    private void handleDeleteCard(Investissement inv) {
        Alert confirm = new Alert(Alert.AlertType.CONFIRMATION);
        confirm.setTitle("Confirmation");
        confirm.setHeaderText("Supprimer l'investissement ?");
        confirm.setContentText("Cette action est irréversible.");
        Optional<ButtonType> result = confirm.showAndWait();
        if (result.isPresent() && result.get() == ButtonType.OK) {
            try {
                serviceInvest.supprimer(inv);
                loadData();
                clearForm();
            } catch (Exception e) {
                showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la suppression: " + e.getMessage());
            }
        }
    }

    @FXML
    private void handleClear(ActionEvent event) {
        clearForm();
        selectedInvestissement = null;
    }

    private void clearForm() {
        txtDescription.clear();
        txtMontant.clear();
        txtStatut.clear();
        txtTaux.clear();
        txtDuree.clear();
        if (cbEntreprise != null) cbEntreprise.setValue(null);
        selectedInvestissement = null;
    }

    private void showAlert(Alert.AlertType type, String title, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(content);
        alert.showAndWait();
    }
}
