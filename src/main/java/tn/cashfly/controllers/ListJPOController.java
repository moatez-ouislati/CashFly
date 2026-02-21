package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.ScrollPane;
import javafx.scene.image.ImageView;
import javafx.scene.layout.GridPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.scene.paint.Color;
import javafx.stage.Stage;
import tn.cashfly.entities.JPO;
import tn.cashfly.services.ServiceJPO;
import tn.cashfly.utils.ImageStorage;
import tn.cashfly.utils.NavigationUtil;
import tn.cashfly.utils.SessionManager;

import java.io.IOException;
import java.sql.SQLException;
import java.time.LocalDate;
import java.time.YearMonth;
import java.time.format.DateTimeFormatter;
import java.util.Date;
import java.util.List;
import java.util.stream.Collectors;

public class ListJPOController {

    @FXML private Button backButton;
    @FXML private Label monthLabel;
    @FXML private Label yearLabel;
    @FXML private GridPane calendarGrid;
    @FXML private Label selectedDateTitle;
    @FXML private VBox eventsContainer;
    @FXML private Label noEventsLabel;
    @FXML private VBox eventPanel;
    @FXML private ScrollPane eventsScrollPane;

    private ServiceJPO serviceJPO;
    private List<JPO> allEvents;
    private YearMonth currentYearMonth;
    private static final DateTimeFormatter MONTH_FORMATTER = DateTimeFormatter.ofPattern("MMMM yyyy");

    @FXML
    public void initialize() {
        serviceJPO = new ServiceJPO();
        currentYearMonth = YearMonth.now();
        loadEvents();
        buildCalendar();
        showNoSelection();
    }

