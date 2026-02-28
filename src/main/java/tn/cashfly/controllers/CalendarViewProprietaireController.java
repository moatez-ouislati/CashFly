package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.input.KeyCode;
import javafx.scene.layout.*;
import javafx.scene.paint.Color;
import javafx.scene.shape.Circle;
import javafx.scene.shape.Rectangle;
import javafx.scene.text.Font;
import javafx.scene.text.FontWeight;
import org.kordamp.ikonli.javafx.FontIcon;
import javafx.stage.Modality;
import javafx.stage.Stage;
import javafx.stage.StageStyle;
import javafx.stage.DirectoryChooser;
import java.net.URL;
import tn.cashfly.entities.JPO;
import tn.cashfly.entities.Utilisateur;
import tn.cashfly.services.ServiceJPO;
import tn.cashfly.services.ServiceParticipation;
import tn.cashfly.utils.ImageStorage;
import tn.cashfly.utils.SessionManager;

import java.io.File;
import java.sql.SQLException;
import java.time.LocalDate;
import java.time.LocalTime;
import java.time.YearMonth;
import java.time.format.DateTimeFormatter;
import java.util.*;
import java.util.List;
import java.util.stream.Collectors;
import javafx.scene.image.ImageView;
import javafx.scene.image.Image;
import tn.cashfly.services.ExcelExportService;
import tn.cashfly.entities.EventStatistics;
import tn.cashfly.entities.ParticipantInfo;
import java.io.IOException;

public class CalendarViewProprietaireController {

    @FXML
    private GridPane calendarGrid;
    @FXML
    private Label monthYearLabel;
    @FXML
    private Button prevMonthBtn;
    @FXML
    private Button nextMonthBtn;
    @FXML
    private Button todayBtn;

    private ServiceJPO serviceJPO;
    private Utilisateur currentUser;
    private MainJPOController mainController;

    private YearMonth currentYearMonth;
    private List<JPO> allEvents;
    private Map<LocalDate, List<JPO>> eventsByDate;
    private Stage currentPopup;

    @FXML
    public void initialize() {
        currentUser = SessionManager.getCurrentUser();
        serviceJPO = new ServiceJPO();
        currentYearMonth = YearMonth.now();

        if (currentUser == null || !"proprietaire".equals(currentUser.getRole())) {
            handleUnauthorized();
            return;
        }

        setupButtons();
        loadEvents();
        buildCalendar();
    }

    public void setMainController(MainJPOController mainController) {
        this.mainController = mainController;
    }

    private void setupButtons() {
        prevMonthBtn.setOnAction(e -> {
            currentYearMonth = currentYearMonth.minusMonths(1);
            buildCalendar();
        });

        nextMonthBtn.setOnAction(e -> {
            currentYearMonth = currentYearMonth.plusMonths(1);
            buildCalendar();
        });

        todayBtn.setOnAction(e -> {
            currentYearMonth = YearMonth.now();
            buildCalendar();
        });
    }

    private void loadEvents() {
        try {
            allEvents = serviceJPO.getAll();
            eventsByDate = new HashMap<>();
            for (JPO event : allEvents) {
                LocalDate date = new java.sql.Date(event.getDate_evenement().getTime())
                        .toLocalDate();
                eventsByDate.computeIfAbsent(date, k -> new ArrayList<>()).add(event);
            }
        } catch (SQLException e) {
            allEvents = new ArrayList<>();
            eventsByDate = new HashMap<>();
        }
    }

    private void buildCalendar() {
        calendarGrid.getChildren().clear();

        monthYearLabel.setText(currentYearMonth.format(
                DateTimeFormatter.ofPattern("MMMM yyyy", Locale.FRENCH)).toUpperCase());

        String[] dayNames = { "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim" };
        for (int i = 0; i < 7; i++) {
            Label dayLabel = new Label(dayNames[i]);
            dayLabel.setFont(Font.font("System", FontWeight.BOLD, 14));
            dayLabel.setTextFill(Color.web("#666"));
            dayLabel.setAlignment(Pos.CENTER);
            dayLabel.setMaxWidth(Double.MAX_VALUE);
            GridPane.setHgrow(dayLabel, Priority.ALWAYS);
            calendarGrid.add(dayLabel, i, 0);
        }

        LocalDate firstOfMonth = currentYearMonth.atDay(1);
        int dayOfWeek = firstOfMonth.getDayOfWeek().getValue();
        int daysInMonth = currentYearMonth.lengthOfMonth();

        int row = 1;
        int col = dayOfWeek - 1;

        YearMonth prevMonth = currentYearMonth.minusMonths(1);
        int daysInPrevMonth = prevMonth.lengthOfMonth();
        for (int i = col - 1; i >= 0; i--) {
            int day = daysInPrevMonth - (col - 1 - i);
            LocalDate date = prevMonth.atDay(day);
            StackPane dayCell = createDayCell(date, true);
            calendarGrid.add(dayCell, i, row);
        }

        for (int day = 1; day <= daysInMonth; day++) {
            LocalDate date = currentYearMonth.atDay(day);
            StackPane dayCell = createDayCell(date, false);
            calendarGrid.add(dayCell, col, row);

            col++;
            if (col > 6) {
                col = 0;
                row++;
            }
        }

        int nextMonthDay = 1;
        while (row <= 6 && col <= 6) {
            LocalDate date = currentYearMonth.plusMonths(1).atDay(nextMonthDay);
            StackPane dayCell = createDayCell(date, true);
            calendarGrid.add(dayCell, col, row);
            nextMonthDay++;
            col++;
            if (col > 6) {
                col = 0;
                row++;
            }
        }
    }

