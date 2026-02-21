package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.ButtonType;
import javafx.scene.control.ComboBox;
import javafx.scene.control.Label;
import javafx.scene.control.ScrollPane;
import javafx.scene.control.TextField;
import javafx.scene.image.ImageView;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Priority;
import javafx.scene.layout.Region;
import javafx.scene.layout.TilePane;
import javafx.scene.layout.VBox;
import tn.cashfly.entities.JPO;
import tn.cashfly.entities.Utilisateur;
import tn.cashfly.services.ServiceJPO;
import tn.cashfly.services.ServiceParticipation;
import tn.cashfly.utils.ImageStorage;
import tn.cashfly.utils.SessionManager;

import java.sql.SQLException;
import java.time.Instant;
import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.Comparator;
import java.util.Date;
import java.util.List;
import java.util.Optional;
import java.util.stream.Collectors;

public class InvestorListAllJPOController {

    @FXML private TextField searchField;
    @FXML private ComboBox<String> sortComboBox;
    @FXML private ComboBox<String> filterAvailabilityCombo;
    @FXML private Button applyFiltersBtn;
    @FXML private TilePane eventsTilePane;
    @FXML private ScrollPane eventsScrollPane;
    @FXML private Label resultCountLabel;

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
                "Nom (A → Z)"
        );
        sortComboBox.setValue("Date (prochain → lointain)");

        filterAvailabilityCombo.getItems().addAll(
                "Tous les événements",
                "Places disponibles",
                "Complet / Liste d'attente"
        );
        filterAvailabilityCombo.setValue("Tous les événements");

        applyFiltersBtn.setOnAction(e -> applyFilters());
        sortComboBox.setOnAction(e -> applyFilters());
        filterAvailabilityCombo.setOnAction(e -> applyFilters());

        searchField.textProperty().addListener((obs, old, newVal) -> applyFilters());
    }

    private void loadEvents() {
        try {
            allEvents = serviceJPO.getAll();
            // Only future events
            allEvents = allEvents.stream()
                    .filter(jpo -> jpo.getDate_evenement().after(new Date()))
                    .collect(Collectors.toList());
            filteredEvents = new ArrayList<>(allEvents);
            applyFilters();
        } catch (SQLException e) {
            e.printStackTrace();
            showAlert("Erreur", "Impossible de charger les événements: " + e.getMessage());
        }
    }

    private void applyFilters() {
        if (allEvents == null) return;

        filteredEvents = new ArrayList<>(allEvents);

        // Search filter
        String search = searchField.getText().toLowerCase().trim();
        if (!search.isEmpty()) {
            filteredEvents = filteredEvents.stream()
                    .filter(e -> e.getTitre().toLowerCase().contains(search) ||
                            e.getLieu().toLowerCase().contains(search) ||
                            (e.getDescription() != null && e.getDescription().toLowerCase().contains(search)))
                    .collect(Collectors.toList());
        }

        // Availability filter
        String availability = filterAvailabilityCombo.getValue();
        if (availability != null) {
            switch (availability) {
                case "Places disponibles":
                    filteredEvents = filteredEvents.stream()
                            .filter(e -> e.getSpotsLeft() > 0)
                            .collect(Collectors.toList());
                    break;
                case "Complet / Liste d'attente":
                    filteredEvents = filteredEvents.stream()
                            .filter(e -> e.getSpotsLeft() <= 0)
                            .collect(Collectors.toList());
                    break;
            }
        }

        // Sorting
        String sort = sortComboBox.getValue();
        if (sort != null) {
            Comparator<JPO> comparator;
            switch (sort) {
                case "Date (prochain → lointain)":
                    comparator = Comparator.comparing(JPO::getDate_evenement);
                    break;
                case "Date (lointain → prochain)":
                    comparator = Comparator.comparing(JPO::getDate_evenement).reversed();
                    break;
                case "Places disponibles (plus → moins)":
                    comparator = Comparator.comparing(JPO::getSpotsLeft).reversed();
                    break;
                case "Places disponibles (moins → plus)":
                    comparator = Comparator.comparing(JPO::getSpotsLeft);
                    break;
                case "Nom (A → Z)":
                    comparator = Comparator.comparing(j -> j.getTitre().toLowerCase());
                    break;
                default:
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

        // Image
        ImageView imgView = new ImageView(ImageStorage.loadImage(event.getImagePath()));
        imgView.setFitHeight(150);
        imgView.setFitWidth(270);
        imgView.setPreserveRatio(true);

        // Title
        Label title = new Label(event.getTitre());
        title.setStyle("-fx-font-size: 16px; -fx-font-weight: bold;");
        title.setWrapText(true);

        // Date & Location - FIXED conversion
        String dateStr = formatDate(event.getDate_evenement());
        Label dateLoc = new Label("📅 " + dateStr + "\n📍 " + event.getLieu());
        dateLoc.setStyle("-fx-font-size: 13px; -fx-text-fill: -color-fg-muted;");
        dateLoc.setWrapText(true);

        // Participants indicator
        HBox participantsBox = new HBox(10);
        participantsBox.setAlignment(Pos.CENTER_LEFT);

        int spots = event.getSpotsLeft();
        int max = event.getMaxParticipants();
        Label spotsLabel = new Label();

        if (spots > 0) {
            double ratio = (double) (max - spots) / max;
            if (ratio < 0.5) {
                spotsLabel.setText("🟢 " + spots + " places disponibles");
                spotsLabel.setStyle("-fx-text-fill: #28a745; -fx-font-weight: bold;");
            } else if (ratio < 0.8) {
                spotsLabel.setText("🟡 " + spots + " places restantes");
                spotsLabel.setStyle("-fx-text-fill: #fd7e14; -fx-font-weight: bold;");
            } else {
                spotsLabel.setText("🟠 " + spots + " places restantes");
                spotsLabel.setStyle("-fx-text-fill: #ff6b35; -fx-font-weight: bold;");
            }
        } else {
            spotsLabel.setText("🔴 Complet - Liste d'attente");
            spotsLabel.setStyle("-fx-text-fill: #dc3545; -fx-font-weight: bold;");
        }
        participantsBox.getChildren().add(spotsLabel);

        // Action Button
        Button actionBtn = new Button();
        actionBtn.setPrefWidth(Double.MAX_VALUE);
        actionBtn.setStyle("-fx-padding: 10;");

        try {
            boolean isRegistered = serviceParticipation.isRegistered(event.getId_evenement(), currentUser.getIdUtilisateur());

            if (isRegistered) {
                actionBtn.setText("✓ Inscrit - Se désinscrire");
                actionBtn.getStyleClass().add("btn-danger");
                final JPO evt = event;
                actionBtn.setOnAction(e -> handleCancel(evt));
            } else if (spots > 0) {
                actionBtn.setText("S'inscrire");
                actionBtn.getStyleClass().add("btn-primary");
                final JPO evt = event;
                actionBtn.setOnAction(e -> handleRegister(evt));
            } else {
                actionBtn.setText("Rejoindre la liste d'attente");
                actionBtn.getStyleClass().add("btn-secondary");
                final JPO evt = event;
                actionBtn.setOnAction(e -> handleRegister(evt));
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
            actionBtn.setText("Erreur");
            actionBtn.setDisable(true);
        }

        card.getChildren().addAll(imgView, title, dateLoc, participantsBox, actionBtn);
        return card;
    }

    /**
     * FIXED: Convert java.util.Date (which might actually be java.sql.Date) to LocalDateTime
     * java.sql.Date does NOT support toInstant(), so we use getTime() instead
     */
    private String formatDate(Date date) {
        // Use getTime() which works for both java.util.Date and java.sql.Date
        LocalDateTime dateTime = LocalDateTime.ofInstant(
                Instant.ofEpochMilli(date.getTime()),
                ZoneId.systemDefault()
        );
        return dateTime.format(DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm"));
    }

    /**
     * FIXED: Convert Date to LocalDateTime for comparison
     */
    private LocalDateTime convertToLocalDateTime(Date date) {
        // Use getTime() which works for both java.util.Date and java.sql.Date
        return LocalDateTime.ofInstant(
                Instant.ofEpochMilli(date.getTime()),
                ZoneId.systemDefault()
        );
    }

    private void handleRegister(JPO event) {
        try {
            boolean confirmed = serviceParticipation.register(event.getId_evenement(), currentUser.getIdUtilisateur());
            showAlert(confirmed ? "✅ Inscription confirmée" : "⏳ Liste d'attente",
                    confirmed ? "Vous êtes inscrit à \"" + event.getTitre() + "\" !"
                            : "Événement complet. Vous êtes en liste d'attente.");
            loadEvents(); // Refresh
        } catch (SQLException e) {
            showAlert("❌ Erreur", e.getMessage());
        }
    }

    private void handleCancel(JPO event) {
        // FIXED: Use proper conversion method that handles java.sql.Date
        LocalDateTime eventDate = convertToLocalDateTime(event.getDate_evenement());

        if (LocalDateTime.now().plusHours(24).isAfter(eventDate)) {
            showAlert("⛔ Impossible", "Désinscription impossible moins de 24h avant l'événement.");
            return;
        }

        Alert confirm = new Alert(Alert.AlertType.CONFIRMATION);
        confirm.setTitle("Confirmation");
        confirm.setHeaderText("Se désinscrire de \"" + event.getTitre() + "\" ?");
        confirm.setContentText("Cette action est irréversible.");

        Optional<ButtonType> result = confirm.showAndWait();
        if (result.isPresent() && result.get() == ButtonType.OK) {
            try {
                serviceParticipation.cancel(event.getId_evenement(), currentUser.getIdUtilisateur());
                showAlert("✅ Désinscription confirmée", "Vous êtes désinscrit de l'événement.");
                loadEvents(); // Refresh
            } catch (SQLException e) {
                showAlert("❌ Erreur", "Impossible de se désinscrire: " + e.getMessage());
            }
        }
    }

    private void showAlert(String header, String content) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle("CashFly JPO");
        alert.setHeaderText(header);
        alert.setContentText(content);
        alert.showAndWait();
    }
}