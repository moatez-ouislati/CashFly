package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.geometry.Insets;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.ComboBox;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.FlowPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.stage.Modality;
import javafx.stage.Stage;
import tn.cashfly.entities.OPÉRATIONS;
import tn.cashfly.entities.TRÉSORERIE;
import tn.cashfly.services.ExchangeRateService;
import tn.cashfly.session.UserSession;
import tn.cashfly.DashboardController;

import java.sql.SQLException;
import java.time.LocalDateTime;
import java.util.Collections;
import java.util.List;
import java.util.stream.Collectors;

public class OperationUIController {

    @FXML
    private VBox revenuCardsContainer;

    @FXML
    private VBox depenseCardsContainer;

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
    private ComboBox<String> operationCurrencyBox;
    @FXML
    private TextField montantField;
    @FXML
    private TextField categorieField;
    @FXML
    private TextField descriptionField;

    @FXML
    private Label currentContextLabel;

    @FXML
    private TextField fromCurrencyField;
    @FXML
    private TextField toCurrencyField;
    @FXML
    private Label rateResultLabel;

    @FXML
    private Label kycStatusLabel;

    private final OperationController operationController = new OperationController();
    private final TresorerieController tresorerieController = new TresorerieController();
    private final ExchangeRateService exchangeRateService = new ExchangeRateService();
    private final ObservableList<OPÉRATIONS> data = FXCollections.observableArrayList();

    private OPÉRATIONS selectedOperation;
    private VBox selectedOperationCard;

    @FXML
    public void initialize() {
        typeBox.setItems(FXCollections.observableArrayList("revenu", "depense"));
        typeFilterBox.setItems(FXCollections.observableArrayList("revenu", "depense"));
        operationCurrencyBox.setItems(FXCollections.observableArrayList("TND", "EUR", "USD"));
        operationCurrencyBox.setValue("TND");

        String entreprise = UserSession.getCurrentEntrepriseName();
        Integer tresId = UserSession.getCurrentTresorerieId();
        if (entreprise != null && tresId != null) {
            currentContextLabel.setText("Entreprise : " + entreprise + " • Trésorerie #" + tresId);
        } else if (entreprise != null) {
            currentContextLabel.setText("Entreprise : " + entreprise + " • aucune trésorerie sélectionnée");
        } else {
            currentContextLabel.setText("Aucune trésorerie sélectionnée");
        }

        // Live search/filter: run directly when user types or changes filters (no button click)
        keywordField.textProperty().addListener((o, oldVal, newVal) -> applyFilters());
        typeFilterBox.valueProperty().addListener((o, oldVal, newVal) -> applyFilters());
        minMontantField.textProperty().addListener((o, oldVal, newVal) -> applyFilters());
        maxMontantField.textProperty().addListener((o, oldVal, newVal) -> applyFilters());

        refreshTable();
    }

    /** Applies current keyword + type + montant filters and refreshes the cards (no button needed). */
    private void applyFilters() {
        String keyword = keywordField.getText();
        String type = typeFilterBox.getValue();
        String minStr = minMontantField.getText();
        String maxStr = maxMontantField.getText();
        try {
            List<OPÉRATIONS> all = operationController.getAllOperations();
            double tmpMin = Double.NEGATIVE_INFINITY;
            double tmpMax = Double.POSITIVE_INFINITY;
            try {
                if (minStr != null && !minStr.isBlank()) tmpMin = Double.parseDouble(minStr);
            } catch (NumberFormatException ignored) {}
            try {
                if (maxStr != null && !maxStr.isBlank()) tmpMax = Double.parseDouble(maxStr);
            } catch (NumberFormatException ignored) {}
            final double minVal = tmpMin;
            final double maxVal = tmpMax;
            List<OPÉRATIONS> filtered = all.stream()
                    .filter(op -> {
                        if (keyword == null || keyword.isBlank()) return true;
                        return containsIgnoreCase(op.getCategorie(), keyword)
                                || containsIgnoreCase(op.getDescription(), keyword);
                    })
                    .filter(op -> {
                        if (type == null || type.isBlank()) return true;
                        String t = op.getType();
                        return t != null && t.equalsIgnoreCase(type);
                    })
                    .filter(op -> op.getMontant() >= minVal && op.getMontant() <= maxVal)
                    .collect(Collectors.toList());
            List<OPÉRATIONS> byEntreprise = filterForCurrentEntreprise(filtered);
            data.setAll(byEntreprise);
            renderCards(byEntreprise);
        } catch (SQLException e) {
            showError("Erreur lors du filtrage", e);
        }
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
        applyFilters();
    }

