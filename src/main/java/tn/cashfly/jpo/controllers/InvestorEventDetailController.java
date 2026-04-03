package tn.cashfly.jpo.controllers;

import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.ProgressIndicator;
import javafx.scene.image.ImageView;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.StackPane;
import javafx.scene.shape.Circle;
import javafx.scene.shape.Rectangle;
import org.kordamp.ikonli.javafx.FontIcon;
import tn.cashfly.jpo.entities.JPO;
import tn.cashfly.jpo.entities.Participation;
import tn.cashfly.jpo.entities.Utilisateur;
import tn.cashfly.jpo.services.ServiceJPO;
import tn.cashfly.jpo.services.ServiceParticipation;
import tn.cashfly.jpo.utils.BadgeGenerator;
import tn.cashfly.jpo.utils.ImageStorage;
import tn.cashfly.jpo.utils.SessionManager;

import java.sql.SQLException;
import java.time.Instant;
import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.format.DateTimeFormatter;

public class InvestorEventDetailController {

    @FXML
    private AnchorPane rootPane;
    @FXML
    private Button backButton;
    @FXML
    private ImageView heroImage;
    @FXML
    private Label titleOverlay;
    @FXML
    private Label statusBadge;
    @FXML
    private Circle statusDot;
    @FXML
    private Label dateLabel;
    @FXML
    private Label lieuLabel;
    @FXML
    private Label participantsLabel;
    @FXML
    private Label spotsRemainingLabel;
    @FXML
    private Rectangle progressTrack;
    @FXML
    private Rectangle progressBar;
    @FXML
    private Label descriptionLabel;
    @FXML
    private FontIcon registrationStatusIcon;
    @FXML
    private Label actionTitle;
    @FXML
    private Label actionSubtitle;
    @FXML
    private Button registerButton;
    @FXML
    private Button cancelButton;
    @FXML
    private Button generateBadgeButton;
    @FXML
    private Button downloadBadgeButton;
    @FXML
    private javafx.scene.layout.VBox badgeLoadingBox;
    @FXML
    private ProgressIndicator badgeProgressIndicator;
    @FXML
    private Label badgeLoadingLabel;

    private final ServiceParticipation serviceParticipation = new ServiceParticipation();
    private final ServiceJPO serviceJPO = new ServiceJPO();
    private JPO event;
    private Utilisateur currentUser;
    private Participation currentParticipation;

    @FXML
    public void initialize() {
        currentUser = SessionManager.getCurrentUser();
        updateUiState();
    }

    public void setEvent(JPO event) {
        this.event = event;
        if (event != null) {
            titleOverlay.setText(event.getTitre());
            dateLabel.setText(formatDate(event.getDate_evenement()));
            lieuLabel.setText(event.getLieu());
            descriptionLabel.setText(event.getDescription());
            heroImage.setImage(ImageStorage.loadImage(event.getImagePath()));
            refreshParticipation();
        }
    }

    private void refreshParticipation() {
        if (event == null || currentUser == null) {
            return;
        }
        try {
            currentParticipation = serviceParticipation.getParticipation(event.getId_evenement(),
                    currentUser.getIdUtilisateur());
            updateUiState();
        } catch (SQLException e) {
            showError("Erreur lors du chargement de votre participation: " + e.getMessage());
        }
    }

    private void updateUiState() {
        if (event == null) {
            return;
        }

        int max = event.getMaxParticipants();
        int current = event.getCurrentParticipants();
        int spotsLeft = event.getSpotsLeft();
        participantsLabel.setText(current + " / " + max + " inscrits");
        spotsRemainingLabel.setText(spotsLeft + " place(s) restante(s)");

        double ratio = max > 0 ? (double) current / max : 0;
        double width = progressTrack.getWidth() > 0 ? progressTrack.getWidth() : 650;
        progressBar.setWidth(width * ratio);

        if (spotsLeft > 0) {
            statusBadge.setText(" Places disponibles");
            statusBadge.setStyle("-fx-font-size: 13px; -fx-font-weight: bold; -fx-padding: 6 16; -fx-background-radius: 20; -fx-background-color:#dcfce7; -fx-text-fill:#166534;");
            statusDot.setFill(javafx.scene.paint.Color.web("#22c55e"));
        } else {
            statusBadge.setText(" Complet / Liste d'attente");
            statusBadge.setStyle("-fx-font-size: 13px; -fx-font-weight: bold; -fx-padding: 6 16; -fx-background-radius: 20; -fx-background-color:#fee2e2; -fx-text-fill:#b91c1c;");
            statusDot.setFill(javafx.scene.paint.Color.web("#ef4444"));
        }

        boolean isRegistered = currentParticipation != null;
        boolean badgeGenerated = currentParticipation != null && currentParticipation.isBadgeGenere();

        boolean showRegister = !isRegistered;
        boolean showCancelAndGenerate = isRegistered && !badgeGenerated;
        boolean showDownload = badgeGenerated;

        registerButton.setVisible(showRegister);
        registerButton.setManaged(showRegister);
        registerButton.setDisable(false);

        cancelButton.setVisible(showCancelAndGenerate);
        cancelButton.setManaged(showCancelAndGenerate);
        cancelButton.setDisable(false);

        generateBadgeButton.setVisible(showCancelAndGenerate);
        generateBadgeButton.setManaged(showCancelAndGenerate);
        generateBadgeButton.setDisable(false);

        downloadBadgeButton.setVisible(showDownload);
        downloadBadgeButton.setManaged(showDownload);
        downloadBadgeButton.setDisable(false);

        if (!isRegistered) {
            registrationStatusIcon.setIconLiteral("fas-lock-open");
            actionTitle.setText("Inscription ouverte");
            actionSubtitle.setText("Réservez votre place");
        } else if (badgeGenerated) {
            registrationStatusIcon.setIconLiteral("fas-check-circle");
            actionTitle.setText("Badge généré");
            actionSubtitle.setText("Votre inscription est confirmée");
        } else {
            registrationStatusIcon.setIconLiteral("fas-user-check");
            actionTitle.setText("Inscription confirmée");
            actionSubtitle.setText("Vous pouvez générer votre badge");
        }
    }

