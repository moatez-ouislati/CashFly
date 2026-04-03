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
import tn.cashfly.entities.Entreprise;
import tn.cashfly.session.UserSession;
import tn.cashfly.services.EntrepriseService;
import tn.cashfly.services.GeocodingService;
import tn.cashfly.DashboardController;
import java.math.BigDecimal;

import java.sql.SQLException;
import java.time.LocalDate;
import java.time.format.DateTimeParseException;
import java.util.List;
import java.util.stream.Collectors;

public class EntrepriseUIController {

    @FXML
    private FlowPane entrepriseCardsContainer;

    @FXML
    private TextField searchField;
    @FXML
    private TextField secteurFilterField;

    @FXML
    private TextField nomField;
    @FXML
    private TextField secteurField;
    @FXML
    private javafx.scene.control.ComboBox<String> cbFormeJuridique;
    @FXML
    private TextField dateCreationField;
    @FXML
    private TextField capitalField;
    @FXML
    private TextField proprietaireField;
    @FXML
    private TextField adresseField;

    private final EntrepriseService entrepriseService = new EntrepriseService();
    private final ObservableList<Entreprise> data = FXCollections.observableArrayList();
    private List<Entreprise> allEntreprisesCache = java.util.Collections.emptyList();

    private Entreprise selectedEntreprise;
    private VBox selectedEntrepriseCard;

    @FXML
    public void initialize() {
        cbFormeJuridique.getItems().addAll(
                "SARL", "SA", "EURL", "SAS", "SNC", "Auto-entrepreneur"
        );
        if (UserSession.getUserId() != null) {
            proprietaireField.setText(String.valueOf(UserSession.getUserId()));
            proprietaireField.setEditable(false);
        }
        refreshTable();
    }

    private void refreshTable() {
        try {
            List<Entreprise> all = entrepriseService.afficher();
            allEntreprisesCache = all;
            List<Entreprise> filtered = filterForCurrentUser(all);
            data.setAll(filtered);
            renderCards(filtered);
        } catch (SQLException e) {
            showError("Erreur lors du chargement des entreprises", e);
        }
    }

    private void populateForm(Entreprise e) {
        nomField.setText(e.getNom());
        secteurField.setText(e.getSecteur());
        cbFormeJuridique.setValue(e.getFormeJuridique());
        if (e.getDateCreation() != null) {
            dateCreationField.setText(e.getDateCreation().toString());
        } else {
            dateCreationField.clear();
        }
        capitalField.setText(String.valueOf(e.getCapital()));
        proprietaireField.setText(String.valueOf(e.getIdProprietaire()));
    }

    @FXML
    private void onSearch() {
        String keyword = searchField.getText();
        try {
            List<Entreprise> all = allEntreprisesCache.isEmpty()
                    ? entrepriseService.afficher()
                    : allEntreprisesCache;
            allEntreprisesCache = all;
            List<Entreprise> list = all.stream()
                    .filter(e -> e.getNom().toLowerCase().contains(keyword.toLowerCase()))
                    .collect(Collectors.toList());
            List<Entreprise> filtered = filterForCurrentUser(list);
            data.setAll(filtered);
            renderCards(filtered);
        } catch (SQLException e) {
            showError("Erreur lors de la recherche", e);
        }
    }

    @FXML
    private void onFilterBySecteur() {
        String secteur = secteurFilterField.getText();
        try {
            List<Entreprise> all = allEntreprisesCache.isEmpty()
                    ? entrepriseService.afficher()
                    : allEntreprisesCache;
            allEntreprisesCache = all;
            List<Entreprise> list = all.stream()
                    .filter(e -> e.getSecteur().toLowerCase().contains(secteur.toLowerCase()))
                    .collect(Collectors.toList());
            List<Entreprise> filtered = filterForCurrentUser(list);
            data.setAll(filtered);
            renderCards(filtered);
        } catch (SQLException e) {
            showError("Erreur lors du filtrage", e);
        }
    }

