package tn.cashfly.jpo.controllers;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.Parent;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.ComboBox;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.image.ImageView;
import javafx.scene.layout.HBox;
import javafx.scene.layout.TilePane;
import javafx.scene.layout.VBox;
import javafx.scene.paint.Color;
import javafx.scene.shape.Circle;
import org.kordamp.ikonli.javafx.FontIcon;
import tn.cashfly.jpo.entities.JPO;
import tn.cashfly.jpo.entities.Utilisateur;
import tn.cashfly.jpo.services.ServiceJPO;
import tn.cashfly.jpo.services.ServiceParticipation;
import tn.cashfly.jpo.utils.ImageStorage;
import tn.cashfly.jpo.utils.SessionManager;

import java.io.IOException;
import java.sql.SQLException;
import java.time.Instant;
import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.Comparator;
import java.util.Date;
import java.util.List;
import java.util.stream.Collectors;

public class InvestorListAllJPOController {

    @FXML
    private TextField searchField;
    @FXML
    private ComboBox<String> sortComboBox;
    @FXML
    private ComboBox<String> filterAvailabilityCombo;
    @FXML
    private Button applyFiltersBtn;
    @FXML
    private TilePane eventsTilePane;
    @FXML
    private Label resultCountLabel;

    private ServiceJPO serviceJPO;
    private ServiceParticipation serviceParticipation;
    private Utilisateur currentUser;
    private List<JPO> allEvents;
    private List<JPO> filteredEvents;

    @FXML
    public void initialize() {
        serviceJPO = new ServiceJPO();
        serviceParticipation = new ServiceParticipation();
        currentUser = SessionManager.getCurrentUser();

        setupFilters();
        loadEvents();
    }

    private void setupFilters() {
        sortComboBox.getItems().addAll(
                "Date (prochain → lointain)",
                "Date (lointain → prochain)",
                "Places disponibles (plus → moins)",
                "Places disponibles (moins → plus)",
                "Nom (A → Z)");
        sortComboBox.setValue("Date (prochain → lointain)");

        filterAvailabilityCombo.getItems().addAll(
                "Tous les événements",
                "Places disponibles",
                "Complet / Liste d'attente");
        filterAvailabilityCombo.setValue("Tous les événements");

        applyFiltersBtn.setOnAction(e -> applyFilters());
        sortComboBox.setOnAction(e -> applyFilters());
        filterAvailabilityCombo.setOnAction(e -> applyFilters());

        searchField.textProperty().addListener((obs, old, newVal) -> applyFilters());
    }

    private void loadEvents() {
        try {
            allEvents = serviceJPO.getAll();
            allEvents = allEvents.stream()
                    .filter(jpo -> jpo.getDate_evenement().after(new Date()))
                    .collect(Collectors.toList());
            filteredEvents = new ArrayList<>(allEvents);
            applyFilters();
        } catch (SQLException e) {
            showAlert("Erreur", "Impossible de charger les événements: " + e.getMessage());
        }
    }

    private void applyFilters() {
        if (allEvents == null) {
            return;
        }

        filteredEvents = new ArrayList<>(allEvents);

        String search = searchField.getText().toLowerCase().trim();
        if (!search.isEmpty()) {
            filteredEvents = filteredEvents.stream()
                    .filter(e -> e.getTitre().toLowerCase().contains(search)
                            || e.getLieu().toLowerCase().contains(search)
                            || (e.getDescription() != null
                            && e.getDescription().toLowerCase().contains(search)))
                    .collect(Collectors.toList());
        }

        String availability = filterAvailabilityCombo.getValue();
        if (availability != null) {
            switch (availability) {
                case "Places disponibles" -> filteredEvents = filteredEvents.stream()
                        .filter(e -> e.getSpotsLeft() > 0)
                        .collect(Collectors.toList());
                case "Complet / Liste d'attente" -> filteredEvents = filteredEvents.stream()
                        .filter(e -> e.getSpotsLeft() <= 0)
                        .collect(Collectors.toList());
            }
        }

        String sort = sortComboBox.getValue();
        if (sort != null) {
            Comparator<JPO> comparator;
            switch (sort) {
                case "Date (prochain → lointain)" ->
                        comparator = Comparator.comparing(JPO::getDate_evenement);
                case "Date (lointain → prochain)" ->
                        comparator = Comparator.comparing(JPO::getDate_evenement).reversed();
                case "Places disponibles (plus → moins)" ->
                        comparator = Comparator.comparing(JPO::getSpotsLeft).reversed();
                case "Places disponibles (moins → plus)" ->
                        comparator = Comparator.comparing(JPO::getSpotsLeft);
                case "Nom (A → Z)" ->
                        comparator = Comparator.comparing(j -> j.getTitre().toLowerCase());
                default ->
                        comparator = Comparator.comparing(JPO::getDate_evenement);
            }
            filteredEvents.sort(comparator);
        }

        displayEvents();
        resultCountLabel.setText(filteredEvents.size() + " événement" + (filteredEvents.size() > 1 ? "s" : ""));
    }

    private void displayEvents() {
        eventsTilePane.getChildren().clear();

        if (filteredEvents.isEmpty()) {
            VBox emptyBox = new VBox(20);
            emptyBox.setAlignment(Pos.CENTER);
            Label emptyLabel = new Label("Aucun événement ne correspond à vos critères");
            emptyLabel.setStyle("-fx-font-size: 16px; -fx-text-fill: -color-fg-muted;");
            emptyBox.getChildren().add(emptyLabel);
            eventsTilePane.getChildren().add(emptyBox);
            return;
        }

        for (JPO event : filteredEvents) {
            eventsTilePane.getChildren().add(createEventCard(event));
        }
    }