    private StackPane createDayCell(LocalDate date, boolean isOtherMonth) {
        StackPane cell = new StackPane();
        cell.setPrefSize(140, 100);
        cell.getStyleClass().add("calendar-day-cell");

        if (isOtherMonth) {
            cell.setOpacity(0.4);
            cell.getStyleClass().add("other-month");
        }

        if (date.equals(LocalDate.now())) {
            cell.getStyleClass().add("today");
        }

        Label dayLabel = new Label(String.valueOf(date.getDayOfMonth()));
        dayLabel.setFont(Font.font("System", date.equals(LocalDate.now()) ? FontWeight.BOLD : FontWeight.NORMAL, 16));
        dayLabel.setTextFill(date.equals(LocalDate.now()) ? Color.web("#2E5E99") : Color.web("#333"));
        StackPane.setAlignment(dayLabel, Pos.TOP_LEFT);
        StackPane.setMargin(dayLabel, new Insets(8, 0, 0, 12));

        cell.getChildren().add(dayLabel);

        List<JPO> dayEvents = eventsByDate.getOrDefault(date, new ArrayList<>());

        if (dayEvents.isEmpty()) {
            Label plusLabel = new Label("+");
            plusLabel.setFont(Font.font("System", FontWeight.BOLD, 24));
            plusLabel.setTextFill(Color.web("#2E5E99"));
            plusLabel.setOpacity(0);
            StackPane.setAlignment(plusLabel, Pos.CENTER);

            cell.setOnMouseEntered(e -> plusLabel.setOpacity(0.3));
            cell.setOnMouseExited(e -> plusLabel.setOpacity(0));

            cell.getChildren().add(plusLabel);
        } else {
            FlowPane squaresPane = new FlowPane();
            squaresPane.setAlignment(Pos.BOTTOM_CENTER);
            squaresPane.setPadding(new Insets(0, 5, 8, 5));
            squaresPane.setHgap(3);
            squaresPane.setVgap(3);
            squaresPane.setPrefWrapLength(120);

            int displayCount = Math.min(dayEvents.size(), 4);
            for (int i = 0; i < displayCount; i++) {
                JPO event = dayEvents.get(i);
                Rectangle square = new Rectangle(12, 12);
                square.setFill(Color.web(getEventColor(event)));
                square.setArcWidth(3);
                square.setArcHeight(3);
                squaresPane.getChildren().add(square);
            }

            if (dayEvents.size() > 4) {
                Label moreLabel = new Label("+" + (dayEvents.size() - 4));
                moreLabel.setFont(Font.font("System", 9));
                moreLabel.setTextFill(Color.web("#666"));
                squaresPane.getChildren().add(moreLabel);
            }

            cell.getChildren().add(squaresPane);
            cell.getStyleClass().add("has-events");
        }

        cell.setOnMouseClicked(e -> handleDayClick(date, dayEvents));

        return cell;
    }

    private void handleDayClick(LocalDate date, List<JPO> dayEvents) {
        if (currentPopup != null && currentPopup.isShowing()) {
            currentPopup.close();
        }

        if (dayEvents.isEmpty()) {
            // No events - directly show create dialog
            showCreateEventDialog(date);
        } else {
            // Has events - show action choice dialog
            showDayActionDialog(date, dayEvents);
        }
    }

    private void showCreateEventDialog(LocalDate date) {
        if (currentPopup != null && currentPopup.isShowing()) {
            currentPopup.close();
        }

        currentPopup = new Stage();
        currentPopup.initModality(Modality.APPLICATION_MODAL);
        currentPopup.initStyle(StageStyle.UNDECORATED);
        currentPopup.setTitle("Nouvelle JPO");

        VBox content = new VBox(15);
        content.setPadding(new Insets(25));
        content.setStyle("-fx-background-color: white; -fx-background-radius: 16px; " +
                "-fx-border-radius: 16px; -fx-border-color: #e0e4e8; -fx-border-width: 1px; " +
                "-fx-effect: dropshadow(gaussian, rgba(0,0,0,0.15), 20, 0, 0, 8);");
        content.setPrefWidth(450);

        HBox header = new HBox(10);
        header.setAlignment(Pos.CENTER_LEFT);
        Label icon = new Label("📅");
        icon.setFont(Font.font("System", 24));
        Label title = new Label("Nouvelle Journée Portes Ouvertes");
        title.setFont(Font.font("System", FontWeight.BOLD, 18));
        title.setTextFill(Color.web("#0D2440"));
        header.getChildren().addAll(icon, title);

        Label dateLabel = new Label(date.format(DateTimeFormatter.ofPattern(
                "EEEE dd MMMM yyyy", Locale.FRENCH)));
        dateLabel.setFont(Font.font("System", 14));
        dateLabel.setTextFill(Color.web("#2E5E99"));

        VBox form = new VBox(10);

        Label lblTitle = new Label("Titre *");
        TextField titleField = new TextField();
        titleField.setPromptText("Ex: JPO Startup Tunis");

        Label lblLocation = new Label("Lieu *");
        TextField locationField = new TextField();
        locationField.setPromptText("Ex: Technopole El Ghazela");

        Label lblTime = new Label("Heure");
        ComboBox<String> timeCombo = new ComboBox<>();
        timeCombo.getItems().addAll(
                "08:00", "09:00", "10:00", "11:00", "12:00",
                "13:00", "14:00", "15:00", "16:00", "17:00", "18:00");
        timeCombo.setValue("09:00");

        Label lblDesc = new Label("Description");
        TextArea descArea = new TextArea();
        descArea.setPromptText("Décrivez votre événement...");
        descArea.setPrefRowCount(3);

        Label lblMax = new Label("Nombre max de participants *");
        Spinner<Integer> maxParticipants = new Spinner<>(1, 1000, 50);
        maxParticipants.setEditable(true);

        Label lblImage = new Label("Image de l'événement");
        HBox imageBox = new HBox(10);
        Button chooseImageBtn = new Button("📷 Choisir une image");
        Label imageNameLabel = new Label("Aucune image sélectionnée");
        imageNameLabel.setTextFill(Color.web("#666"));
        imageBox.getChildren().addAll(chooseImageBtn, imageNameLabel);

        final File[] selectedImage = { null };
        chooseImageBtn.setOnAction(e -> {
            javafx.stage.FileChooser chooser = new javafx.stage.FileChooser();
            chooser.getExtensionFilters().add(
                    new javafx.stage.FileChooser.ExtensionFilter("Images", "*.png", "*.jpg", "*.jpeg"));
            File file = chooser.showOpenDialog(currentPopup);
            if (file != null) {
                selectedImage[0] = file;
                imageNameLabel.setText(file.getName());
            }
        });

        form.getChildren().addAll(
                lblTitle, titleField,
                lblLocation, locationField,
                lblTime, timeCombo,
                lblDesc, descArea,
                lblMax, maxParticipants,
                lblImage, imageBox);

        HBox buttons = new HBox(10);
        buttons.setAlignment(Pos.CENTER_RIGHT);
        buttons.setPadding(new Insets(10, 0, 0, 0));

        Button cancelBtn = new Button("❌ Annuler");
        cancelBtn.setStyle("-fx-background-color: #6c757d; -fx-text-fill: white; -fx-background-radius: 8px;");
        cancelBtn.setOnAction(e -> currentPopup.close());

        Button createBtn = new Button("✅ Créer");
        createBtn.setStyle(
                "-fx-background-color: #28a745; -fx-text-fill: white; -fx-font-weight: bold; -fx-background-radius: 8px;");
        createBtn.setOnAction(e -> {
            if (titleField.getText().trim().isEmpty() ||
                    locationField.getText().trim().isEmpty()) {
                showAlert("Champs requis", "Veuillez remplir le titre et le lieu.");
                return;
            }

            createEvent(date, timeCombo.getValue(), titleField.getText(),
                    locationField.getText(), descArea.getText(),
                    maxParticipants.getValue(), selectedImage[0]);
            currentPopup.close();
            loadEvents();
            buildCalendar();
        });

        buttons.getChildren().addAll(cancelBtn, createBtn);

        content.getChildren().addAll(header, new Separator(), dateLabel, form, buttons);

        Scene scene = new Scene(content);
        scene.setFill(Color.TRANSPARENT);
        currentPopup.setScene(scene);
        currentPopup.show();
    }