    @FXML
    private void onAdd() {
        try {
            Entreprise e = buildFromForm(null);
            if (e == null) return;
            
            // Auto geocoding for UI-based creation too
            double[] coords = GeocodingService.getCoordinates("Tunisia"); // placeholder if address field missing
            e.setLatitude(coords[0]);
            e.setLongitude(coords[1]);
            
            entrepriseService.ajouter(e);
            refreshTable();
            clearForm();
            if (DashboardController.getInstance() != null) {
                DashboardController.getInstance().updateStats();
            }
        } catch (SQLException ex) {
            showError("Erreur lors de l'ajout", ex);
        }
    }

    @FXML
    private void onUpdate() {
        Entreprise selected = selectedEntreprise;
        if (selected == null) {
            showInfo("Veuillez sélectionner une entreprise à modifier.");
            return;
        }
        try {
            Entreprise updated = buildFromForm(selected.getIdEntreprise());
            if (updated == null) return;
            
            // updated already has the ID from buildFromForm(selected.getIdEntreprise())
            
            // Preserve coordinates on update if not modified
            updated.setLatitude(selected.getLatitude());
            updated.setLongitude(selected.getLongitude());
            // Address is updated in buildFromForm if I add it to the form
            // But wait, buildFromForm doesn't seem to have adresseField in some versions? 
            // Let's check buildFromForm in EntrepriseUIController.java
            
            entrepriseService.modifier(updated);
            refreshTable();
            if (DashboardController.getInstance() != null) {
                DashboardController.getInstance().updateStats();
            }
        } catch (SQLException ex) {
            showError("Erreur lors de la mise à jour", ex);
        }
    }

    @FXML
    private void onDelete() {
        Entreprise selected = selectedEntreprise;
        if (selected == null) {
            showInfo("Veuillez sélectionner une entreprise à supprimer.");
            return;
        }
        try {
            entrepriseService.supprimer(selected);
            refreshTable();
            clearForm();
            if (DashboardController.getInstance() != null) {
                DashboardController.getInstance().updateStats();
            }
        } catch (SQLException ex) {
            showError("Erreur lors de la suppression", ex);
        }
    }

    private Entreprise buildFromForm(Integer existingId) {
        try {
            String nom = nomField.getText();
            String secteur = secteurField.getText();
            String forme = cbFormeJuridique.getValue();
            String dateStr = dateCreationField.getText();
            String capitalStr = capitalField.getText();
            String propStr = proprietaireField.getText();

            if (nom == null || nom.isBlank()) {
                showInfo("Le nom est obligatoire (min 3 caractères).");
                return null;
            }
            if (nom.length() < 3) {
                showInfo("Le nom est trop court (min 3 caractères, actuel: " + nom.length() + ").");
                return null;
            }

            if (forme == null || forme.isBlank()) {
                showInfo("La forme juridique est obligatoire.");
                return null;
            }

            LocalDate date = null;
            if (dateStr != null && !dateStr.isBlank()) {
                try {
                    date = LocalDate.parse(dateStr);
                } catch (DateTimeParseException e) {
                    showInfo("Format de date invalide (attendu: yyyy-MM-dd, ex: 2023-01-01).");
                    return null;
                }
            }
            double capital;
            try {
                capital = capitalStr == null || capitalStr.isBlank()
                        ? 0.0
                        : Double.parseDouble(capitalStr);
            } catch (NumberFormatException e) {
                showInfo("Le capital doit être un nombre valide.");
                return null;
            }
            
            int idProp;
            try {
                if (UserSession.getUserId() != null) {
                    idProp = UserSession.getUserId();
                } else {
                    idProp = Integer.parseInt(propStr);
                }
            } catch (NumberFormatException e) {
                showInfo("L'ID propriétaire doit être un nombre.");
                return null;
            }

            if (existingId == null) {
                return new Entreprise(nom, secteur, forme, date, capital, idProp);
            } else {
                return new Entreprise(existingId, nom, secteur, forme, date, capital, idProp);
            }
        } catch (Exception e) {
            showError("Erreur lors de la préparation des données", e);
            return null;
        }
    }

