package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.ComboBox;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.FlowPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import tn.cashfly.entities.OPÉRATIONS;
import tn.cashfly.entities.TRÉSORERIE;
import tn.cashfly.session.UserSession;

import java.sql.SQLException;
import java.time.LocalDateTime;
import java.util.Collections;
import java.util.List;
import java.util.stream.Collectors;

public class OperationUIController {

    @FXML
    private FlowPane operationCardsContainer;

    @FXML
    private ComboBox<String> typeFilterBox;
    @FXML
    private TextField minMontantField;
    @FXML
    private TextField maxMontantField;
    @FXML
    private TextField keywordField;

    @FXML
    private ComboBox<String> typeBox;
    @FXML
    private TextField montantField;
    @FXML
    private TextField categorieField;
    @FXML
    private TextField descriptionField;

    @FXML
    private Label currentContextLabel;

    private final OperationController operationController = new OperationController();
    private final TresorerieController tresorerieController = new TresorerieController();
    private final ObservableList<OPÉRATIONS> data = FXCollections.observableArrayList();

    private OPÉRATIONS selectedOperation;
    private VBox selectedOperationCard;

    @FXML
    public void initialize() {
        typeBox.setItems(FXCollections.observableArrayList("revenu", "depense"));
        typeFilterBox.setItems(FXCollections.observableArrayList("revenu", "depense"));

        String entreprise = UserSession.getCurrentEntrepriseName();
        Integer tresId = UserSession.getCurrentTresorerieId();
        if (entreprise != null && tresId != null) {
            currentContextLabel.setText("Entreprise : " + entreprise + " • Trésorerie #" + tresId);
        } else if (entreprise != null) {
            currentContextLabel.setText("Entreprise : " + entreprise + " • aucune trésorerie sélectionnée");
        } else {
            currentContextLabel.setText("Aucune trésorerie sélectionnée");
        }

        refreshTable();
    }

    private void refreshTable() {
        try {
            Integer entrepriseId = UserSession.getCurrentEntrepriseId();
            if (entrepriseId == null) {
                data.clear();
                renderCards(Collections.emptyList());
                showInfo("Veuillez d'abord choisir une entreprise dans l'onglet Entreprises.");
                return;
            }

            List<OPÉRATIONS> all = operationController.getAllOperations();
            List<OPÉRATIONS> filtered = filterForCurrentEntreprise(all);
            data.setAll(filtered);
            renderCards(filtered);
        } catch (SQLException e) {
            showError("Erreur lors du chargement des opérations", e);
        }
    }

    private void populateForm(OPÉRATIONS op) {
        typeBox.setValue(op.getType());
        montantField.setText(String.valueOf(op.getMontant()));
        categorieField.setText(op.getCategorie());
        descriptionField.setText(op.getDescription());
    }

    @FXML
    private void onFilterByType() {
        String type = typeFilterBox.getValue();
        try {
            List<OPÉRATIONS> list = operationController.filterOperationsByType(type);
            List<OPÉRATIONS> filtered = filterForCurrentEntreprise(list);
            data.setAll(filtered);
            renderCards(filtered);
        } catch (SQLException e) {
            showError("Erreur lors du filtrage par type", e);
        }
    }

    @FXML
    private void onFilterByMontant() {
        try {
            double min = (minMontantField.getText() == null || minMontantField.getText().isBlank())
                    ? Double.MIN_VALUE
                    : Double.parseDouble(minMontantField.getText());
            double max = (maxMontantField.getText() == null || maxMontantField.getText().isBlank())
                    ? Double.MAX_VALUE
                    : Double.parseDouble(maxMontantField.getText());
            List<OPÉRATIONS> list = operationController.filterOperationsByAmountRange(min, max);
            List<OPÉRATIONS> filtered = filterForCurrentEntreprise(list);
            data.setAll(filtered);
            renderCards(filtered);
        } catch (NumberFormatException e) {
            showInfo("Montant min/max doivent être des nombres.");
        } catch (SQLException e) {
            showError("Erreur lors du filtrage par montant", e);
        }
    }

    @FXML
    private void onSearchByKeyword() {
        String keyword = keywordField.getText();
        try {
            List<OPÉRATIONS> list = operationController.searchOperationsByKeyword(keyword);
            List<OPÉRATIONS> filtered = filterForCurrentEntreprise(list);
            data.setAll(filtered);
            renderCards(filtered);
        } catch (SQLException e) {
            showError("Erreur lors de la recherche", e);
        }
    }

    @FXML
    private void onAdd() {
        try {
            OPÉRATIONS op = buildFromForm(null);
            if (op == null) return;
            operationController.createOperation(
                    op.getTresorerie(),
                    op.getType(),
                    op.getMontant(),
                    op.getCategorie(),
                    op.getDescription()
            );
            refreshTable();
            clearForm();
        } catch (SQLException e) {
            showError("Erreur lors de l'ajout", e);
        }
    }

    @FXML
    private void onUpdate() {
        OPÉRATIONS selected = selectedOperation;
        if (selected == null) {
            showInfo("Veuillez sélectionner une opération à modifier.");
            return;
        }
        try {
            OPÉRATIONS op = buildFromForm(selected.getIdOperation());
            if (op == null) return;
            op.setIdOperation(selected.getIdOperation());
            operationController.updateOperation(op);
            refreshTable();
        } catch (SQLException e) {
            showError("Erreur lors de la mise à jour", e);
        }
    }