    private void showEventDetailsPopup(JPO event) {
        if (currentPopup != null && currentPopup.isShowing()) {
            currentPopup.close();
        }

        currentPopup = new Stage();
        currentPopup.initModality(Modality.APPLICATION_MODAL);
        currentPopup.initStyle(StageStyle.UNDECORATED);
        currentPopup.setTitle("Détails JPO");

        // Main container with padding for the close button area
        StackPane rootContainer = new StackPane();
        rootContainer.setStyle("-fx-background-color: transparent;");

        // Content container
        VBox content = new VBox(12);
        content.setPadding(new Insets(20));
        content.setStyle("-fx-background-color: white; -fx-background-radius: 16px; " +
                "-fx-border-radius: 16px; -fx-border-color: #e0e4e8; -fx-border-width: 1px; " +
                "-fx-effect: dropshadow(gaussian, rgba(0,0,0,0.15), 20, 0, 0, 8);");
        content.setPrefWidth(550);
        content.setMaxHeight(600);

        // === CLOSE BUTTON (X) in top right ===
        Button closeXBtn = new Button("✕");
        closeXBtn.setStyle("-fx-background-color: transparent; -fx-text-fill: #666; " +
                "-fx-font-size: 18px; -fx-font-weight: bold; -fx-cursor: hand; " +
                "-fx-padding: 5 10; -fx-background-radius: 20;");
        closeXBtn.setOnMouseEntered(e -> closeXBtn.setStyle("-fx-background-color: #ff4444; " +
                "-fx-text-fill: white; -fx-font-size: 18px; -fx-font-weight: bold; " +
                "-fx-cursor: hand; -fx-padding: 5 10; -fx-background-radius: 20;"));
        closeXBtn.setOnMouseExited(e -> closeXBtn.setStyle("-fx-background-color: transparent; " +
                "-fx-text-fill: #666; -fx-font-size: 18px; -fx-font-weight: bold; " +
                "-fx-cursor: hand; -fx-padding: 5 10; -fx-background-radius: 20;"));
        closeXBtn.setOnAction(e -> currentPopup.close());

        // Position the X button in top right
        StackPane.setAlignment(closeXBtn, Pos.TOP_RIGHT);
        StackPane.setMargin(closeXBtn, new Insets(10, 15, 0, 0));

        // Prevent clicks on content from triggering close
        closeXBtn.setPickOnBounds(false);

        // HEADER
        HBox header = new HBox(10);
        header.setAlignment(Pos.CENTER_LEFT);
        Circle colorCircle = new Circle(8, Color.web(getEventColor(event)));
        VBox titleBox = new VBox(2);
        Label titleLabel = new Label(event.getTitre());
        titleLabel.setFont(Font.font("System", FontWeight.BOLD, 16));
        titleLabel.setWrapText(true);
        titleBox.getChildren().addAll(titleLabel);
        header.getChildren().addAll(colorCircle, titleBox);

        // === COMPACT HBOX: Image + Details ===
        HBox mainInfoBox = new HBox(15);
        mainInfoBox.setAlignment(Pos.TOP_LEFT);

        // LEFT: Small Image (HALF SIZE)
        ImageView eventImageView = new ImageView();
        eventImageView.setFitWidth(200);
        eventImageView.setFitHeight(100);
        eventImageView.setPreserveRatio(true);
        eventImageView.setStyle("-fx-background-radius: 6px;");

        // Load image
        if (event.getImagePath() != null && !event.getImagePath().isEmpty()) {
            Image image = ImageStorage.loadImage(event.getImagePath());
            if (image != null && !image.isError()) {
                eventImageView.setImage(image);
            } else {
                eventImageView.setImage(loadPlaceholderImage());
            }
        } else {
            eventImageView.setImage(loadPlaceholderImage());
        }

        // RIGHT: Event Details
        VBox detailsBox = new VBox(8);
        detailsBox.setAlignment(Pos.TOP_LEFT);
        HBox.setHgrow(detailsBox, Priority.ALWAYS);

        String dateStr = new java.sql.Date(event.getDate_evenement().getTime())
                .toLocalDate().format(DateTimeFormatter.ofPattern("EEEE dd MMMM yyyy", Locale.FRENCH));

        // Date
        HBox dateRow = new HBox(8);
        dateRow.setAlignment(Pos.CENTER_LEFT);
        dateRow.getChildren().addAll(new FontIcon("fas-calendar-alt"), new Label(dateStr));

        // Location
        HBox locationRow = new HBox(8);
        locationRow.setAlignment(Pos.CENTER_LEFT);
        locationRow.getChildren().addAll(new FontIcon("fas-map-marker-alt"), new Label(event.getLieu()));

        // Participants
        HBox participantsRow = new HBox(8);
        participantsRow.setAlignment(Pos.CENTER_LEFT);
        participantsRow.getChildren().addAll(
                new FontIcon("fas-users"),
                new Label(event.getCurrentParticipants() + "/" + event.getMaxParticipants() + " inscrits"));

        // Status
        HBox statusRow = new HBox(8);
        statusRow.setAlignment(Pos.CENTER_LEFT);
        String statusText = event.isFull() ? "COMPLET"
                : (event.getSpotsLeft() <= event.getMaxParticipants() * 0.2 ? "PRESQUE COMPLET" : "OUVERT");
        Label statusLabel = new Label(statusText);
        statusLabel.setStyle("-fx-background-color: " + getEventColor(event) + "; " +
                "-fx-text-fill: white; -fx-padding: 2 8; -fx-background-radius: 10; -fx-font-weight: bold;");
        statusRow.getChildren().addAll(new Label("Statut:"), statusLabel);

        detailsBox.getChildren().addAll(dateRow, locationRow, participantsRow, statusRow);
        mainInfoBox.getChildren().addAll(eventImageView, detailsBox);

        // Description
        VBox descBox = new VBox(5);
        Label descTitle = new Label("Description:");
        descTitle.setFont(Font.font("System", FontWeight.BOLD, 11));
        Label descLabel = new Label(event.getDescription() != null ? event.getDescription() : "Aucune description");
        descLabel.setWrapText(true);
        descBox.getChildren().addAll(descTitle, descLabel);

        // Buttons (without Fermer - only Edit, Delete, Export, Chat)
        HBox actions = new HBox(12);
        actions.setAlignment(Pos.CENTER);

        Button editBtn = new Button(" Modifier");
        editBtn.setGraphic(new FontIcon("fas-pen"));
        editBtn.setStyle(
                "-fx-background-color: #ffc107; -fx-text-fill: #0D2440; -fx-font-weight: bold; -fx-padding: 8 16; -fx-background-radius: 6;");

        Button deleteBtn = new Button("Supprimer");
        deleteBtn.setGraphic(new FontIcon("fas-trash-alt"));
        deleteBtn.setStyle(
                "-fx-background-color: #dc3545; -fx-text-fill: white; -fx-font-weight: bold; -fx-padding: 8 16; -fx-background-radius: 6;");

        Button exportBtn = new Button(" Exporter");
        exportBtn.setGraphic(new FontIcon("fas-file-excel"));
        exportBtn.setStyle(
                "-fx-background-color: #28a745; -fx-text-fill: white; -fx-font-weight: bold; -fx-padding: 8 16; -fx-background-radius: 6;");

        Button chatBtn = new Button(" Chat");
        chatBtn.setGraphic(new FontIcon("fas-comments"));
        chatBtn.setStyle(
                "-fx-background-color: #7BA4D0; -fx-text-fill: white; -fx-font-weight: bold; -fx-padding: 8 16; -fx-background-radius: 6;");

        // --- Permissions, Past Date & 24h Check ---
        int currentUserId = SessionManager.getCurrentUser().getIdUtilisateur();
        boolean isCreator = (event.getIdCreateur() == currentUserId);

        long now = System.currentTimeMillis();
        long eventTime = event.getDate_evenement().getTime();
        boolean isPastOrWithin24h = eventTime < (now + 24 * 60 * 60 * 1000);

        // Visibility based on creator status
        if (!isCreator) {
            editBtn.setVisible(false);
            editBtn.setManaged(false);
            deleteBtn.setVisible(false);
            deleteBtn.setManaged(false);
            exportBtn.setVisible(false);
            exportBtn.setManaged(false);
            chatBtn.setVisible(false);
            chatBtn.setManaged(false);
        } else {
            // If creator, further restrict edit/delete by time
            if (isPastOrWithin24h) {
                editBtn.setVisible(false);
                editBtn.setManaged(false);
                deleteBtn.setVisible(false);
                deleteBtn.setManaged(false);
            }
        }

        editBtn.setOnAction(e -> {
            currentPopup.close();
            showEditEventDialog(event);
        });

        deleteBtn.setOnAction(e -> {
            Alert confirm = new Alert(Alert.AlertType.CONFIRMATION);
            confirm.setTitle("Confirmer la suppression");
            confirm.setHeaderText("Supprimer \"" + event.getTitre() + "\" ?");
            confirm.setContentText(
                    "Cette action est irréversible et supprimera toutes les inscriptions correspondantes.");

            confirm.showAndWait().ifPresent(response -> {
                if (response == ButtonType.OK) {
                    try {
                        // Delete image file if exists
                        if (event.getImagePath() != null && !event.getImagePath().isEmpty()) {
                            ImageStorage.deleteImage(event.getImagePath());
                        }

                        // Delete from database (handles cascade)
                        serviceJPO.delete(event);

                        // Close popup
                        currentPopup.close();

                        // Refresh calendar
                        loadEvents();
                        buildCalendar();

                        showAlert("Succès", "JPO supprimée avec succès.");

                    } catch (SQLException ex) {
                        showAlert("Erreur", "Impossible de supprimer: " + ex.getMessage());
                        ex.printStackTrace();
                    }
                }
            });
        });

        exportBtn.setOnAction(e -> {
            currentPopup.close();
            exportParticipantsToExcel(event);
        });

        chatBtn.setOnAction(e -> {
            currentPopup.close();
            openChatWindow(event);
        });

        actions.getChildren().addAll(editBtn, deleteBtn, exportBtn, chatBtn);

        // Assemble popup content
        content.getChildren().addAll(header, new Separator(), mainInfoBox, new Separator(), descBox, actions);

        // Add content and close button to root container
        rootContainer.getChildren().addAll(content, closeXBtn);

        Scene scene = new Scene(rootContainer);
        scene.setFill(Color.TRANSPARENT);

        // Allow closing by clicking outside (optional - remove if not desired)
        rootContainer.setOnMouseClicked(e -> {
            if (e.getTarget() == rootContainer) {
                currentPopup.close();
            }
        });

        currentPopup.setScene(scene);
        currentPopup.show();
    }

