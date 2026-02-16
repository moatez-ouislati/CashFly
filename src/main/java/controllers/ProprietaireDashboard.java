package controllers;

import entities.Utilisateur;
import javafx.animation.FadeTransition;
import javafx.animation.ScaleTransition;
import javafx.collections.FXCollections;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.HBox;
import javafx.scene.layout.StackPane;
import javafx.scene.layout.VBox;
import javafx.util.Duration;
import services.UtilisateurCrud;
import tools.Session;
import javafx.stage.*;
import javafx.animation.*;
import javafx.scene.Scene;
import javafx.scene.layout.StackPane;

import java.util.List;

public class ProprietaireDashboard {

    @FXML private TextField tfCin, tfTel, tfNom, tfPrenom, tfEmail;
    @FXML private Button btnSaveProfile, btnMyProfile;
    @FXML private ListView<Utilisateur> lvInvestisseurs;
    @FXML private Button btnLogout;

    private final UtilisateurCrud crud = new UtilisateurCrud();
    private Utilisateur currentUser;

    @FXML
    public void initialize() {
        btnLogout.setOnAction(e -> logout());

        currentUser = Session.getCurrentUser();

        loadProfile();
        loadInvestisseurs();
        openProfileDialog();

        btnSaveProfile.setOnAction(e -> saveProfile());
        btnMyProfile.setOnAction(e -> openProfileDialog());
    }

    private void loadProfile() {
        tfCin.setText(String.valueOf(currentUser.getCin()));
        tfTel.setText(currentUser.getTel());
        tfNom.setText(currentUser.getNom());
        tfPrenom.setText(currentUser.getPrenom());
        tfEmail.setText(currentUser.getEmail());
    }

    private void saveProfile() {
        currentUser.setCin(Integer.parseInt(tfCin.getText()));
        currentUser.setTel(tfTel.getText());
        currentUser.setNom(tfNom.getText());
        currentUser.setPrenom(tfPrenom.getText());
        currentUser.setEmail(tfEmail.getText());

        crud.modifier(currentUser);
        alert("Profile updated");
    }

    private void loadInvestisseurs() {

        List<Utilisateur> all = crud.afficher();

        List<Utilisateur> investisseurs = all.stream()
                .filter(u -> u.isInvestisseur())
                .toList();

        lvInvestisseurs.setItems(FXCollections.observableArrayList(investisseurs));

        lvInvestisseurs.setCellFactory(param -> new ListCell<>() {

            @Override
            protected void updateItem(Utilisateur user, boolean empty) {
                super.updateItem(user, empty);

                if (empty || user == null) {
                    setGraphic(null);
                } else {

                    Label name = new Label(user.getNom() + " " + user.getPrenom());
                    name.setStyle("-fx-font-weight:bold;");

                    Label email = new Label(user.getEmail());

                    Button btnInfo = new Button("Check info");
                    btnInfo.getStyleClass().add("mini-button");

                    btnInfo.setOnAction(e -> openInvestorInfoDialog(user));

                    HBox row = new HBox(10, new VBox(name, email), btnInfo);
                    row.setStyle("-fx-alignment:center-left;");

                    setGraphic(row);
                }
            }
        });
    }

    /* ===============================
       PROFILE DIALOG (same animation)
       =============================== */
    @FXML
    private void logout() {

        try {
            Session.clear();

            Parent root = FXMLLoader.load(
                    getClass().getResource("/authentification.fxml")
            );

            Stage stage = (Stage) btnLogout.getScene().getWindow();
            stage.setScene(new Scene(root));

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void openProfileDialog() {

        Dialog<Void> dialog = new Dialog<>();
        dialog.setTitle("Profile Information");

        dialog.getDialogPane().getButtonTypes().addAll(ButtonType.CLOSE);

        VBox box = new VBox(15,
                new Label("Name: " + currentUser.getNom()),
                new Label("Email: " + currentUser.getEmail())
        );

        box.setStyle("-fx-padding:20;");
        dialog.getDialogPane().setContent(box);

        dialog.setOnShown(e -> {
            dialog.getDialogPane().setOpacity(0);
            FadeTransition fade = new FadeTransition(Duration.millis(350), dialog.getDialogPane());
            fade.setFromValue(0);
            fade.setToValue(1);
            fade.play();
        });

        dialog.showAndWait();
    }

    /* ===============================
       INVESTOR INFO VIEW DIALOG
       =============================== */

    private void openInvestorInfoDialog(Utilisateur investor) {

        Stage popup = new Stage();
        popup.initOwner(lvInvestisseurs.getScene().getWindow());
        popup.initModality(Modality.APPLICATION_MODAL);
        popup.initStyle(StageStyle.TRANSPARENT);

        VBox card = new VBox(20);
        card.setStyle("""
            -fx-background-color: white;
            -fx-padding: 30;
            -fx-background-radius: 20;
            -fx-effect: dropshadow(three-pass-box, rgba(0,0,0,0.25), 30, 0, 0, 10);
            """);

        Label title = new Label("Investor Information");
        title.setStyle("-fx-font-size:18px; -fx-font-weight:bold;");

        Label years = new Label("Years Experience: " + safe(investor.getYearsExperience()));
        Label profit = new Label("Highest Profit: " + safe(investor.getHighestProfit()));
        Label budget = new Label("Budget: " + safe(investor.getBudget()));

        years.getStyleClass().add("info-label");
        profit.getStyleClass().add("info-label");
        budget.getStyleClass().add("info-label");

        Button close = new Button("Close");
        close.getStyleClass().add("primary-button");
        close.setOnAction(e -> popup.close());

        card.getChildren().addAll(title, years, profit, budget, close);

        StackPane root = new StackPane(card);
        root.setStyle("-fx-background-color: rgba(0,0,0,0.35);");

        Scene scene = new Scene(root);
        scene.setFill(null);

        popup.setScene(scene);

        // 🔥 Animation
        card.setOpacity(0);
        card.setScaleX(0.9);
        card.setScaleY(0.9);

        FadeTransition fade = new FadeTransition(Duration.millis(250), card);
        fade.setFromValue(0);
        fade.setToValue(1);

        ScaleTransition scale = new ScaleTransition(Duration.millis(250), card);
        scale.setFromX(0.9);
        scale.setFromY(0.9);
        scale.setToX(1);
        scale.setToY(1);

        fade.play();
        scale.play();

        popup.show();
    }


    private String safe(String value) {
        return value == null || value.isBlank() ? "Not provided" : value;
    }

    private void alert(String msg) {
        new Alert(Alert.AlertType.INFORMATION, msg, ButtonType.OK).showAndWait();
    }
}
