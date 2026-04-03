package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.FlowPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import tn.cashfly.entities.TRÉSORERIE;
import tn.cashfly.session.UserSession;
import tn.cashfly.DashboardController;

import java.sql.SQLException;
import java.time.LocalDateTime;
import java.util.Collections;
import java.util.List;
import java.util.stream.Collectors;

public class TresorerieUIController {

    @FXML
    private FlowPane tresorerieCardsContainer;

    @FXML
    private TextField deviseFilterField;
    @FXML
    private TextField minSoldeField;

    @FXML
    private TextField nomCompteField;
    @FXML
    private javafx.scene.control.ComboBox<TRÉSORERIE.TypeCompte> typeCompteCombo;
    @FXML
    private TextField ribField;
    @FXML
    private TextField numeroCompteField;

    @FXML
    private TextField soldeField;
    @FXML
    private TextField deviseField;

    @FXML
    private Label currentEntrepriseLabel;

    private final TresorerieController tresorerieController = new TresorerieController();
    private final ObservableList<TRÉSORERIE> data = FXCollections.observableArrayList();
    private List<TRÉSORERIE> allTresoreriesCache = Collections.emptyList();

    private TRÉSORERIE selectedTresorerie;
    private VBox selectedTresorerieCard;

    @FXML
    public void initialize() {
        String name = UserSession.getCurrentEntrepriseName();
        if (name != null) {
            currentEntrepriseLabel.setText("Entreprise sélectionnée : " + name);
        } else {
            currentEntrepriseLabel.setText("Aucune entreprise sélectionnée");
        }

        // Initialize ComboBox
        typeCompteCombo.setItems(FXCollections.observableArrayList(TRÉSORERIE.TypeCompte.values()));
        typeCompteCombo.valueProperty().addListener((obs, oldVal, newVal) -> {
            if (newVal == TRÉSORERIE.TypeCompte.BANQUE) {
                ribField.setVisible(true);
                ribField.setManaged(true);
                numeroCompteField.setVisible(false);
                numeroCompteField.setManaged(false);
                numeroCompteField.clear();
            } else if (newVal != null) {
                ribField.setVisible(false);
                ribField.setManaged(false);
                ribField.clear();
                numeroCompteField.setVisible(true);
                numeroCompteField.setManaged(true);
                
                // Auto-generate if adding new or if field is empty
                if (selectedTresorerie == null && (numeroCompteField.getText() == null || numeroCompteField.getText().isBlank())) {
                    try {
                        numeroCompteField.setText(tresorerieController.generateNextNumeroCompte());
                    } catch (SQLException e) {
                        e.printStackTrace();
                    }
                }
            }
        });

        // Live filter: run directly when user types (no button click)
        deviseFilterField.textProperty().addListener((o, oldVal, newVal) -> applyFilters());
        minSoldeField.textProperty().addListener((o, oldVal, newVal) -> applyFilters());

        // Make numeroCompteField non-editable to ensure automatic increment is respected
        numeroCompteField.setEditable(false);
        numeroCompteField.setStyle("-fx-background-color: #f1f5f9;"); // Light gray background to show it's read-only

        refreshTable();
    }

    /** Applies current devise + min solde filters and refreshes the cards (no button needed). */
    private void applyFilters() {
        String devise = deviseFilterField.getText();
        String minStr = minSoldeField.getText();
        try {
            List<TRÉSORERIE> list = allTresoreriesCache.isEmpty()
                    ? tresorerieController.getAllTresoreries()
                    : allTresoreriesCache;
            allTresoreriesCache = list;
            if (devise != null && !devise.isBlank()) {
                list = list.stream()
                        .filter(t -> devise.equalsIgnoreCase(t.getDevise()))
                        .collect(Collectors.toList());
            }
            if (minStr != null && !minStr.isBlank()) {
                try {
                    double min = Double.parseDouble(minStr);
                    list = list.stream()
                            .filter(t -> t.getSolde() >= min)
                            .collect(Collectors.toList());
                } catch (NumberFormatException ignored) {
                    // keep current list if invalid number
                }
            }
            List<TRÉSORERIE> filtered = filterForCurrentEntreprise(list);
            data.setAll(filtered);
            renderCards(filtered);
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

            List<TRÉSORERIE> all = tresorerieController.getAllTresoreries();
            allTresoreriesCache = all;
            List<TRÉSORERIE> filtered = filterForCurrentEntreprise(all);
            data.setAll(filtered);
            renderCards(filtered);
        } catch (SQLException e) {
            showError("Erreur lors du chargement de la trésorerie", e);
        }
    }