    private void showEditEventDialog(JPO event) {
        if (currentPopup != null && currentPopup.isShowing()) {
            currentPopup.close();
        }

        currentPopup = new Stage();
        currentPopup.initModality(Modality.APPLICATION_MODAL);
        currentPopup.initStyle(StageStyle.UNDECORATED);
        currentPopup.setTitle("Modifier JPO");

        VBox content = new VBox(15);
        content.setPadding(new Insets(25));
        content.setStyle("-fx-background-color: white; -fx-background-radius: 16px; " +
                "-fx-border-radius: 16px; -fx-border-color: #e0e4e8; -fx-border-width: 1px; " +
                "-fx-effect: dropshadow(gaussian, rgba(0,0,0,0.15), 20, 0, 0, 8);");
        content.setPrefWidth(500);

        HBox header = new HBox(10);
        header.setAlignment(Pos.CENTER_LEFT);
        // Label icon = new Label("✏️");
        // icon.setFont(Font.font("System", 24));
        Label title = new Label("Modifier la JPO");
        title.setFont(Font.font("System", FontWeight.BOLD, 18));
        title.setTextFill(Color.web("#0D2440"));
        header.getChildren().addAll(title);

        LocalDate currentDate = new java.sql.Date(event.getDate_evenement().getTime()).toLocalDate();

        VBox form = new VBox(10);

        Label lblTitle = new Label("Titre *");
        TextField titleField = new TextField(event.getTitre());

        Label lblLocation = new Label("Lieu *");
        TextField locationField = new TextField(event.getLieu());

        HBox dateTimeBox = new HBox(15);
        VBox dateBox = new VBox(5);
        Label lblDate = new Label("Date");
        DatePicker datePicker = new DatePicker(currentDate);
        dateBox.getChildren().addAll(lblDate, datePicker);

        VBox timeBox = new VBox(5);
        Label lblTime = new Label("Heure");
        ComboBox<String> timeCombo = new ComboBox<>();
        timeCombo.getItems().addAll(
                "08:00", "09:00", "10:00", "11:00", "12:00",
                "13:00", "14:00", "15:00", "16:00", "17:00", "18:00");
        timeCombo.setValue(String.format("%02d:00",
                java.time.LocalDateTime.ofInstant(
                        java.time.Instant.ofEpochMilli(event.getDate_evenement().getTime()),
                        java.time.ZoneId.systemDefault()).getHour()));
        timeBox.getChildren().addAll(lblTime, timeCombo);

        HBox.setHgrow(dateBox, Priority.ALWAYS);
        HBox.setHgrow(timeBox, Priority.ALWAYS);
        dateTimeBox.getChildren().addAll(dateBox, timeBox);

        Label lblDesc = new Label("Description");
        TextArea descArea = new TextArea(event.getDescription() != null ? event.getDescription() : "");
        descArea.setPromptText("Décrivez votre événement...");
        descArea.setPrefRowCount(3);

        Label lblMax = new Label("Max participants *");
        Spinner<Integer> maxParticipants = new Spinner<>(1, 1000, event.getMaxParticipants());
        maxParticipants.setEditable(true);

        int currentParticipants = event.getCurrentParticipants();
        if (currentParticipants > 0) {
            Label warningLabel = new Label(currentParticipants + " personnes déjà inscrites. " +
                    "La modification n'affectera pas les inscriptions existantes.");
            warningLabel.setTextFill(Color.web("#fd7e14"));
            warningLabel.setWrapText(true);
            form.getChildren().add(warningLabel);
        }

        Label lblImage = new Label("Image (optionnel - laisser vide pour conserver l'actuelle)");
        HBox imageBox = new HBox(10);
        Button chooseImageBtn = new Button(" Changer l'image");
        chooseImageBtn.setGraphic(new FontIcon("fas-image"));
        Label imageNameLabel = new Label(
                event.getImagePath() != null ? event.getImagePath().substring(event.getImagePath().lastIndexOf("/") + 1)
                        : "Image par défaut");
        imageNameLabel.setTextFill(Color.web("#666"));
        imageBox.getChildren().addAll(chooseImageBtn, imageNameLabel);

        final File[] selectedImage = { null };
        chooseImageBtn.setOnAction(e -> {
            javafx.stage.FileChooser chooser = new javafx.stage.FileChooser();
            chooser.getExtensionFilters().add(
                    new javafx.stage.FileChooser.ExtensionFilter("Images", "*.png", "*.jpg", "*.jpeg"));
            File file = chooser.showOpenDialog(currentPopup);
            if (file != null) {
                selectedImage[0] = file;
                imageNameLabel.setText(file.getName());
            }
        });

        form.getChildren().addAll(
                lblTitle, titleField,
                lblLocation, locationField,
                dateTimeBox,
                lblDesc, descArea,
                lblMax, maxParticipants,
                lblImage, imageBox);

        HBox buttons = new HBox(10);
        buttons.setAlignment(Pos.CENTER_RIGHT);
        buttons.setPadding(new Insets(10, 0, 0, 0));

        Button cancelBtn = new Button(" Annuler");
        cancelBtn.setGraphic(new FontIcon("fas-times"));
        cancelBtn.setStyle("-fx-background-color: #6c757d; -fx-text-fill: white; -fx-background-radius: 8px;");
        cancelBtn.setOnAction(e -> currentPopup.close());

        Button saveBtn = new Button(" Enregistrer");
        saveBtn.setGraphic(new FontIcon("fas-save"));
        saveBtn.setStyle(
                "-fx-background-color: #28a745; -fx-text-fill: white; -fx-font-weight: bold; -fx-background-radius: 8px;");
        saveBtn.setOnAction(e -> {
            if (titleField.getText().trim().isEmpty() ||
                    locationField.getText().trim().isEmpty()) {
                showAlert("Champs requis", "Veuillez remplir le titre et le lieu.");
                return;
            }

            updateEvent(event, titleField.getText(), locationField.getText(),
                    descArea.getText(), maxParticipants.getValue(),
                    datePicker.getValue(), timeCombo.getValue(), selectedImage[0]);
            currentPopup.close();
            loadEvents();
            buildCalendar();
        });

        buttons.getChildren().addAll(cancelBtn, saveBtn);

        content.getChildren().addAll(header, new Separator(), form, buttons);

        Scene scene = new Scene(content);
        scene.setFill(Color.TRANSPARENT);
        currentPopup.setScene(scene);
        currentPopup.show();
    }