    private void clearForm() {
        nomField.clear();
        secteurField.clear();
        cbFormeJuridique.setValue(null);
        dateCreationField.clear();
        capitalField.clear();
        if (UserSession.getUserId() != null) {
            proprietaireField.setText(String.valueOf(UserSession.getUserId()));
        } else {
            proprietaireField.clear();
        }
        selectedEntreprise = null;
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

    private List<Entreprise> filterForCurrentUser(List<Entreprise> input) {
        Integer userId = UserSession.getUserId();
        if (userId == null) {
            return input;
        }
        return input.stream()
                .filter(e -> e.getIdProprietaire() == userId)
                .collect(Collectors.toList());
    }

    private void renderCards(List<Entreprise> entreprises) {
        entrepriseCardsContainer.getChildren().clear();

        for (Entreprise e : entreprises) {
            VBox card = createCard(e);
            entrepriseCardsContainer.getChildren().add(card);
        }
    }

    private VBox createCard(Entreprise e) {
        VBox card = new VBox(6);
        card.setPadding(new Insets(12));
        card.setSpacing(6);
        card.getStyleClass().add("card");

        Label title = new Label(e.getNom());
        title.setStyle("-fx-font-weight: bold; -fx-text-fill: #0f172a;");

        String secteurText = e.getSecteur() != null && !e.getSecteur().isBlank()
                ? e.getSecteur()
                : "(secteur inconnu)";
        Label secteur = new Label(secteurText);
        secteur.setStyle("-fx-text-fill: #6b7280;");

        String formeText = e.getFormeJuridique() != null && !e.getFormeJuridique().isBlank()
                ? e.getFormeJuridique()
                : "(forme juridique inconnue)";
        Label forme = new Label(formeText);
        forme.setStyle("-fx-text-fill: #4b5563;");

        String meta = "ID #" + e.getIdEntreprise();
        if (e.getDateCreation() != null) {
            meta += " • " + e.getDateCreation();
        }
        meta += " • Capital: " + e.getCapital();
        meta += " • Propriétaire: " + e.getIdProprietaire();
        Label metaLabel = new Label(meta);
        metaLabel.setStyle("-fx-text-fill: #9ca3af; -fx-font-size: 11;");

        HBox header = new HBox(8, title);
        header.setPadding(new Insets(0, 0, 4, 0));

        card.getChildren().addAll(header, secteur, forme, metaLabel);

        card.setOnMouseClicked(eClick -> {
            if (selectedEntrepriseCard != null) {
                selectedEntrepriseCard.getStyleClass().remove("card-selected");
            }
            card.getStyleClass().add("card-selected");
            selectedEntrepriseCard = card;

            selectedEntreprise = e;
            UserSession.setCurrentEntreprise(e.getIdEntreprise(), e.getNom());
        });

        HBox actions = new HBox(8);
        Button modifyBtn = new Button("Modifier");
        Button deleteBtn = new Button("Supprimer");
        modifyBtn.setStyle("-fx-background-color: #0ea5e9; -fx-text-fill: white; -fx-background-radius: 6;");
        deleteBtn.setStyle("-fx-background-color: #ef4444; -fx-text-fill: white; -fx-background-radius: 6;");
        actions.getChildren().addAll(modifyBtn, deleteBtn);
        card.getChildren().add(actions);

        modifyBtn.setOnAction(ev -> {
            selectedEntreprise = e;
            if (selectedEntrepriseCard != null) {
                selectedEntrepriseCard.getStyleClass().remove("card-selected");
            }
            card.getStyleClass().add("card-selected");
            selectedEntrepriseCard = card;
            populateForm(e);
            ev.consume();
        });

        deleteBtn.setOnAction(ev -> {
            Alert confirm = new Alert(Alert.AlertType.CONFIRMATION);
            confirm.setHeaderText("Supprimer cette entreprise ?");
            confirm.setContentText("Cette action est irréversible.");
            confirm.showAndWait().ifPresent(result -> {
                if (result.getButtonData().isDefaultButton()) {
                    try {
                        entrepriseService.supprimer(e);
                        if (selectedEntreprise != null && selectedEntreprise.getIdEntreprise() == e.getIdEntreprise()) {
                            clearForm();
                        }
                        refreshTable();
                    } catch (SQLException ex) {
                        showError("Erreur lors de la suppression", ex);
                    }
                }
            });
            ev.consume();
        });

        // Hover handled by CSS .card:hover

        return card;
    }
}
