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
    private TextField soldeField;
    @FXML
    private TextField deviseField;

    @FXML
    private Label currentEntrepriseLabel;

    private final TresorerieController tresorerieController = new TresorerieController();
    private final ObservableList<TRÉSORERIE> data = FXCollections.observableArrayList();

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

        // Live filter: run directly when user types (no button click)
        deviseFilterField.textProperty().addListener((o, oldVal, newVal) -> applyFilters());
        minSoldeField.textProperty().addListener((o, oldVal, newVal) -> applyFilters());

        refreshTable();
    }

    /** Applies current devise + min solde filters and refreshes the cards (no button needed). */
    private void applyFilters() {
        String devise = deviseFilterField.getText();
        String minStr = minSoldeField.getText();
        try {
            List<TRÉSORERIE> list = tresorerieController.getAllTresoreries();
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
            List<TRÉSORERIE> filtered = filterForCurrentEntreprise(all);
            data.setAll(filtered);
            renderCards(filtered);
        } catch (SQLException e) {
            showError("Erreur lors du chargement de la trésorerie", e);
        }
    }

    private void populateForm(TRÉSORERIE t) {
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
            tresorerieController.createTresorerie(t.getIdEntreprise(), t.getSolde(), t.getDevise());
            refreshTable();
            clearForm();
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
            double solde = Double.parseDouble(soldeField.getText());
            String devise = deviseField.getText();

            if (devise == null || devise.isBlank()) {
                showInfo("La devise est obligatoire.");
                return null;
            }

            if (existingId == null) {
                return new TRÉSORERIE(idEntreprise, solde, devise);
            } else {
                return new TRÉSORERIE(existingId, idEntreprise, solde, devise, LocalDateTime.now());
            }
        } catch (NumberFormatException e) {
            showInfo("Vérifiez les valeurs numériques (ID entreprise, solde).");
            return null;
        }
    }

    private void clearForm() {
        soldeField.clear();
        deviseField.clear();
        selectedTresorerie = null;
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
        String baseStyle = """
                -fx-background-color: white;
                -fx-border-color: #e5e7eb;
                -fx-border-radius: 10;
                -fx-background-radius: 10;
                -fx-effect: dropshadow(gaussian, rgba(15,23,42,0.10), 10, 0.25, 0, 2);
                """;
        String selectedStyle = baseStyle + "-fx-border-color: #0ea5e9; -fx-border-width: 2;";
        card.setStyle(baseStyle);
        card.setUserData(baseStyle);
        card.getStyleClass().add("card");

        String titleText = "Compte #" + t.getIdTresorerie();
        Label title = new Label(titleText);
        title.setStyle("-fx-font-weight: bold; -fx-text-fill: #0f172a;");

        String soldeText = String.format("Solde: %.2f %s", t.getSolde(), t.getDevise());
        Label solde = new Label(soldeText);
        solde.setStyle("-fx-text-fill: #4b5563;");

        String meta = "Entreprise: " + t.getIdEntreprise();
        LocalDateTime maj = t.getDerniereMaj();
        if (maj != null) {
            meta += " • Maj: " + maj.toString();
        }
        Label metaLabel = new Label(meta);
        metaLabel.setStyle("-fx-text-fill: #9ca3af; -fx-font-size: 11;");

        HBox header = new HBox(8, title);
        header.setPadding(new Insets(0, 0, 4, 0));

        card.getChildren().addAll(header, solde, metaLabel);

        card.setOnMouseClicked(eClick -> {
            if (selectedTresorerieCard != null) {
                selectedTresorerieCard.getStyleClass().remove("card-selected");
                Object prev = selectedTresorerieCard.getUserData();
                if (prev instanceof String) {
                    selectedTresorerieCard.setStyle((String) prev);
                }
            }
            card.getStyleClass().add("card-selected");
            selectedTresorerieCard = card;
            card.setStyle(selectedStyle);

            selectedTresorerie = t;
            UserSession.setCurrentTresorerie(t.getIdTresorerie());
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
                Object prev = selectedTresorerieCard.getUserData();
                if (prev instanceof String) {
                    selectedTresorerieCard.setStyle((String) prev);
                }
            }
            card.getStyleClass().add("card-selected");
            selectedTresorerieCard = card;
            card.setStyle(selectedStyle);
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

        card.setOnMouseEntered(evt -> {
            if (card != selectedTresorerieCard) {
                card.setStyle(baseStyle + "-fx-effect: dropshadow(gaussian, rgba(15,23,42,0.16), 14, 0.3, 0, 3);");
            }
        });
        card.setOnMouseExited(evt -> {
            if (card != selectedTresorerieCard) {
                card.setStyle(baseStyle);
            }
        });

        return card;
    }
}
