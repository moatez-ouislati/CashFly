package controllers;

import entities.Utilisateur;
import javafx.collections.FXCollections;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;
import services.UtilisateurCrud;
import tools.Session;
import java.util.List;
import javafx.animation.FadeTransition;
import javafx.util.Duration;

public class InvestisseurDashboard {

    @FXML private TextField tfCin, tfTel, tfNom, tfPrenom, tfEmail;
    @FXML private Button btnSaveProfile, btnMyProfile;
    @FXML private ListView<Utilisateur> lvProprietaires;
    @FXML private Button btnLogout;

    private final UtilisateurCrud crud = new UtilisateurCrud();
    private Utilisateur currentUser;

    @FXML
    public void initialize() {
        btnLogout.setOnAction(e -> logout());

        // Assume user stored statically after login
        currentUser = Session.getCurrentUser();

        loadProfile();
        loadProprietaires();
        openExtraInfoDialog(); // Auto popup

        btnSaveProfile.setOnAction(e -> saveProfile());
        btnMyProfile.setOnAction(e -> openExtraInfoDialog());
    }

    private void loadProfile() {
        tfCin.setText(String.valueOf(currentUser.getCin()));
        tfTel.setText(currentUser.getTel());
        tfNom.setText(currentUser.getNom());
        tfPrenom.setText(currentUser.getPrenom());
        tfEmail.setText(currentUser.getEmail());
    }
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

    private void saveProfile() {
        currentUser.setCin(Integer.parseInt(tfCin.getText()));
        currentUser.setTel(tfTel.getText());
        currentUser.setNom(tfNom.getText());
        currentUser.setPrenom(tfPrenom.getText());
        currentUser.setEmail(tfEmail.getText());

        crud.modifier(currentUser);
        alert("Profile updated");
    }

    private void loadProprietaires() {

        List<Utilisateur> all = crud.afficher();

        List<Utilisateur> props = all.stream()
                .filter(u -> u.isProprietaire())
                .toList();

        lvProprietaires.setItems(FXCollections.observableArrayList(props));

        lvProprietaires.setCellFactory(param -> new ListCell<>() {
            private final Button btnVoir = new Button("Voir offres");
            private final VBox container = new VBox();

            @Override
            protected void updateItem(Utilisateur user, boolean empty) {
                super.updateItem(user, empty);

                if (empty || user == null) {
                    setGraphic(null);
                } else {

                    btnVoir.setOnAction(e ->
                            alert("Offres feature coming soon"));

                    container.getChildren().clear();
                    container.getChildren().addAll(
                            new Label(user.getNom() + " " + user.getPrenom()),
                            new Label(user.getEmail()),
                            btnVoir
                    );

                    setGraphic(container);
                }
            }
        });
    }

    /* =========================
       ELEGANT EXTRA INFO DIALOG
       ========================= */
    private String formatCurrency(String value) {
        try {
            double amount = Double.parseDouble(value.replace(",", ""));
            return String.format("%,.2f TND", amount);
        } catch (Exception e) {
            return value;
        }
    }

    private void openExtraInfoDialog() {

        Dialog<Void> dialog = new Dialog<>();
        dialog.setTitle("Additional Information");

        ButtonType saveBtn = new ButtonType("Save", ButtonBar.ButtonData.OK_DONE);
        dialog.getDialogPane().getButtonTypes().addAll(saveBtn, ButtonType.CANCEL);

        TextField tfYears = new TextField(currentUser.getYearsExperience());
        tfYears.setPromptText("Years of Experience");

        TextField tfProfit = new TextField(currentUser.getHighestProfit());
        tfProfit.setPromptText("Highest Profit");

        TextField tfBudget = new TextField(currentUser.getBudget());
        tfBudget.setPromptText("Budget");

        VBox box = new VBox(15, tfYears, tfProfit, tfBudget);
        box.setStyle("-fx-padding:20;");
        dialog.getDialogPane().setContent(box);

        dialog.setOnShown(e -> {
            dialog.getDialogPane().setOpacity(0);
            FadeTransition fade = new FadeTransition(Duration.millis(350), dialog.getDialogPane());
            fade.setFromValue(0);
            fade.setToValue(1);
            fade.play();
        });

        dialog.setResultConverter(button -> {
            if (button == saveBtn) {

                currentUser.setYearsExperience(tfYears.getText());
                currentUser.setHighestProfit(tfProfit.getText());

                // Format budget before saving
                currentUser.setBudget(formatCurrency(tfBudget.getText()));

                crud.modifierI(currentUser);
            }
            return null;
        });

        dialog.showAndWait();
    }


    private void alert(String msg) {
        new Alert(Alert.AlertType.INFORMATION, msg, ButtonType.OK).showAndWait();
    }
}
