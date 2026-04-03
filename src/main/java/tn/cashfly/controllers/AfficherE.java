package tn.cashfly.controllers;

import tn.cashfly.entities.Entreprise;
import tn.cashfly.services.EntrepriseService;
import tn.cashfly.services.WeatherService;
import tn.cashfly.services.GeocodingService;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.*;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.image.Image;
import javafx.geometry.Insets;
import javafx.scene.image.ImageView;
import javafx.scene.layout.*;
import javafx.scene.paint.Color;
import javafx.scene.shape.Circle;
import javafx.stage.Stage;

import tn.cashfly.DashboardController;
import tn.cashfly.session.UserSession;
import org.json.JSONObject;

import java.math.BigDecimal;
import java.net.URL;
import java.sql.*;
import java.util.Comparator;
import java.util.List;
import java.util.ResourceBundle;
import java.util.stream.Collectors;


public class AfficherE implements Initializable {

    @FXML
    private FlowPane cardsContainer;
    @FXML private TextField txtSearch;
    @FXML private ComboBox<String> cbSort;


    @FXML private ImageView weatherIcon;
    @FXML private Label labelTemperature;
    @FXML private Label labelDescription;
    @FXML private Label labelHumidity;
    @FXML private Label labelWind;
    @FXML private VBox weatherCard;

    @FXML private Label labelTotalRevenu;
    @FXML private Label labelTotalDepense;
    @FXML private Label labelTotalDocs;

    private final EntrepriseService service = new EntrepriseService();
    private final tn.cashfly.services.DocumentService docService = new tn.cashfly.services.DocumentService();
    private ObservableList<Entreprise> masterList;

    @Override
    public void initialize(URL url, ResourceBundle rb) {

        cbSort.getItems().addAll(
                "Nom (A → Z)",
                "Nom (Z → A)",
                "Capital ↑",
                "Capital ↓"
        );
        load();

        txtSearch.textProperty().addListener((obs, o, n) -> filter());
    }

    private void updateStats(Entreprise e) {
        try {
            Connection cnx = tn.cashfly.utils.MyDataBase.getInstance().getCnx();
            
            // Total revenus / dépenses pour cette entreprise
            String sql = """
                SELECT 
                    SUM(CASE WHEN o.type = 'revenu' THEN o.montant ELSE 0 END) AS total_revenus,
                    SUM(CASE WHEN o.type = 'depense' THEN o.montant ELSE 0 END) AS total_depenses
                FROM OPÉRATIONS o
                JOIN TRÉSORERIE t ON o.id_tresorerie = t.id_tresorerie
                WHERE t.id_entreprise = ?
            """;
            
            try (PreparedStatement ps = cnx.prepareStatement(sql)) {
                ps.setInt(1, e.getIdEntreprise());
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next()) {
                        labelTotalRevenu.setText(String.format("%.2f TND", rs.getDouble("total_revenus")));
                        labelTotalDepense.setText(String.format("%.2f TND", rs.getDouble("total_depenses")));
                    }
                }
            }

            // Nombre de documents
            int docCount = docService.getByEntreprise(e.getIdEntreprise()).size();
            labelTotalDocs.setText(String.valueOf(docCount));

        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    private void updateWeather(Entreprise e) {

        String response = WeatherService.getWeather(
                e.getLatitude(),
                e.getLongitude()
        );

        if (response == null) return;

        JSONObject obj = new JSONObject(response);

        double temp = obj.getJSONObject("main").getDouble("temp");
        int humidity = obj.getJSONObject("main").getInt("humidity");
        double wind = obj.getJSONObject("wind").getDouble("speed");

        JSONObject weatherObj =
                obj.getJSONArray("weather").getJSONObject(0);

        String description = weatherObj.getString("description");
        String mainWeather = weatherObj.getString("main");

        switch (mainWeather) {
            case "Clear" ->
                    weatherCard.setStyle("-fx-background-color: linear-gradient(to right, #f7971e, #ffd200); -fx-background-radius: 15; -fx-padding:20;");
            case "Clouds" ->
                    weatherCard.setStyle("-fx-background-color: linear-gradient(to right, #bdc3c7, #2c3e50); -fx-background-radius: 15; -fx-padding:20;");
            default ->
                    weatherCard.setStyle("-fx-background-color: linear-gradient(to right, #4facfe, #00f2fe); -fx-background-radius: 15; -fx-padding:20;");
        }

        String iconCode = weatherObj.getString("icon");
        weatherIcon.setImage(new Image(
                "https://openweathermap.org/img/wn/" + iconCode + "@2x.png"
        ));

        labelTemperature.setText(temp + " °C");
        labelDescription.setText(description);
        labelHumidity.setText("Humidité : " + humidity + " %");
        labelWind.setText("Vent : " + wind + " m/s");
    }

