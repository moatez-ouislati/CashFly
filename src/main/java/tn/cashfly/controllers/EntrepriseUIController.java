package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.scene.control.Alert;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.FlowPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import tn.cashfly.entities.ENTREPRISE;
import tn.cashfly.session.UserSession;

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
    private TextField formeField;
    @FXML
    private TextField dateCreationField;
    @FXML
    private TextField capitalField;
    @FXML
    private TextField proprietaireField;

    private final EntrepriseController entrepriseController = new EntrepriseController();
    private final ObservableList<ENTREPRISE> data = FXCollections.observableArrayList();

    private ENTREPRISE selectedEntreprise;
    private VBox selectedEntrepriseCard;

    @FXML
    public void initialize() {
        if (UserSession.getUserId() != null) {
            proprietaireField.setText(String.valueOf(UserSession.getUserId()));
            proprietaireField.setEditable(false);
        }
        refreshTable();
    }

    private void refreshTable() {
        try {
            List<ENTREPRISE> all = entrepriseController.getAllEntreprises();
            List<ENTREPRISE> filtered = filterForCurrentUser(all);
            data.setAll(filtered);
            renderCards(filtered);
        } catch (SQLException e) {
            showError("Erreur lors du chargement des entreprises", e);
        }
    }

    private void populateForm(ENTREPRISE e) {
        nomField.setText(e.getNom());
        secteurField.setText(e.getSecteur());
        formeField.setText(e.getFormeJuridique());
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
            List<ENTREPRISE> list = entrepriseController.searchEntreprisesByName(keyword);
            List<ENTREPRISE> filtered = filterForCurrentUser(list);
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
            List<ENTREPRISE> list = entrepriseController.filterEntreprisesBySecteur(secteur);
            List<ENTREPRISE> filtered = filterForCurrentUser(list);
            data.setAll(filtered);
            renderCards(filtered);
        } catch (SQLException e) {
            showError("Erreur lors du filtrage", e);
        }
    }

    @FXML
    private void onAdd() {
        try {
            ENTREPRISE e = buildFromForm(null);
            if (e == null) return;
            entrepriseController.createEntreprise(
                    e.getNom(),
                    e.getSecteur(),
                    e.getFormeJuridique(),
                    e.getDateCreation(),
                    e.getCapital(),
                    e.getIdProprietaire()
            );
            refreshTable();
            clearForm();
        } catch (SQLException ex) {
            showError("Erreur lors de l'ajout", ex);
        }
    }

    @FXML
    private void onUpdate() {
        ENTREPRISE selected = selectedEntreprise;
        if (selected == null) {
            showInfo("Veuillez sélectionner une entreprise à modifier.");
            return;
        }
        try {
            ENTREPRISE updated = buildFromForm(selected.getIdEntreprise());
            if (updated == null) return;
            updated.setIdEntreprise(selected.getIdEntreprise());
            entrepriseController.updateEntreprise(updated);
            refreshTable();
        } catch (SQLException ex) {
            showError("Erreur lors de la mise à jour", ex);
        }
    }

    @FXML
    private void onDelete() {
        ENTREPRISE selected = selectedEntreprise;
        if (selected == null) {
            showInfo("Veuillez sélectionner une entreprise à supprimer.");
            return;
        }
        try {
            entrepriseController.deleteEntreprise(selected.getIdEntreprise());
            refreshTable();
            clearForm();
        } catch (SQLException ex) {
            showError("Erreur lors de la suppression", ex);
        }
    }

    private ENTREPRISE buildFromForm(Integer existingId) {
        try {
            String nom = nomField.getText();
            String secteur = secteurField.getText();
            String forme = formeField.getText();
            String dateStr = dateCreationField.getText();
            String capitalStr = capitalField.getText();
            String propStr = proprietaireField.getText();

            if (nom == null || nom.isBlank()) {
                showInfo("Le nom est obligatoire.");
                return null;
            }

            LocalDate date = null;
            if (dateStr != null && !dateStr.isBlank()) {
                date = LocalDate.parse(dateStr);
            }
            double capital = capitalStr == null || capitalStr.isBlank()
                    ? 0.0
                    : Double.parseDouble(capitalStr);
            int idProp;
            if (UserSession.getUserId() != null) {
                idProp = UserSession.getUserId();
            } else {
                idProp = Integer.parseInt(propStr);
            }

            if (existingId == null) {
                return new ENTREPRISE(nom, secteur, forme, date, capital, idProp);
            } else {
                return new ENTREPRISE(existingId, nom, secteur, forme, date, capital, idProp);
            }
        } catch (NumberFormatException | DateTimeParseException e) {
            showInfo("Vérifiez les valeurs numériques et la date (format yyyy-MM-dd).");
            return null;
        }
    }

    private void clearForm() {
        nomField.clear();
        secteurField.clear();
        formeField.clear();
        dateCreationField.clear();
        capitalField.clear();
        proprietaireField.clear();
        selectedEntreprise = null;
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

    private List<ENTREPRISE> filterForCurrentUser(List<ENTREPRISE> input) {
        Integer userId = UserSession.getUserId();
        if (userId == null) {
            return input;
        }
        return input.stream()
                .filter(e -> e.getIdProprietaire() == userId)
                .collect(Collectors.toList());
    }

    private void renderCards(List<ENTREPRISE> entreprises) {
        entrepriseCardsContainer.getChildren().clear();

        for (ENTREPRISE e : entreprises) {
            VBox card = createCard(e);
            entrepriseCardsContainer.getChildren().add(card);
        }
    }

    private VBox createCard(ENTREPRISE e) {
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
            populateForm(e);
            UserSession.setCurrentEntreprise(e.getIdEntreprise(), e.getNom());
        });

        return card;
    }
}
