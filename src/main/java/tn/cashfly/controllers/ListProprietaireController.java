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
import javafx.scene.control.TextField;
import javafx.scene.control.ButtonType;
import javafx.scene.control.Separator;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.FlowPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.scene.shape.Circle;
import tn.cashfly.entities.Utilisateur;
import tn.cashfly.services.UtilisateurCrud;

import java.net.URL;
import java.util.List;
import java.util.ResourceBundle;
import java.util.stream.Collectors;

public class ListProprietaireController implements Initializable {

    @FXML private FlowPane cardContainer;
    @FXML private TextField txtSearch;
    @FXML private Label pageTitle;
    @FXML private Label pageSubtitle;

    private final UtilisateurCrud service = new UtilisateurCrud();
    private ObservableList<Utilisateur> masterList;

    private String roleToDisplay = "proprietaire";

    public void setRoleToDisplay(String role) {
        this.roleToDisplay = role;
        if (pageTitle != null) {
            if ("investisseur".equalsIgnoreCase(role)) {
                pageTitle.setText("Investisseurs Partenaires");
                pageSubtitle.setText("Découvrez des investisseurs pour financer vos projets.");
            } else {
                pageTitle.setText("Propriétaires d'Entreprises");
                pageSubtitle.setText("Consultez la liste des partenaires et leurs contacts.");
            }
        }
        loadData();
    }

    @Override
    public void initialize(URL url, ResourceBundle rb) {
        loadData();
        txtSearch.textProperty().addListener((obs, o, n) -> filter());
    }

    private void loadData() {
        // Fetch all users and filter for owners
        List<Utilisateur> users = service.afficher().stream()
                .filter(u -> roleToDisplay.equalsIgnoreCase(u.getRoles()))
                .collect(Collectors.toList());
        
        masterList = FXCollections.observableArrayList(users);
        displayCards(users);
    }

    private void displayCards(List<Utilisateur> users) {
        cardContainer.getChildren().clear();
        for (Utilisateur u : users) {
            cardContainer.getChildren().add(createCard(u));
        }
    }

