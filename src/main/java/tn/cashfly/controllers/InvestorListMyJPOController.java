package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.ButtonType;
import javafx.scene.control.Label;
import javafx.scene.control.ScrollPane;
import javafx.scene.image.ImageView;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Priority;
import javafx.scene.layout.Region;
import javafx.scene.layout.VBox;
import tn.cashfly.entities.JPO;
import tn.cashfly.entities.Participation;
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
import java.util.Date;
import java.util.List;
import java.util.Optional;

public class InvestorListMyJPOController {

    @FXML private VBox eventsContainer;
    @FXML private ScrollPane eventsScrollPane;
    @FXML private Label countLabel;
    @FXML private Label emptyMessage;

    private ServiceJPO serviceJPO;
    private ServiceParticipation serviceParticipation;
    private Utilisateur currentUser;

    @FXML
    public void initialize() {
        serviceJPO = new ServiceJPO();
        serviceParticipation = new ServiceParticipation();
        currentUser = SessionManager.getCurrentUser();

        loadMyEvents();
    }

    private void loadMyEvents() {
        try {
            eventsContainer.getChildren().clear();

            List<Participation> participations = serviceParticipation.getUserParticipationsWithEvents(currentUser.getIdUtilisateur());

            countLabel.setText("(" + participations.size() + ")");

            if (participations.isEmpty()) {
                showEmptyState();
                return;
            }

            emptyMessage.setVisible(false);

            // Sort by event date
            participations.sort((p1, p2) -> {
                try {
                    JPO e1 = serviceJPO.getAll().stream()
                            .filter(j -> j.getId_evenement() == p1.getIdEvenement())
                            .findFirst().orElse(null);
                    JPO e2 = serviceJPO.getAll().stream()
                            .filter(j -> j.getId_evenement() == p2.getIdEvenement())
                            .findFirst().orElse(null);
                    if (e1 == null || e2 == null) return 0;
                    return e1.getDate_evenement().compareTo(e2.getDate_evenement());
                } catch (Exception e) {
                    return 0;
                }
            });

            for (Participation p : participations) {
                JPO event = serviceJPO.getAll().stream()
                        .filter(j -> j.getId_evenement() == p.getIdEvenement())
                        .findFirst()
                        .orElse(null);

                if (event != null) {
                    eventsContainer.getChildren().add(createEventCard(p, event));
                }
            }

        } catch (SQLException e) {
            e.printStackTrace();
            showAlert("Erreur", "Impossible de charger vos inscriptions: " + e.getMessage());
        }
    }

    private void showEmptyState() {
        emptyMessage.setVisible(true);
        VBox emptyBox = new VBox(20);
        emptyBox.setAlignment(Pos.CENTER);
        emptyBox.setPadding(new Insets(50));

        Label icon = new Label("📭");
        icon.setStyle("-fx-font-size: 64px;");

        Label text = new Label("Vous n'êtes inscrit à aucun événement");
        text.setStyle("-fx-font-size: 18px; -fx-text-fill: -color-fg-muted;");

        emptyBox.getChildren().addAll(icon, text);
        eventsContainer.getChildren().add(emptyBox);
    }

    private VBox createEventCard(Participation participation, JPO event) {
        VBox card = new VBox(12);
        card.getStyleClass().add("my-event-card");
        card.setPadding(new Insets(18));
        card.setStyle("-fx-background-color: -color-bg-overlay; -fx-background-radius: 12; " +
                "-fx-border-radius: 12; -fx-border-color: -color-border-default; " +
                "-fx-border-width: 1; -fx-effect: dropshadow(gaussian, rgba(0,0,0,0.08), 10, 0, 0, 2);");

        // Top row: Image + Info
        HBox topRow = new HBox(15);
        topRow.setAlignment(Pos.CENTER_LEFT);

        ImageView imgView = new ImageView(ImageStorage.loadImage(event.getImagePath()));
        imgView.setFitHeight(100);
        imgView.setFitWidth(150);
        imgView.setPreserveRatio(true);
        imgView.setStyle("-fx-background-radius: 8;");

        VBox infoBox = new VBox(8);
        infoBox.setAlignment(Pos.CENTER_LEFT);
        HBox.setHgrow(infoBox, Priority.ALWAYS);

        Label titleLabel = new Label(event.getTitre());
        titleLabel.setStyle("-fx-font-size: 18px; -fx-font-weight: bold;");
        titleLabel.setWrapText(true);

        // FIXED: Use proper date conversion
        String formattedDate = formatEventDate(event.getDate_evenement());
        Label dateLabel = new Label("📅 " + formattedDate);
        dateLabel.setStyle("-fx-font-size: 14px; -fx-text-fill: -color-fg-muted;");

        Label locationLabel = new Label("📍 " + event.getLieu());
        locationLabel.setStyle("-fx-font-size: 14px; -fx-text-fill: -color-fg-muted;");

        infoBox.getChildren().addAll(titleLabel, dateLabel, locationLabel);
        topRow.getChildren().addAll(imgView, infoBox);

        // Status row
        HBox statusRow = new HBox(10);
        statusRow.setAlignment(Pos.CENTER_LEFT);

        Label statusBadge = createStatusBadge(participation.getStatut());
        Label participantBadge = createParticipantBadge(event);

        statusRow.getChildren().addAll(statusBadge, participantBadge);

        // Action row
        HBox actionRow = new HBox(10);
        actionRow.setAlignment(Pos.CENTER_RIGHT);

        // FIXED: Proper date conversion for time check
        LocalDateTime eventDate = convertToLocalDateTime(event.getDate_evenement());
        LocalDateTime now = LocalDateTime.now();
        boolean canCancel = now.plusHours(24).isBefore(eventDate);
        boolean isPast = now.isAfter(eventDate);

        // Badge generation
        if ("confirmé".equals(participation.getStatut()) && !isPast) {
            if (!participation.isBadgeGenere()) {
                Button badgeBtn = new Button("🎫 Générer mon badge");
                badgeBtn.getStyleClass().add("btn-secondary");
                badgeBtn.setStyle("-fx-font-size: 12px; -fx-padding: 8 15;");
                badgeBtn.setOnAction(e -> generateBadge(participation));
                actionRow.getChildren().add(badgeBtn);
            } else {
                Label badgeLabel = new Label("✓ Badge généré");
                badgeLabel.setStyle("-fx-font-size: 12px; -fx-text-fill: #28a745; -fx-font-weight: bold;");
                actionRow.getChildren().add(badgeLabel);
            }
        }

        Region spacer = new Region();
        HBox.setHgrow(spacer, Priority.ALWAYS);
        actionRow.getChildren().add(spacer);

        if (isPast) {
            Label pastLabel = new Label("✓ Événement terminé");
            pastLabel.setStyle("-fx-font-size: 12px; -fx-text-fill: #6c757d; -fx-font-style: italic;");
            actionRow.getChildren().add(pastLabel);
        } else if (!canCancel) {
            Label noCancelLabel = new Label("⚠ Désinscription impossible (<24h)");
            noCancelLabel.setStyle("-fx-font-size: 12px; -fx-text-fill: #dc3545; -fx-font-style: italic;");
            actionRow.getChildren().add(noCancelLabel);
        } else {
            Button cancelBtn = new Button("Se désinscrire");
            cancelBtn.getStyleClass().add("btn-danger");
            cancelBtn.setStyle("-fx-font-size: 12px; -fx-padding: 8 20;");
            cancelBtn.setOnAction(e -> handleCancel(event));
            actionRow.getChildren().add(cancelBtn);
        }

        card.getChildren().addAll(topRow, statusRow, actionRow);
        return card;
    }