    private void showEventSelectionDialog(LocalDate date, List<JPO> events) {
        if (currentPopup != null && currentPopup.isShowing()) {
            currentPopup.close();
        }

        currentPopup = new Stage();
        currentPopup.initModality(Modality.APPLICATION_MODAL);
        currentPopup.initStyle(StageStyle.UNDECORATED);
        currentPopup.setTitle("Sélectionner une JPO");

        VBox content = new VBox(15);
        content.setPadding(new Insets(25));
        content.setStyle("-fx-background-color: white; -fx-background-radius: 16px; " +
                "-fx-border-radius: 16px; -fx-border-color: #e0e4e8; -fx-border-width: 1px; " +
                "-fx-effect: dropshadow(gaussian, rgba(0,0,0,0.15), 20, 0, 0, 8);");
        content.setPrefWidth(500);

        Label header = new Label(" " + events.size() + " JPOs le " +
                date.format(DateTimeFormatter.ofPattern("dd/MM/yyyy", Locale.FRENCH)));
        header.setGraphic(new FontIcon("fas-calendar-alt"));
        header.setFont(Font.font("System", FontWeight.BOLD, 16));
        header.setTextFill(Color.web("#0D2440"));

        VBox eventsList = new VBox(10);
        eventsList.setPadding(new Insets(10, 0, 10, 0));

        for (JPO event : events) {
            HBox eventRow = new HBox(10);
            eventRow.setAlignment(Pos.CENTER_LEFT);
            eventRow.setPadding(new Insets(12));
            eventRow.setStyle("-fx-background-color: #f8f9fa; -fx-background-radius: 10px; " +
                    "-fx-border-color: #e0e4e8; -fx-border-radius: 10px; -fx-border-width: 1px;");

            // === IMAGE THUMBNAIL (ADDED) ===
            ImageView thumbImageView = new ImageView();
            thumbImageView.setFitWidth(60);
            thumbImageView.setFitHeight(60);
            thumbImageView.setPreserveRatio(true);
            thumbImageView.setStyle("-fx-background-radius: 6px;");

            if (event.getImagePath() != null && !event.getImagePath().isEmpty()) {
                Image image = ImageStorage.loadImage(event.getImagePath());
                if (image != null && !image.isError()) {
                    thumbImageView.setImage(image);
                } else {
                    thumbImageView.setImage(loadPlaceholderImage());
                }
            } else {
                thumbImageView.setImage(loadPlaceholderImage());
            }
            // ================================

            Circle indicator = new Circle(8, Color.web(getEventColor(event)));

            VBox textBox = new VBox(3);
            Label title = new Label(event.getTitre());
            title.setFont(Font.font("System", FontWeight.BOLD, 13));
            Label subtitle = new Label(event.getLieu() + " • " +
                    event.getCurrentParticipants() + "/" + event.getMaxParticipants());
            subtitle.setFont(Font.font("System", 11));
            subtitle.setTextFill(Color.web("#666"));
            textBox.getChildren().addAll(title, subtitle);
            HBox.setHgrow(textBox, Priority.ALWAYS);

            Button editBtn = new Button("Edit");
            editBtn.setStyle("-fx-background-color: #ffc107; -fx-text-fill: #0D2440; -fx-font-weight: bold; " +
                    "-fx-background-radius: 6px; -fx-min-width: 40px;");
            editBtn.setOnAction(e -> {
                currentPopup.close();
                showEditEventDialog(event);
            });

            Button deleteBtn = new Button("Supprimer");
            deleteBtn.setStyle("-fx-background-color: #dc3545; -fx-text-fill: white; -fx-font-weight: bold; " +
                    "-fx-background-radius: 6px; -fx-min-width: 40px;");

            // --- Permissions, Past Date & 24h Check ---
            int currentUserId = SessionManager.getCurrentUser().getIdUtilisateur();
            boolean isCreator = (event.getIdCreateur() == currentUserId);

            long now = System.currentTimeMillis();
            long eventTime = event.getDate_evenement().getTime();
            boolean isPastOrWithin24h = eventTime < (now + 24 * 60 * 60 * 1000);

            if (!isCreator) {
                editBtn.setVisible(false);
                editBtn.setManaged(false);
                deleteBtn.setVisible(false);
                deleteBtn.setManaged(false);
            } else if (isPastOrWithin24h) {
                editBtn.setDisable(true);
                editBtn.setOpacity(0.5);
                deleteBtn.setDisable(true);
                deleteBtn.setOpacity(0.5);
            }

            deleteBtn.setOnAction(e -> {
                Alert confirm = new Alert(Alert.AlertType.CONFIRMATION);
                confirm.setTitle("Confirmer");
                confirm.setHeaderText("Supprimer \"" + event.getTitre() + "\" ?");
                confirm.setContentText("Cette action est irréversible.");

                confirm.showAndWait().ifPresent(response -> {
                    if (response == ButtonType.OK) {
                        deleteEvent(event);
                        currentPopup.close();
                        loadEvents();
                        buildCalendar();
                        showEventSelectionDialog(date, events.stream()
                                .filter(ev -> ev.getId_evenement() != event.getId_evenement())
                                .collect(Collectors.toList()));
                    }
                });
            });

            // Add thumbnail to row (before indicator)
            eventRow.getChildren().addAll(thumbImageView, indicator, textBox, editBtn, deleteBtn);
            eventsList.getChildren().add(eventRow);
        }

        ScrollPane scrollPane = new ScrollPane(eventsList);
        scrollPane.setFitToWidth(true);
        scrollPane.setPrefHeight(350);
        scrollPane.setStyle("-fx-background-color: transparent;");

        Button closeBtn = new Button("Fermer");
        closeBtn.setStyle("-fx-background-color: #6c757d; -fx-text-fill: white; -fx-background-radius: 8px;");
        closeBtn.setOnAction(e -> currentPopup.close());

        HBox buttons = new HBox(10, closeBtn);
        buttons.setAlignment(Pos.CENTER_RIGHT);

        content.getChildren().addAll(header, scrollPane, buttons);

        Scene scene = new Scene(content);
        scene.setFill(Color.TRANSPARENT);
        currentPopup.setScene(scene);
        currentPopup.show();
    }

