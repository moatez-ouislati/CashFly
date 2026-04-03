package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.geometry.Pos;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import tn.cashfly.entities.Investissement;
import tn.cashfly.entities.RendementInvestissement;
import tn.cashfly.services.ServiceRendement;
import tn.cashfly.services.ServiceInvest;

import java.math.BigDecimal;
import java.net.URL;
import java.sql.Date;
import java.util.List;
import java.util.Optional;
import java.util.ResourceBundle;

public class RendementsController implements Initializable {

    @FXML
    private FlowPane cardsContainer;
    @FXML
    private ComboBox<Investissement> cbInvestissement;
    @FXML
    private TextField txtDateCalcul;
    @FXML
    private TextField txtGain;
    @FXML
    private TextField txtPerte;
    @FXML
    private Label lblTotalGain;
    @FXML
    private Label lblTotalPerte;
    @FXML
    private Label lblNetResult;

    private ServiceRendement serviceRendement;
    private ServiceInvest serviceInvest;
    private ObservableList<RendementInvestissement> rendementList;
    private RendementInvestissement selectedRendement;

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        serviceRendement = new ServiceRendement();
        serviceInvest = new ServiceInvest();
        rendementList = FXCollections.observableArrayList();
        
        loadData();
        loadInvestments();
    }

    private void loadInvestments() {
        try {
            List<Investissement> list = serviceInvest.afficher();
            cbInvestissement.setItems(FXCollections.observableArrayList(list));
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void loadData() {
        try {
            rendementList.clear();
            rendementList.addAll(serviceRendement.afficher());
            renderCards(rendementList);
            updateSummary();
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Impossible de charger les rendements: " + e.getMessage());
        }
    }

    private void updateSummary() {
        BigDecimal totalGain = rendementList.stream().map(RendementInvestissement::getGain)
                .reduce(BigDecimal.ZERO, BigDecimal::add);
        BigDecimal totalPerte = rendementList.stream().map(RendementInvestissement::getPerte)
                .reduce(BigDecimal.ZERO, BigDecimal::add);
        BigDecimal net = totalGain.subtract(totalPerte);

        if (lblTotalGain != null)
            lblTotalGain.setText(String.format("+%,.2f TND", totalGain.doubleValue()));
        if (lblTotalPerte != null)
            lblTotalPerte.setText(String.format("-%,.2f TND", totalPerte.doubleValue()));
        if (lblNetResult != null) {
            lblNetResult.setText(String.format("%,.2f TND", net.doubleValue()));
            lblNetResult.setStyle(net.compareTo(BigDecimal.ZERO) >= 0
                    ? "-fx-text-fill: #2ecc71; -fx-font-size: 22px; -fx-font-weight: bold;"
                    : "-fx-text-fill: #e74c3c; -fx-font-size: 22px; -fx-font-weight: bold;");
        }
    }

    private void renderCards(List<RendementInvestissement> list) {
        if (cardsContainer == null)
            return;
        cardsContainer.getChildren().clear();

        for (RendementInvestissement r : list) {
            VBox card = createRendementCard(r);
            cardsContainer.getChildren().add(card);
        }

        if (list.isEmpty()) {
            Label emptyLabel = new Label("Aucun rendement enregistré.");
            emptyLabel.setStyle("-fx-text-fill: #7f8c8d; -fx-font-size: 14px;");
            cardsContainer.getChildren().add(emptyLabel);
        }
    }

    private VBox createRendementCard(RendementInvestissement r) {
        VBox card = new VBox(12);
        card.setPrefWidth(260);
        card.setStyle("-fx-background-color: white; -fx-background-radius: 12; " +
                "-fx-effect: dropshadow(three-pass-box, rgba(0,0,0,0.08), 10, 0, 0, 4); " +
                "-fx-padding: 16; -fx-cursor: hand;");

        BigDecimal net = r.getGain().subtract(r.getPerte());
        boolean isPositive = net.compareTo(BigDecimal.ZERO) >= 0;
        String netColor = isPositive ? "#2ecc71" : "#e74c3c";
        String netStr = (isPositive ? "+" : "") + String.format("%,.2f", net.doubleValue()) + " TND";

        HBox header = new HBox();
        header.setAlignment(Pos.CENTER_LEFT);
        Label netLabel = new Label(netStr);
        netLabel.setStyle("-fx-font-size: 20px; -fx-font-weight: bold; -fx-text-fill: " + netColor + ";");

        Region spacer = new Region();
        HBox.setHgrow(spacer, Priority.ALWAYS);

        Label idLabel = new Label("Inv. #" + r.getIdInvestissement());
        idLabel.setStyle("-fx-font-size: 11px; -fx-text-fill: #7f8c8d; -fx-background-color: #f4f6f9; " +
                "-fx-background-radius: 10; -fx-padding: 3 8;");
        header.getChildren().addAll(netLabel, spacer, idLabel);

        Label dateLabel = new Label("📅 " + (r.getDateCalcul() != null ? r.getDateCalcul().toString() : "N/A"));
        dateLabel.setStyle("-fx-font-size: 12px; -fx-text-fill: #7f8c8d;");

        Separator sep = new Separator();
        sep.setStyle("-fx-opacity: 0.15;");

        HBox gainPerte = new HBox(10);
        gainPerte.setAlignment(Pos.CENTER_LEFT);

        VBox gainBox = new VBox(2);
        Label gainLbl = new Label("Gain");
        gainLbl.setStyle("-fx-font-size: 10px; -fx-text-fill: #7f8c8d;");
        Label gainVal = new Label(String.format("+%,.2f", r.getGain().doubleValue()));
        gainVal.setStyle("-fx-font-size: 13px; -fx-font-weight: bold; -fx-text-fill: #2ecc71;");
        gainBox.getChildren().addAll(gainLbl, gainVal);

        VBox perteBox = new VBox(2);
        Label perteLbl = new Label("Perte");
        perteLbl.setStyle("-fx-font-size: 10px; -fx-text-fill: #7f8c8d;");
        Label perteVal = new Label(String.format("-%,.2f", r.getPerte().doubleValue()));
        perteVal.setStyle("-fx-font-size: 13px; -fx-font-weight: bold; -fx-text-fill: #e74c3c;");
        perteBox.getChildren().addAll(perteLbl, perteVal);

        Region spacer2 = new Region();
        HBox.setHgrow(spacer2, Priority.ALWAYS);

        VBox portBox = new VBox(2);
        Label portLbl = new Label("Portefeuille");
        portLbl.setStyle("-fx-font-size: 10px; -fx-text-fill: #7f8c8d;");
        Label portVal = new Label(String.format("%,.2f", r.getValeurPortefeuille().doubleValue()));
        portVal.setStyle("-fx-font-size: 13px; -fx-font-weight: bold; -fx-text-fill: #5a5ce5;");
        portBox.getChildren().addAll(portLbl, portVal);

        gainPerte.getChildren().addAll(gainBox, spacer2, perteBox, new Label("  "), portBox);

        HBox actions = new HBox(8);
        Button editBtn = new Button("Modifier");
        editBtn.getStyleClass().add("btn-primary");
        editBtn.setStyle("-fx-font-size: 11px; -fx-padding: 5 10;");

        Button deleteBtn = new Button("Supprimer");
        deleteBtn.getStyleClass().add("btn-danger");
        deleteBtn.setStyle("-fx-font-size: 11px; -fx-padding: 5 10;");

        editBtn.setOnAction(e -> {
            selectedRendement = r;
            fillForm(r);
        });
        deleteBtn.setOnAction(e -> handleDeleteCard(r));
        actions.getChildren().addAll(editBtn, deleteBtn);

        card.getChildren().addAll(header, dateLabel, sep, gainPerte, actions);

        return card;
    }

    private void fillForm(RendementInvestissement r) {
        // Select the investment in the ComboBox
        if (cbInvestissement != null && cbInvestissement.getItems() != null) {
            for (Investissement inv : cbInvestissement.getItems()) {
                if (inv.getIdInvestissement() == r.getIdInvestissement()) {
                    cbInvestissement.setValue(inv);
                    break;
                }
            }
        }
        txtDateCalcul.setText(r.getDateCalcul().toString());
        txtGain.setText(String.valueOf(r.getGain()));
        txtPerte.setText(String.valueOf(r.getPerte()));
    }

    @FXML
    private void handleAddRendement(ActionEvent event) {
        try {
            if (cbInvestissement.getValue() == null) {
                showAlert(Alert.AlertType.WARNING, "Attention", "Veuillez choisir un investissement.");
                return;
            }
            RendementInvestissement r = new RendementInvestissement(
                    cbInvestissement.getValue().getIdInvestissement(),
                    Date.valueOf(txtDateCalcul.getText()),
                    new BigDecimal(txtGain.getText()),
                    new BigDecimal(txtPerte.getText()),
                    BigDecimal.ZERO); // Valeur portefeuille supprimée du UI
            serviceRendement.ajouter(r);
            loadData();
            clearForm();
            showAlert(Alert.AlertType.INFORMATION, "Succès", "Rendement ajouté.");
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de l'ajout: " + e.getMessage());
        }
    }

    @FXML
    private void handleUpdateRendement(ActionEvent event) {
        if (selectedRendement == null) {
            showAlert(Alert.AlertType.WARNING, "Attention", "Cliquez sur Modifier dans une carte.");
            return;
        }
        try {
            if (cbInvestissement.getValue() == null) {
                showAlert(Alert.AlertType.WARNING, "Attention", "Veuillez choisir un investissement.");
                return;
            }
            selectedRendement.setIdInvestissement(cbInvestissement.getValue().getIdInvestissement());
            selectedRendement.setDateCalcul(Date.valueOf(txtDateCalcul.getText()));
            selectedRendement.setGain(new BigDecimal(txtGain.getText()));
            selectedRendement.setPerte(new BigDecimal(txtPerte.getText()));
            selectedRendement.setValeurPortefeuille(BigDecimal.ZERO); // Valeur portefeuille supprimée du UI
            serviceRendement.modifier(selectedRendement);
            loadData();
            clearForm();
            showAlert(Alert.AlertType.INFORMATION, "Succès", "Rendement mis à jour.");
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur: " + e.getMessage());
        }
    }

    @FXML
    private void handleDeleteRendement(ActionEvent event) {
        if (selectedRendement == null) {
            showAlert(Alert.AlertType.WARNING, "Attention", "Cliquez Supprimer sur une carte.");
            return;
        }
        handleDeleteCard(selectedRendement);
    }

    private void handleDeleteCard(RendementInvestissement r) {
        Alert confirm = new Alert(Alert.AlertType.CONFIRMATION);
        confirm.setTitle("Confirmation");
        confirm.setHeaderText("Supprimer ce rendement ?");
        confirm.setContentText("Cette action est irréversible.");
        Optional<ButtonType> result = confirm.showAndWait();
        if (result.isPresent() && result.get() == ButtonType.OK) {
            try {
                serviceRendement.supprimer(r);
                loadData();
                clearForm();
            } catch (Exception e) {
                showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la suppression: " + e.getMessage());
            }
        }
    }

    @FXML
    private void handleClearRendement(ActionEvent event) {
        clearForm();
    }

    private void clearForm() {
        if (cbInvestissement != null) cbInvestissement.setValue(null);
        txtDateCalcul.clear();
        txtGain.clear();
        txtPerte.clear();
        selectedRendement = null;
    }

    private void showAlert(Alert.AlertType type, String title, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(content);
        alert.showAndWait();
    }
}