    private void populateForm(TRÉSORERIE t) {
        nomCompteField.setText(t.getNomCompte());
        typeCompteCombo.setValue(t.getTypeCompte());
        if (t.getTypeCompte() == TRÉSORERIE.TypeCompte.BANQUE) {
            ribField.setText(t.getRib());
            numeroCompteField.clear();
        } else {
            numeroCompteField.setText(t.getNumeroCompte());
            ribField.clear();
        }
        soldeField.setText(String.valueOf(t.getSolde()));
        deviseField.setText(t.getDevise());
    }

    @FXML
    private void onFilterByDevise() {
        applyFilters();
    }

    @FXML
    private void onFilterByMinSolde() {
        applyFilters();
    }

    @FXML
    private void onAdd() {
        try {
            TRÉSORERIE t = buildFromForm(null);
            if (t == null) return;
            tresorerieController.addTresorerie(t);
            refreshTable();
            clearForm();
            if (DashboardController.getInstance() != null) {
                DashboardController.getInstance().updateStats();
            }
        } catch (SQLException e) {
            showError("Erreur lors de l'ajout", e);
        }
    }

    @FXML
    private void onUpdate() {
        TRÉSORERIE selected = selectedTresorerie;
        if (selected == null) {
            showInfo("Veuillez sélectionner une ligne à modifier.");
            return;
        }
        try {
            TRÉSORERIE t = buildFromForm(selected.getIdTresorerie());
            if (t == null) return;
            t.setIdTresorerie(selected.getIdTresorerie());
            tresorerieController.updateTresorerie(t);
            refreshTable();
            if (DashboardController.getInstance() != null) {
                DashboardController.getInstance().updateStats();
            }
        } catch (SQLException e) {
            showError("Erreur lors de la mise à jour", e);
        }
    }

    @FXML
    private void onDelete() {
        TRÉSORERIE selected = selectedTresorerie;
        if (selected == null) {
            showInfo("Veuillez sélectionner une ligne à supprimer.");
            return;
        }
        try {
            tresorerieController.deleteTresorerie(selected.getIdTresorerie());
            refreshTable();
            clearForm();
            if (DashboardController.getInstance() != null) {
                DashboardController.getInstance().updateStats();
            }
        } catch (SQLException e) {
            showError("Erreur lors de la suppression", e);
        }
    }

    private TRÉSORERIE buildFromForm(Integer existingId) {
        try {
            Integer fromSession = UserSession.getCurrentEntrepriseId();
            if (fromSession == null) {
                showInfo("Veuillez choisir une entreprise avant d'ajouter une trésorerie.");
                return null;
            }
            int idEntreprise = fromSession;
            String nom = nomCompteField.getText();
            TRÉSORERIE.TypeCompte type = typeCompteCombo.getValue();
            
            if (nom == null || nom.isBlank()) {
                showInfo("Le nom du compte est obligatoire (min 3 caractères).");
                return null;
            }
            if (nom.length() < 3) {
                showInfo("Le nom du compte est trop court (min 3 caractères, actuel: " + nom.length() + ").");
                return null;
            }
            if (type == null) {
                showInfo("Le type de compte est obligatoire.");
                return null;
            }

            String rib = null;
            String num = null;
            if (type == TRÉSORERIE.TypeCompte.BANQUE) {
                rib = ribField.getText();
                if (rib == null || rib.isBlank()) {
                     showInfo("Le RIB est obligatoire pour un compte bancaire (20 chiffres).");
                     return null;
                }
                if (rib.length() != 20 || !rib.matches("\\d+")) {
                    showInfo("Le RIB doit contenir exactement 20 chiffres (actuel: " + rib.length() + ").");
                    return null;
                }
            } else {
                num = numeroCompteField.getText();
                if (num == null || num.isBlank()) {
                     showInfo("Le numéro de compte est obligatoire (min 8 caractères).");
                     return null;
                }
                if (num.length() < 8) {
                    showInfo("Le numéro de compte est trop court (min 8 caractères, actuel: " + num.length() + ").");
                    return null;
                }
            }

            double solde;
            try {
                solde = Double.parseDouble(soldeField.getText());
            } catch (NumberFormatException e) {
                showInfo("Le solde doit être un nombre valide.");
                return null;
            }

            String devise = deviseField.getText();
            if (devise == null || devise.length() != 3) {
                showInfo("La devise doit contenir exactement 3 caractères (ex: TND, EUR, USD).");
                return null;
            }

            if (existingId == null) {
                return new TRÉSORERIE(idEntreprise, nom, type, solde, devise, rib, num);
            } else {
                return new TRÉSORERIE(existingId, idEntreprise, nom, type, solde, devise, LocalDateTime.now(), rib, num);
            }
        } catch (Exception e) {
            showError("Erreur lors de la préparation des données", e);
            return null;
        }
    }