    @FXML
    private void onDelete() {
        OPÉRATIONS selected = selectedOperation;
        if (selected == null) {
            showInfo("Veuillez sélectionner une opération à supprimer.");
            return;
        }
        try {
            operationController.deleteOperation(selected.getIdOperation());
            refreshTable();
            clearForm();
        } catch (SQLException e) {
            showError("Erreur lors de la suppression", e);
        }
    }

    private OPÉRATIONS buildFromForm(Integer existingId) {
        try {
            Integer fromSession = UserSession.getCurrentTresorerieId();
            if (fromSession == null) {
                showInfo("Veuillez choisir une trésorerie dans l'onglet Trésorerie d'abord.");
                return null;
            }
            int idTres = fromSession;
            String type = typeBox.getValue();
            double montant = Double.parseDouble(montantField.getText());
            String categorie = categorieField.getText();
            String description = descriptionField.getText();

            if (type == null || type.isBlank()) {
                showInfo("Le type (revenu/depense) est obligatoire.");
                return null;
            }

            TRÉSORERIE t = tresorerieController.getTresorerieById(idTres);
            if (t == null) {
                showInfo("Aucune trésorerie trouvée pour l'ID : " + idTres);
                return null;
            }

            OPÉRATIONS op;
            if (existingId == null) {
                op = new OPÉRATIONS(t, type, montant, categorie, description);
            } else {
                op = new OPÉRATIONS(existingId, t, type, montant, categorie, description, LocalDateTime.now());
            }
            return op;
        } catch (NumberFormatException e) {
            showInfo("Vérifiez les valeurs numériques (ID trésorerie, montant).");
            return null;
        } catch (SQLException e) {
            showError("Erreur lors de la récupération de la trésorerie", e);
            return null;
        }
    }

    private void clearForm() {
        typeBox.getSelectionModel().clearSelection();
        montantField.clear();
        categorieField.clear();
        descriptionField.clear();
        selectedOperation = null;
    }

    private void renderCards(List<OPÉRATIONS> operations) {
        operationCardsContainer.getChildren().clear();

        for (OPÉRATIONS op : operations) {
            VBox card = createCard(op);
            operationCardsContainer.getChildren().add(card);
        }
    }

    private VBox createCard(OPÉRATIONS op) {
        VBox card = new VBox(4);
        card.setPadding(new Insets(10));
        card.setSpacing(4);
        card.setStyle("""
                -fx-background-color: white;
                -fx-border-color: #e2e8f0;
                -fx-border-radius: 8;
                -fx-background-radius: 8;
                -fx-effect: dropshadow(gaussian, rgba(15,23,42,0.08), 8, 0.2, 0, 2);
                """);
        card.getStyleClass().add("card");

        String type = op.getType() != null ? op.getType() : "";
        String typeColor = "revenu".equalsIgnoreCase(type) ? "#16a34a" : "#dc2626";

        Label title = new Label(type.toUpperCase() + " • " + String.format("%.2f", op.getMontant()) + " TND");
        title.setStyle("-fx-font-weight: bold; -fx-text-fill: #0f172a;");

        Label categorie = new Label(op.getCategorie() != null ? op.getCategorie() : "(aucune catégorie)");
        categorie.setStyle("-fx-text-fill: #6b7280;");

        String descText = op.getDescription() != null && !op.getDescription().isBlank()
                ? op.getDescription()
                : "Aucune description";
        Label description = new Label(descText);
        description.setWrapText(true);
        description.setStyle("-fx-text-fill: #4b5563;");

        String meta = "";
        TRÉSORERIE t = op.getTresorerie();
        if (t != null) {
            meta += "Trésorerie #" + t.getIdTresorerie();
        }
        LocalDateTime date = op.getDateOperation();
        if (date != null) {
            if (!meta.isEmpty()) meta += " • ";
            meta += date.toString();
        }
        if (!meta.isEmpty()) {
            Label metaLabel = new Label(meta);
            metaLabel.setStyle("-fx-text-fill: #9ca3af; -fx-font-size: 11;");
            card.getChildren().add(metaLabel);
        }

        HBox header = new HBox(8, title);
        header.setPadding(new Insets(0, 0, 4, 0));

        Label typeBadge = new Label(type.toUpperCase());
        typeBadge.setStyle(
                "-fx-background-color: " + typeColor + "1A;" +
                "-fx-text-fill: " + typeColor + ";" +
                "-fx-padding: 2 8 2 8;" +
                "-fx-background-radius: 999;"
        );
        header.getChildren().add(typeBadge);

        card.getChildren().addAll(header, categorie, description);

        card.setOnMouseClicked(e -> {
            if (selectedOperationCard != null) {
                selectedOperationCard.getStyleClass().remove("card-selected");
            }
            card.getStyleClass().add("card-selected");
            selectedOperationCard = card;

            selectedOperation = op;
            populateForm(op);
        });

        return card;
    }

    private void showError(String message, Exception e) {
        e.printStackTrace();
        Alert alert = new Alert(Alert.AlertType.ERROR);
        alert.setHeaderText(message);
        alert.setContentText(e.getMessage());
        alert.showAndWait();
    }

    private void showInfo(String message) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }

    private List<OPÉRATIONS> filterForCurrentEntreprise(List<OPÉRATIONS> input) {
        Integer entrepriseId = UserSession.getCurrentEntrepriseId();
        if (entrepriseId == null) {
            return input;
        }
        return input.stream()
                .filter(op -> {
                    TRÉSORERIE t = op.getTresorerie();
                    return t != null && t.getIdEntreprise() == entrepriseId;
                })
                .collect(Collectors.toList());
    }
}