    private VBox createCard(Utilisateur u) {
        VBox card = new VBox(15);
        card.setPrefWidth(300);
        card.getStyleClass().add("card");
        card.setAlignment(Pos.CENTER);
        card.setPadding(new Insets(20));

        // Handle null values for name
        String nom = u.getNom() != null ? u.getNom() : "Nom";
        String prenom = u.getPrenom() != null ? u.getPrenom() : "Inconnu";
        String initialStr = nom.isEmpty() ? "?" : nom.substring(0, 1).toUpperCase();

        // Profile Avatar
        Circle avatar = new Circle(40);
        avatar.setFill(javafx.scene.paint.Color.web("#6366f1"));
        
        Label initial = new Label(initialStr);
        initial.setStyle("-fx-text-fill: white; -fx-font-size: 24px; -fx-font-weight: bold;");
        
        javafx.scene.layout.StackPane avatarStack = new javafx.scene.layout.StackPane(avatar, initial);

        // Name and Role
        VBox infoBox = new VBox(5);
        infoBox.setAlignment(Pos.CENTER);
        
        Label name = new Label(nom + " " + prenom);
        name.setStyle("-fx-font-size: 18px; -fx-font-weight: bold; -fx-text-fill: #1e293b;");
        
        String roleLabelText = "proprietaire".equalsIgnoreCase(u.getRoles()) ? "Propriétaire PME" : "Investisseur";
        Label role = new Label(roleLabelText);
        role.setStyle("-fx-font-size: 12px; -fx-text-fill: #6366f1; -fx-background-color: #f5f3ff; -fx-padding: 3 10; -fx-background-radius: 15;");
        
        infoBox.getChildren().addAll(name, role);

        // Contact Details
        VBox contactBox = new VBox(10);
        contactBox.setAlignment(Pos.CENTER_LEFT);
        contactBox.setStyle("-fx-padding: 10 0;");
        
        String emailStr = u.getEmail() != null ? u.getEmail() : "Email non renseigné";
        Label email = new Label("📧 " + emailStr);
        email.setStyle("-fx-font-size: 13px; -fx-text-fill: #475569;");
        
        Label phone = new Label("📱 " + (u.getTel() != null ? u.getTel() : "Non renseigné"));
        phone.setStyle("-fx-font-size: 13px; -fx-text-fill: #475569;");
        
        contactBox.getChildren().addAll(email, phone);

        // Action Buttons
        HBox actionBox = new HBox(10);
        actionBox.setAlignment(Pos.CENTER);

        Button btnEmail = new Button("Email");
        btnEmail.getStyleClass().add("btn-secondary");
        btnEmail.setPrefWidth(120);
        btnEmail.setOnAction(e -> {
            if (u.getEmail() != null) {
                contactByEmail(u.getEmail());
            }
        });

        Button btnWhatsApp = new Button("WhatsApp");
        btnWhatsApp.getStyleClass().add("btn-primary");
        btnWhatsApp.setPrefWidth(120);
        btnWhatsApp.setDisable(u.getTel() == null || u.getTel().isBlank());
        btnWhatsApp.setOnAction(e -> {
            if (u.getTel() != null) {
                contactByWhatsApp(u.getTel(), u.getNom());
            }
        });

        actionBox.getChildren().addAll(btnEmail, btnWhatsApp);

        Button btnInfo = new Button("Voir Infos");
        btnInfo.getStyleClass().add("btn-secondary");
        btnInfo.setPrefWidth(250);
        btnInfo.setOnAction(e -> {
            Alert alert = new Alert(Alert.AlertType.INFORMATION);
            alert.setTitle("Détails Utilisateur");
            alert.setHeaderText(nom + " " + prenom);
            String info = "Email: " + emailStr + "\n" +
                          "Téléphone: " + (u.getTel() != null ? u.getTel() : "N/A") + "\n" +
                          "Rôle: " + roleLabelText + "\n" +
                          "Expérience: " + (u.getYearsExperience() != null ? u.getYearsExperience() : "N/A") + "\n" +
                          "Budget/Profit: " + (u.getBudget() != null ? u.getBudget() : (u.getHighestProfit() != null ? u.getHighestProfit() : "N/A"));
            alert.setContentText(info);
            alert.showAndWait();
        });

        card.getChildren().addAll(avatarStack, infoBox, new Separator(), contactBox, actionBox, btnInfo);
        return card;
    }

    private void contactByEmail(String email) {
        try {
            if (java.awt.Desktop.isDesktopSupported()) {
                java.awt.Desktop.getDesktop().mail(new java.net.URI("mailto:" + email + "?subject=Contact%20Cashfly"));
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void contactByWhatsApp(String phone, String name) {
        try {
            if (java.awt.Desktop.isDesktopSupported()) {
                String cleanPhone = phone.replaceAll("[^0-9]", "");
                if (cleanPhone.length() == 8) cleanPhone = "216" + cleanPhone;
                String msg = "Bonjour " + (name != null ? name : "") + ", je vous contacte via Cashfly.";
                String url = "https://wa.me/" + cleanPhone + "?text=" + java.net.URLEncoder.encode(msg, "UTF-8");
                java.awt.Desktop.getDesktop().browse(new java.net.URI(url));
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void filter() {
        String search = txtSearch.getText().toLowerCase();
        List<Utilisateur> filtered = masterList.stream()
                .filter(u -> {
                    String fullName = (u.getNom() != null ? u.getNom() : "") + " " + (u.getPrenom() != null ? u.getPrenom() : "");
                    String email = u.getEmail() != null ? u.getEmail() : "";
                    return fullName.toLowerCase().contains(search) || email.toLowerCase().contains(search);
                })
                .collect(Collectors.toList());
        displayCards(filtered);
    }
}