    private void clearForm() {
        nomCompteField.clear();
        typeCompteCombo.setValue(null);
        ribField.clear();
        numeroCompteField.clear();
        soldeField.clear();
        deviseField.clear();
        selectedTresorerie = null;
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

    private List<TRÉSORERIE> filterForCurrentEntreprise(List<TRÉSORERIE> input) {
        Integer entrepriseId = UserSession.getCurrentEntrepriseId();
        if (entrepriseId == null) {
            return input;
        }
        return input.stream()
                .filter(t -> t.getIdEntreprise() == entrepriseId)
                .collect(Collectors.toList());
    }

    private void renderCards(List<TRÉSORERIE> tresoreries) {
        tresorerieCardsContainer.getChildren().clear();

        for (TRÉSORERIE t : tresoreries) {
            VBox card = createCard(t);
            tresorerieCardsContainer.getChildren().add(card);
        }
    }

    private VBox createCard(TRÉSORERIE t) {
        VBox card = new VBox(6);
        card.setPadding(new Insets(12));
        card.setSpacing(6);
        card.getStyleClass().add("card");

        String titleText = t.getNomCompte() + " (" + t.getTypeCompte() + ")";
        Label title = new Label(titleText);
        title.setStyle("-fx-font-weight: bold; -fx-text-fill: #0f172a;");

        String soldeText = String.format("Solde: %.2f %s", t.getSolde(), t.getDevise());
        Label solde = new Label(soldeText);
        solde.setStyle("-fx-text-fill: #4b5563;");

        String details = "";
        if (t.getTypeCompte() == TRÉSORERIE.TypeCompte.BANQUE) {
            details = "RIB: " + t.getRib();
        } else {
            details = "N°: " + t.getNumeroCompte();
        }
        Label detailsLabel = new Label(details);
        detailsLabel.setStyle("-fx-text-fill: #6b7280; -fx-font-size: 11;");

        String meta = "Entreprise: " + t.getIdEntreprise();
        LocalDateTime maj = t.getDerniereMaj();
        if (maj != null) {
            meta += " • Maj: " + maj.toString();
        }
        Label metaLabel = new Label(meta);
        metaLabel.setStyle("-fx-text-fill: #9ca3af; -fx-font-size: 11;");

        HBox header = new HBox(8, title);
        header.setPadding(new Insets(0, 0, 4, 0));

        card.getChildren().addAll(header, solde, detailsLabel, metaLabel);

        card.setOnMouseClicked(eClick -> {
            if (selectedTresorerieCard != null) {
                selectedTresorerieCard.getStyleClass().remove("card-selected");
            }
            card.getStyleClass().add("card-selected");
            selectedTresorerieCard = card;

            selectedTresorerie = t;
            UserSession.setCurrentTresorerie(t.getIdTresorerie());
            
            // Navigate to Operations
            if (DashboardController.getInstance() != null) {
                DashboardController.getInstance().showOperations();
            }
        });

        HBox actions = new HBox(8);
        Button modifyBtn = new Button("Modifier");
        Button deleteBtn = new Button("Supprimer");
        modifyBtn.setStyle("-fx-background-color: #0ea5e9; -fx-text-fill: white; -fx-background-radius: 6;");
        deleteBtn.setStyle("-fx-background-color: #ef4444; -fx-text-fill: white; -fx-background-radius: 6;");
        actions.getChildren().addAll(modifyBtn, deleteBtn);
        card.getChildren().add(actions);

        modifyBtn.setOnAction(ev -> {
            selectedTresorerie = t;
            if (selectedTresorerieCard != null) {
                selectedTresorerieCard.getStyleClass().remove("card-selected");
            }
            card.getStyleClass().add("card-selected");
            selectedTresorerieCard = card;
            populateForm(t);
            ev.consume();
        });

        deleteBtn.setOnAction(ev -> {
            Alert confirm = new Alert(Alert.AlertType.CONFIRMATION);
            confirm.setHeaderText("Supprimer cette trésorerie ?");
            confirm.setContentText("Cette action est irréversible.");
            confirm.showAndWait().ifPresent(result -> {
                if (result.getButtonData().isDefaultButton()) {
                    try {
                        tresorerieController.deleteTresorerie(t.getIdTresorerie());
                        if (selectedTresorerie != null && selectedTresorerie.getIdTresorerie() == t.getIdTresorerie()) {
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

        // Hover handled by CSS .card:hover

        return card;
    }
}

