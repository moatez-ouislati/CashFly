package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.FlowPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import tn.cashfly.jpo.entities.JPO;
import tn.cashfly.jpo.entities.Participation;
import tn.cashfly.jpo.services.ServiceJPO;
import tn.cashfly.jpo.services.ServiceParticipation;
import tn.cashfly.session.UserSession;

import java.net.URL;
import java.sql.SQLException;
import java.time.LocalDate;
import java.time.ZoneId;
import java.time.format.DateTimeFormatter;
import java.util.List;
import java.util.ResourceBundle;

public class InvestorEventsController implements Initializable {

    @FXML
    private FlowPane eventsContainer;

    private ServiceJPO serviceJPO;
    private ServiceParticipation serviceParticipation;
    private ObservableList<JPO> events;

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        serviceJPO = new ServiceJPO();
        serviceParticipation = new ServiceParticipation();
        events = FXCollections.observableArrayList();
        loadEvents();
    }

    private void loadEvents() {
        eventsContainer.getChildren().clear();
        try {
            List<JPO> all = serviceJPO.getAll();
            LocalDate today = LocalDate.now();
            events.setAll(all.stream()
                    .filter(e -> toLocalDate(e.getDate_evenement()).isAfter(today.minusDays(1)))
                    .toList());
            if (events.isEmpty()) {
                Label empty = new Label("Aucun événement JPO à venir.");
                empty.setStyle("-fx-font-size: 16px; -fx-text-fill: #64748b;");
                eventsContainer.getChildren().add(empty);
                return;
            }
            for (JPO event : events) {
                eventsContainer.getChildren().add(createEventCard(event));
            }
        } catch (SQLException e) {
            showAlert("Erreur", "Impossible de charger les événements: " + e.getMessage());
        }
    }

    private VBox createEventCard(JPO event) {
        VBox card = new VBox(8);
        card.setAlignment(Pos.TOP_LEFT);
        card.setPadding(new Insets(12));
        card.setPrefWidth(320);
        card.setStyle("""
                -fx-background-color: white;
                -fx-background-radius: 12;
                -fx-border-radius: 12;
                -fx-border-color: #e0e4e8;
                -fx-effect: dropshadow(gaussian, rgba(0,0,0,0.06), 10, 0, 0, 4);
                """);

        Label title = new Label(event.getTitre());
        title.setStyle("-fx-font-size: 15px; -fx-font-weight: bold;");

        Label date = new Label(formatDate(event));
        date.setStyle("-fx-text-fill: #64748b;");

        Label location = new Label(event.getLieu());
        location.setStyle("-fx-text-fill: #64748b;");

        Label capacity = new Label(event.getCurrentParticipants() + "/" + event.getMaxParticipants() + " inscrits");
        capacity.setStyle("-fx-text-fill: #0d9488;");

        HBox statusBox = new HBox(6);
        statusBox.setAlignment(Pos.CENTER_LEFT);
        Label statusLabel = new Label(event.isFull() ? "Complet" : "Ouvert");
        statusLabel.setStyle(event.isFull()
                ? "-fx-background-color:#dc2626; -fx-text-fill:white; -fx-padding:2 8; -fx-background-radius:999;"
                : "-fx-background-color:#22c55e; -fx-text-fill:white; -fx-padding:2 8; -fx-background-radius:999;");
        statusBox.getChildren().add(statusLabel);

        HBox buttons = new HBox(8);
        buttons.setAlignment(Pos.CENTER_RIGHT);

        Button actionBtn = new Button();
        Button refreshBtn = new Button("↻");
        refreshBtn.setStyle("-fx-background-color:#e5e7eb; -fx-text-fill:#111827; -fx-background-radius:6;");
        refreshBtn.setOnAction(e -> loadEvents());

        Integer investorId = UserSession.getUserId();
        Participation participation = null;
        if (investorId != null) {
            try {
                participation = serviceParticipation.getParticipation(event.getId_evenement(), investorId);
            } catch (SQLException e) {
            }
        }

        if (investorId == null) {
            actionBtn.setDisable(true);
            actionBtn.setText("Connexion requise");
            actionBtn.setStyle("-fx-background-color:#9ca3af; -fx-text-fill:white; -fx-background-radius:6;");
        } else if (participation == null) {
            actionBtn.setText("S'inscrire");
            actionBtn.setStyle("-fx-background-color:#2563eb; -fx-text-fill:white; -fx-background-radius:6;");
            Participation finalParticipation = null;
            actionBtn.setOnAction(e -> handleRegister(event, investorId));
        } else {
            String statut = participation.getStatut();
            if ("confirmé".equalsIgnoreCase(statut) || "en_attente".equalsIgnoreCase(statut)) {
                actionBtn.setText("Annuler");
                actionBtn.setStyle("-fx-background-color:#f97316; -fx-text-fill:white; -fx-background-radius:6;");
                Participation finalParticipation1 = participation;
                actionBtn.setOnAction(e -> handleCancel(event, investorId, finalParticipation1));
            } else {
                actionBtn.setText("S'inscrire");
                actionBtn.setStyle("-fx-background-color:#2563eb; -fx-text-fill:white; -fx-background-radius:6;");
                actionBtn.setOnAction(e -> handleRegister(event, investorId));
            }
        }

        buttons.getChildren().addAll(refreshBtn, actionBtn);

        card.getChildren().addAll(title, date, location, capacity, statusBox, buttons);
        return card;
    }

    private void handleRegister(JPO event, int investorId) {
        try {
            boolean confirmed = serviceParticipation.register(event.getId_evenement(), investorId);
            if (confirmed) {
                showAlert("Succès", "Inscription confirmée à l'événement.");
            } else {
                showAlert("Liste d'attente", "L'événement est complet, vous êtes en liste d'attente.");
            }
            loadEvents();
        } catch (SQLException e) {
            showAlert("Erreur", "Impossible de s'inscrire: " + e.getMessage());
        }
    }

    private void handleCancel(JPO event, int investorId, Participation participation) {
        try {
            serviceParticipation.cancel(event.getId_evenement(), investorId);
            showAlert("Succès", "Inscription annulée.");
            loadEvents();
        } catch (SQLException e) {
            showAlert("Erreur", "Impossible d'annuler: " + e.getMessage());
        }
    }

    private LocalDate toLocalDate(java.util.Date date) {
        return new java.sql.Date(date.getTime()).toLocalDate();
    }

    private String formatDate(JPO event) {
        LocalDate d = toLocalDate(event.getDate_evenement());
        return d.format(DateTimeFormatter.ofPattern("EEEE dd MMMM yyyy", java.util.Locale.FRENCH));
    }

    private void showAlert(String title, String msg) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(msg);
        alert.showAndWait();
    }
}