    private void createEvent(LocalDate date, String time, String title, String location,
            String description, int maxParticipants, File imageFile) {
        try {
            JPO event = new JPO();
            event.setTitre(title);
            event.setLieu(location);
            event.setDescription(description);

            LocalTime localTime = LocalTime.parse(time);
            java.time.LocalDateTime dateTime = date.atTime(localTime);
            event.setDate_evenement(java.sql.Timestamp.valueOf(dateTime));

            event.setMaxParticipants(maxParticipants);
            event.setCurrentParticipants(0);
            event.setIdCreateur(SessionManager.getCurrentUser().getIdUtilisateur());

            if (imageFile != null) {
                String imagePath = ImageStorage.saveImage(imageFile);
                event.setImagePath(imagePath);
            }

            serviceJPO.add(event);
            showAlert("Succès", "Journée Portes Ouvertes créée avec succès !");
        } catch (Exception e) {
            showAlert("Erreur", "Impossible de créer l'événement: " + e.getMessage());
        }
    }

    private void updateEvent(JPO event, String title, String location, String description,
            int maxParticipants, LocalDate newDate, String newTime, File newImage) {
        try {
            event.setTitre(title);
            event.setLieu(location);
            event.setDescription(description);
            event.setMaxParticipants(maxParticipants);

            if (newDate != null && newTime != null) {
                LocalTime localTime = LocalTime.parse(newTime);
                java.time.LocalDateTime dateTime = newDate.atTime(localTime);
                event.setDate_evenement(java.sql.Timestamp.valueOf(dateTime));
            }

            if (newImage != null) {
                ImageStorage.deleteImage(event.getImagePath());
                String imagePath = ImageStorage.saveImage(newImage);
                event.setImagePath(imagePath);
            }

            serviceJPO.update(event);
            showAlert("Succès", "JPO mise à jour avec succès !");
        } catch (Exception e) {
            showAlert("Erreur", "Impossible de modifier: " + e.getMessage());
        }
    }

    private void deleteEvent(JPO event) {
        try {
            ImageStorage.deleteImage(event.getImagePath());
            serviceJPO.delete(event);
            showAlert("Succès", "JPO supprimée définitivement.");
        } catch (SQLException e) {
            showAlert("Erreur", "Impossible de supprimer: " + e.getMessage());
        }
    }

    private String getEventColor(JPO event) {
        int spotsLeft = event.getSpotsLeft();
        int max = event.getMaxParticipants();

        if (max == 0)
            return "#6c757d";
        if (spotsLeft == 0)
            return "#dc3545";
        if (spotsLeft <= max * 0.2)
            return "#fd7e14";
        return "#28a745";
    }

    private String truncateText(String text, int maxLen) {
        if (text == null || text.length() <= maxLen)
            return text;
        return text.substring(0, maxLen - 2) + "..";
    }