    @FXML
    private void handleBack() {
        if (rootPane != null && rootPane.getScene() != null && rootPane.getScene().getWindow() != null) {
            rootPane.getScene().getWindow().hide();
        }
    }

    @FXML
    private void handleRegister() {
        if (event == null || currentUser == null) {
            return;
        }
        try {
            boolean confirmed = serviceParticipation.register(event.getId_evenement(), currentUser.getIdUtilisateur());
            showInfo(confirmed ? "Inscription confirmée" : "Ajouté à la liste d'attente");
            reloadEventState();
        } catch (SQLException e) {
            showError(e.getMessage());
        }
    }

    @FXML
    private void handleCancelRegistration() {
        if (event == null || currentUser == null) {
            return;
        }
        try {
            serviceParticipation.cancel(event.getId_evenement(), currentUser.getIdUtilisateur());
            showInfo("Inscription annulée");
            reloadEventState();
        } catch (SQLException e) {
            showError(e.getMessage());
        }
    }

    @FXML
    private void handleGenerateBadge() {
        if (event == null || currentUser == null || currentParticipation == null) {
            return;
        }
        try {
            badgeLoadingBox.setVisible(true);
            badgeLoadingBox.setManaged(true);
            String path = BadgeGenerator.generateBadge(currentUser, event, currentParticipation);
            serviceParticipation.markBadgeGenerated(currentParticipation.getIdParticipation());
            showInfo("Badge généré: " + path);
            reloadEventState();
        } catch (Exception e) {
            e.printStackTrace();
            String msg = e.getMessage();
            if (msg == null || msg.isBlank()) {
                msg = e.toString();
            }
            showError("Erreur lors de la génération du badge: " + msg);
        } finally {
            badgeLoadingBox.setVisible(false);
            badgeLoadingBox.setManaged(false);
        }
    }

    @FXML
    private void handleOpenChat() {
        if (event == null) {
            showError("Événement non disponible pour le chat.");
            return;
        }
        try {
            String fxmlPath = "/tn/cashfly/ChatView.fxml";
            java.net.URL resource = getClass().getResource(fxmlPath);
            if (resource == null) {
                showError("Interface de chat introuvable.");
                return;
            }
            javafx.fxml.FXMLLoader loader = new javafx.fxml.FXMLLoader(resource);
            javafx.scene.Parent chatView = loader.load();
            ChatViewController controller = loader.getController();
            if (controller != null) {
                controller.setEvent(event);
            }
            javafx.stage.Stage chatStage = new javafx.stage.Stage();
            chatStage.initModality(javafx.stage.Modality.APPLICATION_MODAL);
            chatStage.initStyle(javafx.stage.StageStyle.DECORATED);
            chatStage.setTitle("Chat - " + event.getTitre());
            javafx.scene.Scene scene = new javafx.scene.Scene(chatView, 450, 600);
            scene.setOnKeyPressed(e -> {
                if (e.getCode() == javafx.scene.input.KeyCode.ESCAPE) {
                    if (controller != null) {
                        controller.cleanup();
                    }
                    chatStage.close();
                }
            });
            chatStage.setScene(scene);
            chatStage.setOnCloseRequest(e -> {
                if (controller != null) {
                    controller.cleanup();
                }
            });
            chatStage.show();
        } catch (Exception e) {
            showError("Impossible d'ouvrir le chat: " + e.getMessage());
        }
    }

    @FXML
    private void handleDownloadBadge() {
        showInfo("Votre badge est déjà généré. Consultez le dossier Badges dans votre espace utilisateur.");
    }

    private void reloadEventState() {
        try {
            if (event != null) {
                event = serviceJPO.getAll().stream()
                        .filter(e -> e.getId_evenement() == event.getId_evenement())
                        .findFirst()
                        .orElse(event);
            }
        } catch (SQLException ignored) {
        }
        refreshParticipation();
    }

    private String formatDate(java.util.Date date) {
        LocalDateTime dateTime = LocalDateTime.ofInstant(
                Instant.ofEpochMilli(date.getTime()),
                ZoneId.systemDefault());
        return dateTime.format(DateTimeFormatter.ofPattern("EEEE dd MMMM yyyy 'à' HH:mm", java.util.Locale.FRENCH));
    }

    private void showInfo(String msg) {
        javafx.scene.control.Alert alert = new javafx.scene.control.Alert(javafx.scene.control.Alert.AlertType.INFORMATION);
        alert.setTitle("CashFly JPO");
        alert.setHeaderText(null);
        alert.setContentText(msg);
        alert.showAndWait();
    }

    private void showError(String msg) {
        javafx.scene.control.Alert alert = new javafx.scene.control.Alert(javafx.scene.control.Alert.AlertType.ERROR);
        alert.setTitle("CashFly JPO");
        alert.setHeaderText("Erreur");
        alert.setContentText(msg);
        alert.showAndWait();
    }
}