    @FXML
    private void onFilterByMontant() {
        applyFilters();
    }

    @FXML
    private void onSearchByKeyword() {
        applyFilters();
    }

    @FXML
    private void onKYC() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/kyc_modal.fxml"));
            Parent root = loader.load();
            Stage stage = new Stage();
            stage.initModality(Modality.APPLICATION_MODAL);
            stage.setTitle("Vérification KYC");
            stage.setScene(new Scene(root));
            stage.showAndWait();

            if (KYCController.isVerified()) {
                kycStatusLabel.setText("✅ Vérifié");
                kycStatusLabel.setStyle("-fx-text-fill: #10b981; -fx-font-weight: bold;");
            }
        } catch (Exception e) {
            e.printStackTrace();
            showError("Erreur lors de l'ouverture du KYC", e);
        }
    }

    private boolean ensureKYC() {
        if (KYCController.isVerified()) {
            return true;
        }

        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/kyc_modal.fxml"));
            Parent root = loader.load();
            Stage stage = new Stage();
            stage.initModality(Modality.APPLICATION_MODAL);
            stage.setTitle("Vérification Biométrique Requise");
            stage.setScene(new Scene(root));
            stage.showAndWait();

            if (KYCController.isVerified()) {
                kycStatusLabel.setText("✅ Vérifié");
                kycStatusLabel.setStyle("-fx-text-fill: #10b981; -fx-font-weight: bold;");
                return true;
            }
        } catch (Exception e) {
            e.printStackTrace();
            showError("Erreur lors de l'ouverture du KYC", e);
        }
        return false;
    }

    @FXML
    private void onAdd() {
        if (!ensureKYC()) {
            return;
        }

        String opCurrency = operationCurrencyBox.getValue();
        
        // Background thread to handle potential API call for conversion
        new Thread(() -> {
            try {
                OPÉRATIONS op = buildFromForm(null);
                if (op == null) return;

                TRÉSORERIE t = op.getTresorerie();
                String tresCurrency = (t != null && t.getDevise() != null) ? t.getDevise() : "TND";

                double finalAmount = op.getMontant();
                if (!opCurrency.equalsIgnoreCase(tresCurrency)) {
                    double rate = exchangeRateService.getExchangeRate(opCurrency, tresCurrency);
                    finalAmount = op.getMontant() * rate;
                    op.setMontant(finalAmount);
                }

                final double convertedAmount = finalAmount;
                operationController.createOperation(
                        op.getTresorerie(),
                        op.getType(),
                        convertedAmount,
                        op.getCategorie(),
                        op.getDescription()
                );

                javafx.application.Platform.runLater(() -> {
                    refreshTable();
                    clearForm();
                    if (DashboardController.getInstance() != null) {
                        DashboardController.getInstance().updateStats();
                    }
                });
            } catch (Exception e) {
                e.printStackTrace();
                javafx.application.Platform.runLater(() -> showError("Erreur lors de l'ajout", e));
            }
        }).start();
    }

    @FXML
    private void onUpdate() {
        if (!ensureKYC()) {
            return;
        }

        OPÉRATIONS selected = selectedOperation;
        if (selected == null) {
            showInfo("Veuillez sélectionner une opération à modifier.");
            return;
        }

        String opCurrency = operationCurrencyBox.getValue();

        new Thread(() -> {
            try {
                OPÉRATIONS op = buildFromForm(selected.getIdOperation());
                if (op == null) return;
                op.setIdOperation(selected.getIdOperation());

                TRÉSORERIE t = op.getTresorerie();
                String tresCurrency = (t != null && t.getDevise() != null) ? t.getDevise() : "TND";

                double finalAmount = op.getMontant();
                if (!opCurrency.equalsIgnoreCase(tresCurrency)) {
                    double rate = exchangeRateService.getExchangeRate(opCurrency, tresCurrency);
                    finalAmount = op.getMontant() * rate;
                    op.setMontant(finalAmount);
                }

                operationController.updateOperation(op);

                javafx.application.Platform.runLater(() -> {
                    refreshTable();
                    clearForm();
                    if (DashboardController.getInstance() != null) {
                        DashboardController.getInstance().updateStats();
                    }
                });
            } catch (Exception e) {
                e.printStackTrace();
                javafx.application.Platform.runLater(() -> showError("Erreur lors de la mise à jour", e));
            }
        }).start();
    }

    @FXML
    private void onDelete() {
        if (!ensureKYC()) {
            return;
        }

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
        
        //verif session entreprise 

        try {
            Integer fromSession = UserSession.getCurrentTresorerieId();
            if (fromSession == null) {
                showInfo("Veuillez choisir une trésorerie dans l'onglet Trésorerie d'abord.");
                return null;
            }
            //creation de sesision tresorerie
            int idTres = fromSession;
            // retrieve form values
            String type = typeBox.getValue();
            double montant = Double.parseDouble(montantField.getText());
            String categorie = categorieField.getText();
            String description = descriptionField.getText();
            //control de saisieee
            if (type == null ) {
                showInfo("Le type (revenu/depense) est obligatoire.");
                return null;
            }
            ////////////////////////////////////////////////////////////////////
            if (montant == 0) {
                showInfo("Le montant est obligatoire.");
                return null;
            }
            if (montant < 0) {
                showInfo("Le montant ne peut pas être négatif.");
                return null;
            }
           ///////////////////////////////////////////////////////////////////
            if (description == null || description.isBlank()) {
                showInfo("La description est obligatoire.");
                return null;
            }
            if (description.length() > 255) {
                showInfo("La description ne doit pas dépasser 255 caractères.");
                return null;
            }
            ///////////////////////////////////////////////////////////////////
             if (categorie == null || categorie.isBlank()) {
                showInfo("La catégorie est obligatoire.");
                return null;
            }
            if (categorie.length() > 15) {
                showInfo("La catégorie ne doit pas dépasser 15 caractères.");
                return null;
            }


            TRÉSORERIE t = tresorerieController.getTresorerieById(idTres);
          //  if (t == null) {
            //    showInfo("Aucune trésorerie trouvée pour l'ID : " + idTres);
            //    return null;
            //}

            OPÉRATIONS op;
            if (existingId == null) {
                op = new OPÉRATIONS(t, type, montant, categorie, description);
            } else {
                op = new OPÉRATIONS(existingId, t, type, montant, categorie, description, LocalDateTime.now());
            }
            return op;
        } catch (NumberFormatException e) {
            showInfo("Vérifiez les valeurs numériques ( montant).");
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
        operationCurrencyBox.setValue("TND");
        selectedOperation = null;
    }

    private void renderCards(List<OPÉRATIONS> operations) {
        revenuCardsContainer.getChildren().clear();
        depenseCardsContainer.getChildren().clear();

        for (OPÉRATIONS op : operations) {
            VBox card = createCard(op);
            if ("revenu".equalsIgnoreCase(op.getType())) {
                revenuCardsContainer.getChildren().add(card);
            } else {
                depenseCardsContainer.getChildren().add(card);
            }
        }
    }

    private VBox createCard(OPÉRATIONS op) {
        VBox card = new VBox(6);
        card.setPadding(new Insets(12));
        card.setSpacing(6);
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
        });

        HBox actions = new HBox(8);
        Button modifyBtn = new Button("Modifier");
        Button deleteBtn = new Button("Supprimer");
        Button noteBtn = new Button("Notes");
        
        modifyBtn.setStyle("-fx-background-color: #0ea5e9; -fx-text-fill: white; -fx-background-radius: 6;");
        deleteBtn.setStyle("-fx-background-color: #ef4444; -fx-text-fill: white; -fx-background-radius: 6;");
        noteBtn.setStyle("-fx-background-color: #8b5cf6; -fx-text-fill: white; -fx-background-radius: 6;");
        
        actions.getChildren().addAll(modifyBtn, deleteBtn, noteBtn);
        card.getChildren().add(actions);

        noteBtn.setOnAction(ev -> {
            openNoteEditor(op);
            ev.consume();
        });

        modifyBtn.setOnAction(ev -> {
            selectedOperation = op;
            if (selectedOperationCard != null) {
                selectedOperationCard.getStyleClass().remove("card-selected");
            }
            card.getStyleClass().add("card-selected");
            selectedOperationCard = card;
            populateForm(op);
            ev.consume();
        });

        deleteBtn.setOnAction(ev -> {
            Alert confirm = new Alert(Alert.AlertType.CONFIRMATION);
            confirm.setHeaderText("Supprimer cette opération ?");
            confirm.setContentText("Cette action est irréversible.");
            confirm.showAndWait().ifPresent(result -> {
                if (result.getButtonData().isDefaultButton()) {
                    try {
                        operationController.deleteOperation(op.getIdOperation());
                        if (selectedOperation != null && selectedOperation.getIdOperation() == op.getIdOperation()) {
                            clearForm();
                        }
                        refreshTable();
                    } catch (SQLException e1) {
                        showError("Erreur lors de la suppression", e1);
                    }
                }
            });
            ev.consume();
        });

        // Hover handled via CSS .card:hover

        return card;
    }

    private boolean containsIgnoreCase(String haystack, String needle) {
        if (haystack == null || needle == null) return false;
        return haystack.toLowerCase().contains(needle.toLowerCase());
    }

    private void openNoteEditor(OPÉRATIONS op) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/note_editor.fxml"));
            Parent root = loader.load();
            
            NoteEditorController controller = loader.getController();
            controller.setOperation(op);
            
            Stage stage = new Stage();
            stage.initModality(Modality.APPLICATION_MODAL);
            stage.setTitle("Éditeur de notes - Cashfly");
            stage.setScene(new Scene(root));
            stage.show();
        } catch (Exception e) {
            e.printStackTrace();
            showError("Erreur lors de l'ouverture de l'éditeur de notes", e);
        }
    }

    private void showError(String message, Exception e) {
        e.printStackTrace();
        javafx.application.Platform.runLater(() -> {
            Alert alert = new Alert(Alert.AlertType.ERROR);
            alert.setHeaderText(message);
            alert.setContentText(e.getMessage());
            alert.showAndWait();
        });
    }

    private void showInfo(String message) {
        javafx.application.Platform.runLater(() -> {
            Alert alert = new Alert(Alert.AlertType.INFORMATION);
            alert.setHeaderText(null);
            alert.setContentText(message);
            alert.showAndWait();
        });
    }

    private List<OPÉRATIONS> filterForCurrentEntreprise(List<OPÉRATIONS> input) {
        Integer entrepriseId = UserSession.getCurrentEntrepriseId();
        Integer tresId = UserSession.getCurrentTresorerieId();
        return input.stream()
                .filter(op -> {
                    TRÉSORERIE t = op.getTresorerie();
                    if (t == null) return false;
                    if (entrepriseId != null && t.getIdEntreprise() != entrepriseId) return false;
                    if (tresId != null && t.getIdTresorerie() != tresId) return false;
                    return true;
                })
                .collect(Collectors.toList());
    }

    @FXML
    private void onCheckRate() {
        String from = fromCurrencyField.getText();
        String to = toCurrencyField.getText();
        
        if (from == null || from.isBlank() || to == null || to.isBlank()) {
            showInfo("Veuillez saisir les deux devises (ex: EUR, TND).");
            return;
        }

        rateResultLabel.setText("Chargement...");
        
        // Run in a background thread to avoid UI freezing
        new Thread(() -> {
            try {
                double rate = exchangeRateService.getExchangeRate(from, to);
                javafx.application.Platform.runLater(() -> {
                    rateResultLabel.setText(String.format("1 %s = %.4f %s", from.toUpperCase(), rate, to.toUpperCase()));
                });
            } catch (Exception e) {
                e.printStackTrace();
                javafx.application.Platform.runLater(() -> {
                    rateResultLabel.setText("Erreur : " + e.getMessage());
                });
            }
        }).start();
    }
}