    private VBox createEventCard(JPO event) {
        VBox card = new VBox(12);
        card.setPrefWidth(300);
        card.getStyleClass().add("event-card");
        card.setPadding(new Insets(15));

        card.setOnMouseClicked(e -> handleEventClick(event));
        card.setStyle(card.getStyle() + "-fx-cursor: hand;");

        ImageView imgView = new ImageView(ImageStorage.loadImage(event.getImagePath()));
        imgView.setFitHeight(150);
        imgView.setFitWidth(270);
        imgView.setPreserveRatio(true);
        imgView.setStyle("-fx-background-radius: 8;");

        Label title = new Label(event.getTitre());
        title.setStyle("-fx-font-size: 16px; -fx-font-weight: bold;");
        title.setWrapText(true);

        String dateStr = formatDate(event.getDate_evenement());
        VBox dateLocBox = new VBox(5);
        Label dateLabel = new Label(" " + dateStr);
        dateLabel.setGraphic(new FontIcon("fas-calendar-alt"));
        dateLabel.setStyle("-fx-font-size: 13px; -fx-text-fill: -color-fg-muted;");
        Label locLabel = new Label(" " + event.getLieu());
        locLabel.setGraphic(new FontIcon("fas-map-marker-alt"));
        locLabel.setStyle("-fx-font-size: 13px; -fx-text-fill: -color-fg-muted;");
        locLabel.setWrapText(true);
        dateLocBox.getChildren().addAll(dateLabel, locLabel);

        HBox participantsBox = new HBox(10);
        participantsBox.setAlignment(Pos.CENTER_LEFT);

        int spots = event.getSpotsLeft();
        int max = event.getMaxParticipants();
        Label spotsLabel = new Label();

        if (spots > 0) {
            double ratio = (double) (max - spots) / max;
            Circle circleDot = new Circle(5);
            if (ratio < 0.5) {
                spotsLabel.setText(" " + spots + " places disponibles");
                circleDot.setFill(Color.web("#28a745"));
                spotsLabel.setStyle("-fx-text-fill: #28a745; -fx-font-weight: bold;");
            } else if (ratio < 0.8) {
                spotsLabel.setText(" " + spots + " places restantes");
                circleDot.setFill(Color.web("#fd7e14"));
                spotsLabel.setStyle("-fx-text-fill: #fd7e14; -fx-font-weight: bold;");
            } else {
                spotsLabel.setText(" " + spots + " places restantes");
                circleDot.setFill(Color.web("#ff6b35"));
                spotsLabel.setStyle("-fx-text-fill: #ff6b35; -fx-font-weight: bold;");
            }
            spotsLabel.setGraphic(circleDot);
        } else {
            Circle circleDot = new Circle(5);
            circleDot.setFill(Color.web("#dc3545"));
            spotsLabel.setText(" Complet");
            spotsLabel.setGraphic(circleDot);
            spotsLabel.setStyle("-fx-text-fill: #dc3545; -fx-font-weight: bold;");
        }

        participantsBox.getChildren().add(spotsLabel);

        Button actionBtn = new Button();
        actionBtn.setPrefWidth(200);
        actionBtn.setStyle("-fx-font-weight: bold;");

        try {
            boolean isRegistered = serviceParticipation.isRegistered(event.getId_evenement(), currentUser.getIdUtilisateur());
            if (isRegistered) {
                actionBtn.setText("Voir les détails");
                actionBtn.getStyleClass().add("btn-secondary");
                actionBtn.setOnAction(e -> navigateToEventDetail(event));
            } else if (event.getSpotsLeft() > 0) {
                actionBtn.setText("S'inscrire");
                actionBtn.getStyleClass().add("btn-primary");
                actionBtn.setOnAction(e -> handleRegister(event));
            } else {
                actionBtn.setText("Liste d'attente");
                actionBtn.getStyleClass().add("btn-secondary");
                actionBtn.setDisable(true);
            }
        } catch (SQLException ex) {
            actionBtn.setText("Erreur");
            actionBtn.setDisable(true);
        }

        card.getChildren().addAll(imgView, title, dateLocBox, participantsBox, actionBtn);
        return card;
    }

    private void handleEventClick(JPO event) {
        navigateToEventDetail(event);
    }

    private void navigateToEventDetail(JPO event) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/InvestorEventDetail.fxml"));
            Parent detailView = loader.load();

            tn.cashfly.jpo.controllers.InvestorEventDetailController controller = loader.getController();
            controller.setEvent(event);

            javafx.stage.Stage stage = new javafx.stage.Stage();
            stage.setTitle(event.getTitre());
            stage.setScene(new javafx.scene.Scene(detailView));
            stage.show();

        } catch (IOException e) {
            showAlert("Erreur", "Impossible de charger les détails de l'événement: " + e.getMessage());
        }
    }

    private void handleRegister(JPO event) {
        try {
            boolean confirmed = serviceParticipation.register(event.getId_evenement(), currentUser.getIdUtilisateur());
            showAlert(confirmed ? "✅ Inscription confirmée" : "⏳ Liste d'attente",
                    confirmed ? "Vous êtes inscrit à \"" + event.getTitre() + "\" !"
                            : "Événement complet. Vous êtes en liste d'attente.");
            loadEvents();
        } catch (SQLException e) {
            showAlert("❌ Erreur", e.getMessage());
        }
    }

    private String formatDate(Date date) {
        LocalDateTime dateTime = LocalDateTime.ofInstant(
                Instant.ofEpochMilli(date.getTime()),
                ZoneId.systemDefault());
        return dateTime.format(DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm"));
    }

    private void showAlert(String header, String content) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle("CashFly JPO");
        alert.setHeaderText(header);
        alert.setContentText(content);
        alert.showAndWait();
    }
}
