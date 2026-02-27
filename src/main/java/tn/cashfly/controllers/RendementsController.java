package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.geometry.Pos;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import tn.cashfly.models.RendementInvestissement;
import tn.cashfly.services.ServiceRendement;

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
    private TextField txtIdInvestissementRDT;
    @FXML
    private TextField txtDateCalcul;
    @FXML
    private TextField txtGain;
    @FXML
    private TextField txtPerte;
    @FXML
    private TextField txtValeurPortefeuille;
    @FXML
    private Label lblTotalGain;
    @FXML
    private Label lblTotalPerte;
    @FXML
    private Label lblNetResult;

    private ServiceRendement serviceRendement;
    private ObservableList<RendementInvestissement> rendementList;
    private RendementInvestissement selectedRendement;

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        serviceRendement = new ServiceRendement();
        rendementList = FXCollections.observableArrayList();
        loadData();
    }

    private void loadData() {
        try {
            rendementList.clear();
            rendementList.addAll(serviceRendement.getAll());
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

        // Net result header
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

        // Date
        Label dateLabel = new Label("📅 " + r.getDateCalcul().toString());
        dateLabel.setStyle("-fx-font-size: 12px; -fx-text-fill: #7f8c8d;");

        Separator sep = new Separator();
        sep.setStyle("-fx-opacity: 0.15;");

        // Gain / Perte split
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

        // Actions
        HBox actions = new HBox(8);
        Button editBtn = new Button("Modifier");
        editBtn.setStyle("-fx-background-color: #5a5ce5; -fx-text-fill: white; " +
                "-fx-background-radius: 6; -fx-font-size: 11px; -fx-cursor: hand; -fx-padding: 5 10;");
        Button deleteBtn = new Button("Supprimer");
        deleteBtn.setStyle("-fx-background-color: #e74c3c22; -fx-text-fill: #e74c3c; " +
                "-fx-background-radius: 6; -fx-font-size: 11px; -fx-cursor: hand; -fx-padding: 5 10;");
        editBtn.setOnAction(e -> {
            selectedRendement = r;
            fillForm(r);
        });
        deleteBtn.setOnAction(e -> handleDeleteCard(r));
        actions.getChildren().addAll(editBtn, deleteBtn);

        card.getChildren().addAll(header, dateLabel, sep, gainPerte, actions);

        card.setOnMouseEntered(e -> card.setStyle("-fx-background-color: #f8f9ff; -fx-background-radius: 12; " +
                "-fx-effect: dropshadow(three-pass-box, rgba(90,92,229,0.15), 15, 0, 0, 6); " +
                "-fx-padding: 16; -fx-cursor: hand;"));
        card.setOnMouseExited(e -> card.setStyle("-fx-background-color: white; -fx-background-radius: 12; " +
                "-fx-effect: dropshadow(three-pass-box, rgba(0,0,0,0.08), 10, 0, 0, 4); " +
                "-fx-padding: 16; -fx-cursor: hand;"));

        return card;
    }

    private void fillForm(RendementInvestissement r) {
        txtIdInvestissementRDT.setText(String.valueOf(r.getIdInvestissement()));
        txtDateCalcul.setText(r.getDateCalcul().toString());
        txtGain.setText(String.valueOf(r.getGain()));
        txtPerte.setText(String.valueOf(r.getPerte()));
        txtValeurPortefeuille.setText(String.valueOf(r.getValeurPortefeuille()));
    }

    @FXML
    private void handleAddRendement(ActionEvent event) {
        try {
            RendementInvestissement r = new RendementInvestissement(
                    Integer.parseInt(txtIdInvestissementRDT.getText()),
                    Date.valueOf(txtDateCalcul.getText()),
                    new BigDecimal(txtGain.getText()),
                    new BigDecimal(txtPerte.getText()),
                    new BigDecimal(txtValeurPortefeuille.getText()));
            serviceRendement.add(r);
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
            selectedRendement.setIdInvestissement(Integer.parseInt(txtIdInvestissementRDT.getText()));
            selectedRendement.setDateCalcul(Date.valueOf(txtDateCalcul.getText()));
            selectedRendement.setGain(new BigDecimal(txtGain.getText()));
            selectedRendement.setPerte(new BigDecimal(txtPerte.getText()));
            selectedRendement.setValeurPortefeuille(new BigDecimal(txtValeurPortefeuille.getText()));
            serviceRendement.update(selectedRendement);
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
                serviceRendement.delete(r);
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
        txtIdInvestissementRDT.clear();
        txtDateCalcul.clear();
        txtGain.clear();
        txtPerte.clear();
        txtValeurPortefeuille.clear();
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