    private void showAlert(String header, String content) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle("CashFly Propriétaire");
        alert.setHeaderText(header);
        alert.setContentText(content);
        alert.showAndWait();
    }

    private void handleUnauthorized() {
        showAlert("Accès refusé", "Vous n'avez pas les droits pour accéder à cette page.");
        handleLogout();
    }

    private void handleLogout() {
        SessionManager.clearSession();
        try {
            if (mainController != null) {
            }
        } catch (Exception e) {
        }
    }

    private Image loadPlaceholderImage() {
        try {
            // Try to load default event image from resources
            java.io.InputStream is = getClass().getResourceAsStream("/tn/cashfly/Images/default-event.png");
            if (is != null) {
                return new Image(is);
            }
            // Return blank image if default not found
            return new Image(new java.io.ByteArrayInputStream(new byte[0]));
        } catch (Exception e) {
            return null;
        }
    }

    private void showDayActionDialog(LocalDate date, List<JPO> dayEvents) {
        currentPopup = new Stage();
        currentPopup.initModality(Modality.APPLICATION_MODAL);
        currentPopup.initStyle(StageStyle.UNDECORATED);
        currentPopup.setTitle("Actions du " + date.format(DateTimeFormatter.ofPattern("dd/MM/yyyy", Locale.FRENCH)));

        VBox content = new VBox(15);
        content.setPadding(new Insets(25));
        content.setStyle("-fx-background-color: white; -fx-background-radius: 16px; " +
                "-fx-border-radius: 16px; -fx-border-color: #e0e4e8; -fx-border-width: 1px; " +
                "-fx-effect: dropshadow(gaussian, rgba(0,0,0,0.15), 20, 0, 0, 8);");
        content.setPrefWidth(400);

        // Header with date
        HBox header = new HBox(10);
        header.setAlignment(Pos.CENTER_LEFT);
        FontIcon calendarIcon = new FontIcon("fas-calendar-alt");
        calendarIcon.setIconSize(24);
        VBox dateBox = new VBox(2);
        Label dateLabel = new Label(date.format(DateTimeFormatter.ofPattern("EEEE dd MMMM yyyy", Locale.FRENCH)));
        dateLabel.setFont(Font.font("System", FontWeight.BOLD, 16));
        dateLabel.setTextFill(Color.web("#0D2440"));
        Label eventCountLabel = new Label(
                dayEvents.size() + " événement" + (dayEvents.size() > 1 ? "s" : "") + " existant");
        eventCountLabel.setFont(Font.font("System", 12));
        eventCountLabel.setTextFill(Color.web("#666"));
        dateBox.getChildren().addAll(dateLabel, eventCountLabel);
        header.getChildren().addAll(calendarIcon, dateBox);

        Separator separator1 = new Separator();

        // OPTION 1: View Existing Events
        VBox viewOption = new VBox(10);
        viewOption.setPadding(new Insets(15));
        viewOption.setStyle("-fx-background-color: #f8f9fa; -fx-background-radius: 12px; " +
                "-fx-border-color: #e0e4e8; -fx-border-radius: 12px; -fx-cursor: hand;");
        viewOption.setOnMouseClicked(e -> {
            currentPopup.close();
            if (dayEvents.size() == 1) {
                showEventDetailsPopup(dayEvents.get(0));
            } else {
                showEventSelectionDialog(date, dayEvents);
            }
        });
        viewOption.setOnMouseEntered(e -> viewOption.setStyle(
                "-fx-background-color: #e7f3ff; -fx-background-radius: 12px; " +
                        "-fx-border-color: #2E5E99; -fx-border-radius: 12px; -fx-cursor: hand;"));
        viewOption.setOnMouseExited(e -> viewOption.setStyle(
                "-fx-background-color: #f8f9fa; -fx-background-radius: 12px; " +
                        "-fx-border-color: #e0e4e8; -fx-border-radius: 12px; -fx-cursor: hand;"));

        HBox viewHeader = new HBox(10);
        viewHeader.setAlignment(Pos.CENTER_LEFT);
        FontIcon viewIcon = new FontIcon("fas-eye");
        viewIcon.setIconSize(28);
        VBox viewText = new VBox(3);
        Label viewTitle = new Label("Voir les événements");
        viewTitle.setFont(Font.font("System", FontWeight.BOLD, 14));
        Label viewDesc = new Label("Consulter ou modifier les " + dayEvents.size() + " événement(s)");
        viewDesc.setFont(Font.font("System", 11));
        viewDesc.setTextFill(Color.web("#666"));
        viewText.getChildren().addAll(viewTitle, viewDesc);
        viewHeader.getChildren().addAll(viewIcon, viewText);
        HBox.setHgrow(viewText, Priority.ALWAYS);

        // Mini preview of existing events
        VBox previewBox = new VBox(5);
        previewBox.setPadding(new Insets(5, 0, 0, 38));
        int previewCount = Math.min(dayEvents.size(), 3);
        for (int i = 0; i < previewCount; i++) {
            JPO event = dayEvents.get(i);
            HBox previewRow = new HBox(8);
            previewRow.setAlignment(Pos.CENTER_LEFT);
            Circle dot = new Circle(4, Color.web(getEventColor(event)));
            Label previewLabel = new Label(truncateText(event.getTitre(), 25));
            previewLabel.setFont(Font.font("System", 11));
            previewRow.getChildren().addAll(dot, previewLabel);
            previewBox.getChildren().add(previewRow);
        }
        if (dayEvents.size() > 3) {
            Label moreLabel = new Label("+" + (dayEvents.size() - 3) + " plus...");
            moreLabel.setFont(Font.font("System", 10));
            moreLabel.setTextFill(Color.web("#999"));
            moreLabel.setPadding(new Insets(0, 0, 0, 16));
            previewBox.getChildren().add(moreLabel);
        }

        viewOption.getChildren().addAll(viewHeader, previewBox);

        // OPTION 2: Add New Event
        VBox addOption = new VBox(10);
        addOption.setPadding(new Insets(15));
        addOption.setStyle("-fx-background-color: #f0f9f0; -fx-background-radius: 12px; " +
                "-fx-border-color: #28a745; -fx-border-radius: 12px; -fx-cursor: hand;");
        addOption.setOnMouseClicked(e -> {
            currentPopup.close();
            showCreateEventDialog(date);
        });
        addOption.setOnMouseEntered(e -> addOption.setStyle(
                "-fx-background-color: #d4edda; -fx-background-radius: 12px; " +
                        "-fx-border-color: #1e7e34; -fx-border-radius: 12px; -fx-cursor: hand;"));
        addOption.setOnMouseExited(e -> addOption.setStyle(
                "-fx-background-color: #f0f9f0; -fx-background-radius: 12px; " +
                        "-fx-border-color: #28a745; -fx-border-radius: 12px; -fx-cursor: hand;"));

        HBox addHeader = new HBox(10);
        addHeader.setAlignment(Pos.CENTER_LEFT);
        FontIcon addIcon = new FontIcon("fas-plus");
        addIcon.setIconSize(28);
        VBox addText = new VBox(3);
        Label addTitle = new Label("Ajouter un événement");
        addTitle.setFont(Font.font("System", FontWeight.BOLD, 14));
        addTitle.setTextFill(Color.web("#28a745"));
        Label addDesc = new Label("Créer une nouvelle JPO pour cette date");
        addDesc.setFont(Font.font("System", 11));
        addDesc.setTextFill(Color.web("#666"));
        addText.getChildren().addAll(addTitle, addDesc);
        addHeader.getChildren().addAll(addIcon, addText);

        addOption.getChildren().add(addHeader);

        Separator separator2 = new Separator();

        // Cancel button
        Button cancelBtn = new Button("Annuler");
        cancelBtn.setStyle(
                "-fx-background-color: #6c757d; -fx-text-fill: white; -fx-background-radius: 8px; -fx-padding: 8 20;");
        cancelBtn.setOnAction(e -> currentPopup.close());

        HBox cancelBox = new HBox();
        cancelBox.setAlignment(Pos.CENTER_RIGHT);
        cancelBox.getChildren().add(cancelBtn);

        // Assemble dialog
        content.getChildren().addAll(header, separator1, viewOption, addOption, separator2, cancelBox);

        Scene scene = new Scene(content);
        scene.setFill(Color.TRANSPARENT);
        currentPopup.setScene(scene);
        currentPopup.show();
    }

    private void exportParticipantsToExcel(JPO event) {

        try {
            ServiceParticipation serviceParticipation = new ServiceParticipation();
            List<ParticipantInfo> participants = serviceParticipation
                    .getEventParticipantsDetailed(event.getId_evenement());
            EventStatistics statistics = serviceParticipation.getEventStatistics(event.getId_evenement());

            if (participants.isEmpty()) {
                showAlert("Information", "Aucun participant inscrit à cet événement.");
                return;
            }

            // Show directory chooser dialog
            DirectoryChooser directoryChooser = new DirectoryChooser();
            directoryChooser.setTitle("Choisir le dossier de destination pour l'export Excel");

            // Set default directory (CashFly/Exports or user home)
            File defaultDir = new File(System.getProperty("user.home"), "CashFly/Exports");
            if (!defaultDir.exists()) {
                defaultDir = new File(System.getProperty("user.home"));
            }
            directoryChooser.setInitialDirectory(defaultDir);

            // Show the dialog (need to get the stage from the current scene)
            Stage stage = (Stage) calendarGrid.getScene().getWindow();
            File selectedDirectory = directoryChooser.showDialog(stage);

            // User cancelled the dialog
            if (selectedDirectory == null) {
                return;
            }

            // Generate Excel file in selected directory
            ExcelExportService exportService = new ExcelExportService();
            String filePath = exportService.exportParticipantsToExcel(event, participants, statistics,
                    selectedDirectory);

            // Show success dialog
            Alert alert = new Alert(Alert.AlertType.INFORMATION);
            alert.setTitle("Export réussi");
            alert.setHeaderText("Fichier Excel généré avec succès!");
            alert.setContentText("Nom du fichier: " + new File(filePath).getName() + "\n" +
                    "Dossier: " + selectedDirectory.getAbsolutePath() + "\n\n" +
                    "Statistiques exportées:\n" +
                    "• Total participants: " + statistics.getTotalParticipants() + "\n" +
                    "• Confirmés: " + statistics.getConfirmed() + "\n" +
                    "• En attente: " + statistics.getWaiting() + "\n" +
                    "• Badges générés: " + statistics.getBadgesGenerated());

            // Add buttons
            ButtonType openFileButton = new ButtonType("Ouvrir le fichier");
            ButtonType openFolderButton = new ButtonType("Ouvrir le dossier");
            ButtonType okButton = new ButtonType("OK", ButtonBar.ButtonData.OK_DONE);
            alert.getButtonTypes().setAll(openFileButton, openFolderButton, okButton);

            alert.showAndWait().ifPresent(response -> {
                try {
                    File file = new File(filePath);
                    if (response == openFileButton) {
                        // Open the Excel file directly
                        java.awt.Desktop.getDesktop().open(file);
                    } else if (response == openFolderButton) {
                        // Open the containing folder
                        java.awt.Desktop.getDesktop().open(file.getParentFile());
                    }
                } catch (Exception e) {
                    showAlert("Erreur", "Impossible d'ouvrir: " + e.getMessage());
                }
            });

        } catch (IOException | SQLException e) {
            showAlert("Erreur d'export", "Impossible de générer le fichier Excel: " + e.getMessage());
        }
    }

    private void openChatWindow(JPO event) {

        if (event == null) {
            System.err.println("ERROR: Event is null!");
            showAlert("Erreur", "Événement non disponible");
            return;
        }

        try {
            String fxmlPath = "/tn/cashfly/ChatView.fxml";

            URL resource = getClass().getResource(fxmlPath);

            if (resource == null) {
                System.err.println("ERROR: ChatView.fxml not found!");
                showAlert("Erreur", "Fichier ChatView.fxml introuvable");
                return;
            }

            FXMLLoader loader = new FXMLLoader(resource);

            Parent chatView = loader.load();

            ChatViewController controller = loader.getController();

            if (controller != null) {
                controller.setEvent(event);
            }

            // Create popup stage
            Stage chatStage = new Stage();
            chatStage.initModality(Modality.APPLICATION_MODAL);
            chatStage.initStyle(StageStyle.DECORATED); // Use DECORATED for testing
            chatStage.setTitle("Chat - " + event.getTitre());

            Scene scene = new Scene(chatView, 450, 600);

            // Add close on escape
            scene.setOnKeyPressed(e -> {
                if (e.getCode() == KeyCode.ESCAPE) {
                    if (controller != null)
                        controller.cleanup();
                    chatStage.close();
                }
            });

            chatStage.setScene(scene);
            chatStage.setOnCloseRequest(e -> {
                if (controller != null)
                    controller.cleanup();
            });

            chatStage.show();

        } catch (IOException e) {
            System.err.println("IOException in openChatWindow: " + e.getMessage());
            showAlert("Erreur", "Impossible d'ouvrir le chat: " + e.getMessage());
        } catch (Exception e) {
            System.err.println("Exception in openChatWindow: " + e.getMessage());
            showAlert("Erreur", "Erreur inattendue: " + e.getMessage());
        }
    }
}