    @FXML
    private void handleLogout() {
        SessionManager.clearSession();
        try {
            NavigationUtil.navigateTo((Stage) backButton.getScene().getWindow(), "RoleSelector.fxml");
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void loadEvents() {
        try {
            allEvents = serviceJPO.getAll();
        } catch (SQLException e) {
            allEvents = javafx.collections.FXCollections.observableArrayList();
        }
    }

    private void buildCalendar() {
        calendarGrid.getChildren().clear();
        monthLabel.setText(currentYearMonth.getMonth().toString());
        yearLabel.setText(String.valueOf(currentYearMonth.getYear()));

        String[] days = {"Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"};
        for (int i = 0; i < 7; i++) {
            Label dayLabel = new Label(days[i]);
            dayLabel.getStyleClass().add("calendar-header");
            calendarGrid.add(dayLabel, i, 0);
        }

        LocalDate firstOfMonth = currentYearMonth.atDay(1);
        int dayOfWeek = firstOfMonth.getDayOfWeek().getValue();
        int daysInMonth = currentYearMonth.lengthOfMonth();
        int row = 1;
        int col = dayOfWeek - 1;

        for (int day = 1; day <= daysInMonth; day++) {
            LocalDate date = currentYearMonth.atDay(day);
            Button dayBtn = createDayButton(date);
            calendarGrid.add(dayBtn, col, row);

            col++;
            if (col > 6) {
                col = 0;
                row++;
            }
        }
    }

    private Button createDayButton(LocalDate date) {
        Button btn = new Button();
        btn.setMaxSize(Double.MAX_VALUE, Double.MAX_VALUE);
        btn.setMinSize(80, 70);

        VBox content = new VBox(2);
        content.setAlignment(javafx.geometry.Pos.CENTER);

        Label dayNum = new Label(String.valueOf(date.getDayOfMonth()));
        dayNum.setStyle("-fx-font-size: 18px; -fx-font-weight: bold;");
        content.getChildren().add(dayNum);

        long eventCount = allEvents.stream()
                .filter(jpo -> {
                    Date d = new Date(jpo.getDate_evenement().getTime());
                    LocalDate eventDate = new java.util.Date(d.getTime()).toInstant()
                            .atZone(java.time.ZoneId.systemDefault()).toLocalDate();
                    return eventDate.equals(date);
                })
                .count();

        if (eventCount > 0) {
            btn.getStyleClass().add("calendar-day-has-event");
            Label indicator = new Label("● " + eventCount + " événement" + (eventCount > 1 ? "s" : ""));
            indicator.setStyle("-fx-font-size: 10px;");
            indicator.getStyleClass().add("text-muted");
            content.getChildren().add(indicator);
        } else {
            btn.getStyleClass().add("calendar-day");
        }

        if (date.equals(LocalDate.now())) {
            btn.getStyleClass().add("calendar-day-today");
        }

        btn.setGraphic(content);
        btn.setContentDisplay(javafx.scene.control.ContentDisplay.GRAPHIC_ONLY);
        btn.setOnAction(e -> showDayEvents(date));
        return btn;
    }

    private void showDayEvents(LocalDate date) {
        selectedDateTitle.setText("Événements du " + date.format(DateTimeFormatter.ofPattern("dd MMMM yyyy")));
        eventsContainer.getChildren().clear();

        List<JPO> events = allEvents.stream()
                .filter(jpo -> {
                    Date d = new Date(jpo.getDate_evenement().getTime());
                    LocalDate eventDate = new java.util.Date(d.getTime()).toInstant()
                            .atZone(java.time.ZoneId.systemDefault()).toLocalDate();
                    return eventDate.equals(date);
                })
                .collect(Collectors.toList());

        if (events.isEmpty()) {
            showNoEvents();
        } else {
            noEventsLabel.setVisible(false);
            for (JPO jpo : events) {
                eventsContainer.getChildren().add(createEventCard(jpo));
            }
        }
    }

    private void showNoSelection() {
        selectedDateTitle.setText("Sélectionnez une date");
        noEventsLabel.setText("Cliquez sur une date du calendrier pour voir les événements");
        noEventsLabel.setVisible(true);
        eventsContainer.getChildren().clear();
    }

    private void showNoEvents() {
        noEventsLabel.setText("Aucun événement pour cette date");
        noEventsLabel.setVisible(true);
    }

    /**
     * Creates an enhanced event card with participant count indicator
     */
    private VBox createEventCard(JPO jpo) {
        VBox card = new VBox(10);
        card.setPadding(new javafx.geometry.Insets(15));
        card.getStyleClass().add("event-detail-card");

        // Image
        ImageView imgView = new ImageView(ImageStorage.loadImage(jpo.getImagePath()));
        imgView.setFitHeight(150);
        imgView.setFitWidth(280);
        imgView.setPreserveRatio(true);

        // Title
        Label title = new Label(jpo.getTitre());
        title.getStyleClass().add("event-detail-title");
        title.setWrapText(true);

        // Location with participant count
        HBox locationBox = new HBox(10);
        locationBox.setAlignment(javafx.geometry.Pos.CENTER_LEFT);

        Label lieu = new Label("📍 " + jpo.getLieu());
        lieu.getStyleClass().add("event-detail-location");

        // Participant count indicator
        Label participantsLabel = createParticipantLabel(jpo);

        locationBox.getChildren().addAll(lieu, participantsLabel);

        // Description
        if (jpo.getDescription() != null && !jpo.getDescription().isEmpty()) {
            Label desc = new Label(jpo.getDescription());
            desc.getStyleClass().add("text-muted");
            desc.setWrapText(true);
            card.getChildren().addAll(imgView, title, locationBox, desc);
        } else {
            card.getChildren().addAll(imgView, title, locationBox);
        }

        return card;
    }

    /**
     * Creates a colored label showing participant status
     * Green: >50% spots available
     * Orange: <50% spots available (but not full)
     * Red: Full (or overbooked)
     */
    private Label createParticipantLabel(JPO jpo) {
        int max = jpo.getMaxParticipants();
        int current = jpo.getCurrentParticipants();
        int available = jpo.getSpotsLeft();

        Label label = new Label();
        label.setStyle("-fx-font-weight: bold; -fx-padding: 2 8; -fx-background-radius: 12;");

        if (max <= 0) {
            // Fallback if max participants not set
            label.setText("👥 " + current + " participants");
            label.setStyle(label.getStyle() + "-fx-text-fill: #2E5E99; -fx-background-color: #E7F0FA;");
            return label;
        }

        double ratio = (double) current / max;

        if (available <= 0) {
            // FULL - Red
            label.setText("🔴 Complet (" + current + "/" + max + ")");
            label.setStyle(label.getStyle() + "-fx-text-fill: white; -fx-background-color: #dc3545;");
        } else if (ratio >= 0.8) {
            // NEARLY FULL (>80%) - Orange
            label.setText(available + " places restantes (" + current + "/" + max + ")");
            label.setStyle(label.getStyle() + "-fx-text-fill: white; -fx-background-color: #fd7e14;");
        } else if (ratio >= 0.5) {
            // MODERATE (50-80%) - Yellow/Orange
            label.setText(available + " places (" + current + "/" + max + ")");
            label.setStyle(label.getStyle() + "-fx-text-fill: #0D2440; -fx-background-color: #ffc107;");
        } else {
            // PLENTY AVAILABLE (<50%) - Green
            label.setText(available + " places disponibles (" + current + "/" + max + ")");
            label.setStyle(label.getStyle() + "-fx-text-fill: white; -fx-background-color: #28a745;");
        }

        return label;
    }

    @FXML
    private void previousMonth() {
        currentYearMonth = currentYearMonth.minusMonths(1);
        buildCalendar();
        showNoSelection();
    }

    @FXML
    private void nextMonth() {
        currentYearMonth = currentYearMonth.plusMonths(1);
        buildCalendar();
        showNoSelection();
    }

    @FXML
    private void handleBack() {
        try {
            NavigationUtil.navigateTo((Stage) backButton.getScene().getWindow(), "MainJPO.fxml");
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}