    /**
     * FIXED: Convert Date to LocalDateTime using getTime() which works for both java.util.Date and java.sql.Date
     */
    private String formatEventDate(Date date) {
        LocalDateTime dateTime = LocalDateTime.ofInstant(
                Instant.ofEpochMilli(date.getTime()),
                ZoneId.systemDefault()
        );
        return dateTime.format(DateTimeFormatter.ofPattern("EEEE dd MMMM yyyy 'à' HH:mm"));
    }

    /**
     * FIXED: Convert Date to LocalDateTime for comparison
     */
    private LocalDateTime convertToLocalDateTime(Date date) {
        return LocalDateTime.ofInstant(
                Instant.ofEpochMilli(date.getTime()),
                ZoneId.systemDefault()
        );
    }

    private Label createStatusBadge(String status) {
        Label badge = new Label();
        badge.setStyle("-fx-font-weight: bold; -fx-padding: 5 15; -fx-background-radius: 15; -fx-font-size: 12px;");

        switch (status.toLowerCase()) {
            case "confirmé":
                badge.setText("✓ Inscription confirmée");
                badge.setStyle(badge.getStyle() + "-fx-text-fill: white; -fx-background-color: #28a745;");
                break;
            case "en_attente":
                badge.setText("⏳ En liste d'attente");
                badge.setStyle(badge.getStyle() + "-fx-text-fill: #0D2440; -fx-background-color: #ffc107;");
                break;
            case "annulé":
                badge.setText("✗ Inscription annulée");
                badge.setStyle(badge.getStyle() + "-fx-text-fill: white; -fx-background-color: #6c757d;");
                break;
            case "présent":
                badge.setText("✓ Présence confirmée");
                badge.setStyle(badge.getStyle() + "-fx-text-fill: white; -fx-background-color: #17a2b8;");
                break;
            default:
                badge.setText(status);
                badge.setStyle(badge.getStyle() + "-fx-text-fill: -color-fg-default; -fx-background-color: -color-bg-subtle;");
        }
        return badge;
    }

    private Label createParticipantBadge(JPO event) {
        int available = event.getSpotsLeft();
        int max = event.getMaxParticipants();
        Label badge = new Label();
        badge.setStyle("-fx-font-size: 12px; -fx-padding: 5 12; -fx-background-radius: 12; -fx-font-weight: bold;");

        if (max <= 0) {
            badge.setText("Places illimitées");
            badge.setStyle(badge.getStyle() + "-fx-text-fill: -color-fg-muted; -fx-background-color: -color-bg-subtle;");
        } else if (available <= 0) {
            badge.setText("COMPLET");
            badge.setStyle(badge.getStyle() + "-fx-text-fill: white; -fx-background-color: #dc3545;");
        } else if (available <= max * 0.1) {
            badge.setText("⚠ " + available + "/" + max + " places");
            badge.setStyle(badge.getStyle() + "-fx-text-fill: white; -fx-background-color: #dc3545;");
        } else if (available <= max * 0.3) {
            badge.setText("⚠ " + available + "/" + max + " places");
            badge.setStyle(badge.getStyle() + "-fx-text-fill: white; -fx-background-color: #fd7e14;");
        } else {
            badge.setText("● " + available + "/" + max + " places");
            badge.setStyle(badge.getStyle() + "-fx-text-fill: white; -fx-background-color: #28a745;");
        }
        return badge;
    }

    private void generateBadge(Participation participation) {
        try {
            serviceParticipation.markBadgeGenerated(participation.getIdParticipation());
            showAlert("Badge généré", "Votre badge a été généré avec succès !");
            loadMyEvents();
        } catch (SQLException e) {
            showAlert("Erreur", "Impossible de générer le badge: " + e.getMessage());
        }
    }

    private void handleCancel(JPO event) {
        // FIXED: Use proper conversion
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
                loadMyEvents();
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