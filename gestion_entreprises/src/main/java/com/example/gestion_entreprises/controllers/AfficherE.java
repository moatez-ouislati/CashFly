package com.example.gestion_entreprises.controllers;

import com.example.gestion_entreprises.entities.Entreprise;
import com.example.gestion_entreprises.services.EntrepriseService;
import com.example.gestion_entreprises.services.WeatherService;
import com.example.gestion_entreprises.services.GeocodingService;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.*;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.*;
import javafx.stage.Stage;

import org.json.JSONObject;

import java.math.BigDecimal;
import java.net.URL;
import java.sql.SQLException;
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

    private final EntrepriseService service = new EntrepriseService();
    private ObservableList<Entreprise> masterList;

    @Override
    public void initialize(URL url, ResourceBundle rb) {

        cbSort.getItems().addAll(
                "Nom (A → Z)",
                "Nom (Z → A)",
                "Date ↑",
                "Date ↓"

        );
        load();




        txtSearch.textProperty().addListener((obs, o, n) -> filter());

        // 🔥 LISTENER MÉTÉO
       ;
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
                    weatherCard.setStyle("""
            -fx-background-color: linear-gradient(to right, #f7971e, #ffd200);
            -fx-background-radius: 15;
            -fx-padding:20;
        """);
            case "Clouds" ->
                    weatherCard.setStyle("""
            -fx-background-color: linear-gradient(to right, #bdc3c7, #2c3e50);
            -fx-background-radius: 15;
            -fx-padding:20;
        """);
            default ->
                    weatherCard.setStyle("""
            -fx-background-color: linear-gradient(to right, #4facfe, #00f2fe);
            -fx-background-radius: 15;
            -fx-padding:20;
        """);
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

        VBox card = new VBox(8);
        card.setStyle("""
                -fx-background-color:white;
                -fx-padding:15;
                -fx-background-radius:10;
                -fx-border-radius:10;
                -fx-border-color:#ddd;
                """);

        Label nom = new Label("Nom: " + e.getNom());
        Label secteur = new Label("Secteur: " + e.getSecteur());
        Label forme = new Label("Forme: " + e.getFormeJuridique());
        Label date = new Label("Date: " + e.getDateCreation());
        Label capital = new Label("Capital: " + e.getCapital());
        Label prop = new Label("Propriétaire ID: " + e.getIdProprietaire());
        Label adresse = new Label("Adresse: " + e.getAdresse());
        adresse.setStyle("""
    -fx-text-fill: #6B7280;
    -fx-wrap-text: true;
""");

        HBox actions = new HBox(10);

        Button delete = new Button("Supprimer");
        delete.setOnAction(ev -> deleteEntreprise(e));
        delete.setStyle("""
    -fx-background-color:   #B10F0F;
    -fx-text-fill: white;
    -fx-background-radius: 8;
    -fx-font-weight: bold;
""");

        Button update = new Button("Modifier");
        update.setOnAction(ev -> updateEntreprise(e));
        update.setStyle("""
    -fx-background-color:  #013b63;
    -fx-text-fill: white;
    -fx-background-radius: 8;
    -fx-font-weight: bold;
""");

        actions.getChildren().addAll(update, delete);

        card.getChildren().addAll(nom, secteur, forme, date, capital, prop, adresse, actions);

        card.setOnMouseClicked(ev -> updateWeather(e));
        card.setOnMouseClicked(ev -> {
            updateWeather(e);
            card.setStyle(card.getStyle() + """
        -fx-border-color: #0B3C5D;
        -fx-border-width: 2;
    """);
        });
        return card;
    }

    private void load() {
        try {
            cardsContainer.getChildren().clear();

            List<Entreprise> entreprises = service.afficher();
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

        try {
            for (Entreprise e : service.afficher()) {
                if (e.getNom().toLowerCase().contains(search)
                        || e.getSecteur().toLowerCase().contains(search)) {

                    cardsContainer.getChildren().add(createCard(e));
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private void deleteEntreprise(Entreprise e) {
        try {
            service.supprimer(e);
            load();
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    private void updateEntreprise(Entreprise selected) {

        Dialog<ButtonType> dialog = new Dialog<>();
        dialog.setTitle("Modifier Entreprise");
        dialog.setHeaderText("ID : " + selected.getIdEntreprise());

        ButtonType saveBtn =
                new ButtonType("Enregistrer", ButtonBar.ButtonData.OK_DONE);

        dialog.getDialogPane().getButtonTypes()
                .addAll(saveBtn, ButtonType.CANCEL);
        TextField tfAdresse = new TextField(
                selected.getAdresse() != null ? selected.getAdresse() : ""
        );

        TextField tfNom = new TextField(selected.getNom());
        TextField tfSecteur = new TextField(selected.getSecteur());
        ComboBox<String> cbForme = new ComboBox<>();
        cbForme.getItems().addAll("SA", "SARL", "SAS");
        cbForme.setValue(selected.getFormeJuridique());
        DatePicker dpDate = new DatePicker(selected.getDateCreation());

        TextField tfCapital = new TextField(
                selected.getCapital() != null
                        ? selected.getCapital().toString()
                        : ""
        );

        TextField tfProprietaire = new TextField(
                String.valueOf(selected.getIdProprietaire())
        );

        GridPane grid = new GridPane();
        grid.setHgap(10);
        grid.setVgap(10);

        grid.add(new Label("Nom:"), 0, 0);
        grid.add(tfNom, 1, 0);

        grid.add(new Label("Secteur:"), 0, 1);
        grid.add(tfSecteur, 1, 1);

        grid.add(new Label("Forme juridique:"), 0, 2);
        grid.add(cbForme, 1, 2);

        grid.add(new Label("Date création:"), 0, 3);
        grid.add(dpDate, 1, 3);

        grid.add(new Label("Capital:"), 0, 4);
        grid.add(tfCapital, 1, 4);

        grid.add(new Label("ID Propriétaire:"), 0, 5);
        grid.add(tfProprietaire, 1, 5);
        grid.add(new Label("Adresse:"), 0, 6);
        grid.add(tfAdresse, 1, 6);

        dialog.getDialogPane().setContent(grid);

        dialog.showAndWait().ifPresent(result -> {
            if (result == saveBtn) {
                try {

                    if (tfNom.getText().isBlank()
                            || tfSecteur.getText().isBlank()
                            || tfCapital.getText().isBlank()
                            || tfProprietaire.getText().isBlank()
                            || tfAdresse.getText().isBlank()
                            || dpDate.getValue() == null
                            || cbForme.getValue() == null) {

                        throw new IllegalArgumentException();
                    }
                    BigDecimal capital = new BigDecimal(tfCapital.getText());
                    int idProp = Integer.parseInt(tfProprietaire.getText());

                    selected.setNom(tfNom.getText());
                    selected.setSecteur(tfSecteur.getText());
                    selected.setFormeJuridique(cbForme.getValue());
                    selected.setDateCreation(dpDate.getValue());
                    selected.setCapital(capital);
                    selected.setIdProprietaire(idProp);
                    double[] coords = GeocodingService.getCoordinates(tfAdresse.getText());

                    if (coords[0] == 0 && coords[1] == 0) {
                        new Alert(
                                Alert.AlertType.ERROR,
                                "Adresse invalide"
                        ).show();
                        return;
                    }

                    selected.setAdresse(tfAdresse.getText());
                    selected.setLatitude(coords[0]);
                    selected.setLongitude(coords[1]);

                    service.modifier(selected);
                    load();

                } catch (NumberFormatException ex) {

                    new Alert(
                            Alert.AlertType.ERROR,
                            "Capital et ID doivent être numériques."
                    ).show();

                } catch (Exception ex) {

                    new Alert(
                            Alert.AlertType.ERROR,
                            "Tous les champs sont obligatoires."
                    ).show();
                }
            }
        });
    }

    @FXML
    private void sortEntreprise() {

        if (cbSort.getValue() == null) return;

        try {
            List<Entreprise> list = service.afficher();

            switch (cbSort.getValue()) {
                case "Nom (A → Z)" ->
                        list.sort(Comparator.comparing(Entreprise::getNom));
                case "Nom (Z → A)" ->
                        list.sort(Comparator.comparing(Entreprise::getNom).reversed());
                case "Date ↑" ->
                        list.sort(Comparator.comparing(Entreprise::getDateCreation));
                case "Date ↓" ->
                        list.sort(Comparator.comparing(Entreprise::getDateCreation).reversed());
            }

            cardsContainer.getChildren().clear();
            list.forEach(e ->
                    cardsContainer.getChildren().add(createCard(e))
            );

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    @FXML
    private void refresh() { load(); }

    @FXML
    private void retour(ActionEvent e) throws Exception {
        Parent root = FXMLLoader.load(
                getClass().getResource("/com/example/gestion_entreprises/MainMenu.fxml")
        );
        Stage stage = (Stage)((Node)e.getSource()).getScene().getWindow();
        stage.setScene(new Scene(root));
    }
}