    private VBox createCard(Entreprise e) {
        VBox card = new VBox(10);
        card.setPrefWidth(320);
        card.setStyle("-fx-background-color: white; " +
                     "-fx-padding: 20; " +
                     "-fx-background-radius: 15; " +
                     "-fx-effect: dropshadow(three-pass-box, rgba(0,0,0,0.1), 10, 0, 0, 5); " +
                     "-fx-border-color: #f1f5f9; " +
                     "-fx-border-radius: 15; " +
                     "-fx-border-width: 1;");

        // Header with Logo/Icon placeholder
        HBox header = new HBox(15);
        header.setAlignment(javafx.geometry.Pos.CENTER_LEFT);
        
        Circle logoCircle = new Circle(20, Color.web("#013b63"));
        Label logoLetter = new Label(e.getNom().substring(0, 1).toUpperCase());
        logoLetter.setStyle("-fx-text-fill: white; -fx-font-weight: bold; -fx-font-size: 16;");
        StackPane logoContainer = new StackPane(logoCircle, logoLetter);

        VBox titleBox = new VBox(2);
        Label nomLabel = new Label(e.getNom());
        nomLabel.setStyle("-fx-font-size: 18; -fx-font-weight: bold; -fx-text-fill: #1e293b;");
        Label secteurLabel = new Label(e.getSecteur());
        secteurLabel.setStyle("-fx-font-size: 12; -fx-text-fill: #64748b;");
        titleBox.getChildren().addAll(nomLabel, secteurLabel);
        
        header.getChildren().addAll(logoContainer, titleBox);

        // Content
        VBox content = new VBox(8);
        content.setPadding(new Insets(10, 0, 10, 0));

        Label adresse = new Label("📍 " + (e.getAdresse() != null ? e.getAdresse() : "Pas d'adresse"));
        adresse.setStyle("-fx-text-fill: #475569; -fx-font-size: 13;");
        adresse.setWrapText(true);

        Label forme = new Label("⚖️ " + e.getFormeJuridique());
        forme.setStyle("-fx-text-fill: #475569; -fx-font-size: 13;");

        Label capital = new Label("💰 Capital: " + String.format("%,.2f", e.getCapital()) + " TND");
        capital.setStyle("-fx-text-fill: #013b63; -fx-font-weight: bold; -fx-font-size: 13;");

        content.getChildren().addAll(adresse, forme, capital);

        // Actions
        HBox actions = new HBox(10);
        actions.setAlignment(javafx.geometry.Pos.CENTER_RIGHT);

        Button update = new Button("Modifier");
        update.setOnAction(ev -> {
            ev.consume();
            updateEntreprise(e);
        });
        update.getStyleClass().add("btn-secondary");
        update.setStyle("-fx-background-radius: 8; -fx-font-weight: bold; -fx-padding: 8 15;");

        Button delete = new Button("Supprimer");
        delete.setOnAction(ev -> {
            ev.consume();
            Alert alert = new Alert(Alert.AlertType.CONFIRMATION);
            alert.setTitle("Confirmation de suppression");
            alert.setHeaderText("Voulez-vous vraiment supprimer cette entreprise ?");
            alert.setContentText(e.getNom());
            alert.showAndWait().ifPresent(response -> {
                if (response == ButtonType.OK) {
                    deleteEntreprise(e);
                }
            });
        });
        delete.getStyleClass().add("btn-danger");
        delete.setStyle("-fx-background-radius: 8; -fx-font-weight: bold; -fx-padding: 8 15;");

        actions.getChildren().addAll(update, delete);

        card.getChildren().addAll(header, content, actions);

        card.getStyleClass().add("card");

        card.setOnMouseClicked(ev -> {
            updateWeather(e);
            updateStats(e);
            // Highlight selected card
            cardsContainer.getChildren().forEach(n -> {
                n.getStyleClass().remove("card-selected");
            });
            card.getStyleClass().add("card-selected");
            
            UserSession.setCurrentEntreprise(e.getIdEntreprise(), e.getNom());
        });

        return card;
    }

    private void load() {
        try {
            cardsContainer.getChildren().clear();

            Integer userId = UserSession.getUserId();
            List<Entreprise> entreprises = service.afficher();
            
            // Filter by current user if logged in
            if (userId != null) {
                entreprises = entreprises.stream()
                        .filter(e -> e.getIdProprietaire() == userId)
                        .collect(Collectors.toList());
            }

            masterList = FXCollections.observableArrayList(entreprises);
            entreprises.forEach(e ->
                    cardsContainer.getChildren().add(createCard(e))
            );

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
    private void filter() {

        String search = txtSearch.getText().toLowerCase();
        cardsContainer.getChildren().clear();

        masterList.stream()
                .filter(e -> e.getNom().toLowerCase().contains(search))
                .forEach(e -> cardsContainer.getChildren().add(createCard(e)));
    }

    private void deleteEntreprise(Entreprise e) {
        try {
            service.supprimer(e);
            load();
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    @FXML
    private void sortEntreprise() {
        if (cbSort.getValue() == null) return;

        List<Entreprise> sorted = masterList.stream().collect(Collectors.toList());

        switch (cbSort.getValue()) {
            case "Nom (A → Z)" -> sorted.sort(Comparator.comparing(e -> e.getNom().toLowerCase()));
            case "Nom (Z → A)" -> sorted.sort((e1, e2) -> e2.getNom().compareToIgnoreCase(e1.getNom()));
            case "Date ↑" -> sorted.sort(Comparator.comparing(Entreprise::getDateCreation, Comparator.nullsLast(Comparator.naturalOrder())));
            case "Date ↓" -> sorted.sort((e1, e2) -> {
                if (e1.getDateCreation() == null) return 1;
                if (e2.getDateCreation() == null) return -1;
                return e2.getDateCreation().compareTo(e1.getDateCreation());
            });
        }

        cardsContainer.getChildren().clear();
        sorted.forEach(e -> cardsContainer.getChildren().add(createCard(e)));
    }

    @FXML
    private void refresh() {
        load();
        txtSearch.clear();
        cbSort.setValue(null);
    }

    private void updateEntreprise(Entreprise e) {
        if (DashboardController.getInstance() != null) {
            DashboardController.getInstance().showModifierEntreprise(e);
        }
    }

    @FXML
    private void showAjouter(ActionEvent event) {
        if (DashboardController.getInstance() != null) {
            DashboardController.getInstance().showAjouterEntreprise();
        }
    }

    @FXML
    private void retour(ActionEvent event) {
        if (DashboardController.getInstance() != null) {
            DashboardController.getInstance().showHome();
        }
    }
}
