package com.example.gestion_entreprises.controllers;

        import com.example.gestion_entreprises.entities.Entreprise;
        import com.example.gestion_entreprises.services.EntrepriseService;
        import javafx.scene.control.*;
        import javafx.scene.layout.GridPane;
        import javafx.collections.transformation.FilteredList;
        import javafx.collections.transformation.SortedList;

        import javafx.collections.FXCollections;
        import javafx.collections.ObservableList;
        import javafx.event.ActionEvent;
        import javafx.fxml.FXML;
        import javafx.fxml.Initializable;
        import javafx.scene.Node;
        import javafx.scene.control.*;
        import javafx.fxml.FXMLLoader;
        import javafx.scene.Parent;
        import javafx.scene.Scene;
        import javafx.stage.Stage;

        import java.math.BigDecimal;
        import java.net.URL;
        import java.sql.SQLException;
        import java.time.LocalDate;
        import java.util.List;
        import java.util.ResourceBundle;

public class AfficherE implements Initializable {

    // ===================== TABLE =====================
    @FXML
    private TableView<Entreprise> tableEntreprise;

    @FXML
    private TableColumn<Entreprise, Integer> colId;

    @FXML
    private TableColumn<Entreprise, String> colNom;

    @FXML
    private TableColumn<Entreprise, String> colSecteur;

    @FXML
    private TableColumn<Entreprise, String> colForme;

    @FXML
    private TableColumn<Entreprise, LocalDate> colDate;

    @FXML
    private TableColumn<Entreprise, BigDecimal> colCapital;

    @FXML
    private TableColumn<Entreprise, Integer> colProprietaire;

    // ===================== UI =====================
    @FXML
    private TextField txtSearch;

    @FXML
    private ComboBox<String> cbSort;


    // ===================== DATA =====================
    private final EntrepriseService entrepriseService = new EntrepriseService();
    private final ObservableList<Entreprise> entrepriseList = FXCollections.observableArrayList();
    private FilteredList<Entreprise> filteredEntreprises;

    // ===================== INITIALIZE =====================
    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        cbSort.getItems().addAll(
                "Nom (A → Z)",
                "Nom (Z → A)",
                "Date création (Ancienne → Récente)",
                "Date création (Récente → Ancienne)"
        );
        txtSearch.textProperty().addListener((obs, oldValue, newValue) -> {

            filteredEntreprises.setPredicate(ent -> {

                if (newValue == null || newValue.isEmpty())
                    return true;

                String search = newValue.toLowerCase();

                return ent.getNom().toLowerCase().contains(search)
                        || ent.getSecteur().toLowerCase().contains(search)
                        || ent.getFormeJuridique().toLowerCase().contains(search);
            });
        });

        // Bind columns
        colId.setCellValueFactory(data -> data.getValue().idEntrepriseProperty().asObject());
        colNom.setCellValueFactory(data -> data.getValue().nomProperty());
        colSecteur.setCellValueFactory(data -> data.getValue().secteurProperty());
        colForme.setCellValueFactory(data -> data.getValue().formeJuridiqueProperty());
        colDate.setCellValueFactory(data -> data.getValue().dateCreationProperty());
        colCapital.setCellValueFactory(data -> data.getValue().capitalProperty());
        colProprietaire.setCellValueFactory(data -> data.getValue().idProprietaireProperty().asObject());

        loadEntreprises();
    }

    // ===================== LOAD =====================
    private void loadEntreprises() {
        try {
            ObservableList<Entreprise> data =
                    FXCollections.observableArrayList(entrepriseService.afficher());

            filteredEntreprises = new FilteredList<>(data, p -> true);

            SortedList<Entreprise> sortedList = new SortedList<>(filteredEntreprises);
            sortedList.comparatorProperty().bind(tableEntreprise.comparatorProperty());

            tableEntreprise.setItems(sortedList);

        } catch (SQLException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", e.getMessage());
        }
    }

    // ===================== SEARCH =====================
    @FXML
    private void searchEntreprise() {
        String keyword = txtSearch.getText();

        if (keyword == null || keyword.isEmpty()) {
            loadEntreprises();
            return;
        }

        try {
            List<Entreprise> result =
                    entrepriseService.searchByNom(keyword);
            tableEntreprise.setItems(FXCollections.observableArrayList(result));
        } catch (SQLException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", e.getMessage());
        }
    }

    // ===================== DELETE =====================
    @FXML
    private void deleteEntreprise() {

        Entreprise selected = tableEntreprise.getSelectionModel().getSelectedItem();

        if (selected == null) {
            showAlert(Alert.AlertType.WARNING,
                    "Aucune sélection",
                    "Veuillez sélectionner une entreprise.");
            return;
        }

        Alert confirm = new Alert(Alert.AlertType.CONFIRMATION);
        confirm.setTitle("Confirmation");
        confirm.setHeaderText(null);
        confirm.setContentText("Supprimer cette entreprise ?");

        if (confirm.showAndWait().orElse(ButtonType.CANCEL) == ButtonType.OK) {
            try {
                entrepriseService.supprimer(selected);
                loadEntreprises();
            } catch (SQLException e) {
                showAlert(Alert.AlertType.ERROR, "Erreur", e.getMessage());
            }
        }
    }

    // ===================== REFRESH =====================
    @FXML
    private void refresh() {
        txtSearch.clear();
        loadEntreprises();
    }

    // ===================== RETOUR =====================
    @FXML
    private void retour(ActionEvent event) {
        changeScreen(event,
                "/com/example/gestion_entreprises/MainMenu.fxml",
                "Menu principal");
    }

    // ===================== UTILS =====================
    private void showAlert(Alert.AlertType type, String title, String msg) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(msg);
        alert.showAndWait();
    }

    private void changeScreen(ActionEvent event, String fxmlPath, String title) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource(fxmlPath));
            Parent root = loader.load();

            Stage stage = (Stage) ((Node) event.getSource())
                    .getScene()
                    .getWindow();

            stage.setTitle(title);
            stage.setScene(new Scene(root));
            stage.show();

        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    @FXML
    private void updateEntreprise() {

        Entreprise selected = tableEntreprise.getSelectionModel().getSelectedItem();

        if (selected == null) {
            showAlert(Alert.AlertType.WARNING,
                    "Aucune sélection",
                    "Veuillez sélectionner une entreprise à modifier.");
            return;
        }

        // ===== Dialog =====
        Dialog<ButtonType> dialog = new Dialog<>();
        dialog.setTitle("Modifier Entreprise");
        dialog.setHeaderText("Entreprise ID : " + selected.getIdEntreprise());

        ButtonType saveBtn = new ButtonType("Enregistrer", ButtonBar.ButtonData.OK_DONE);
        dialog.getDialogPane().getButtonTypes().addAll(saveBtn, ButtonType.CANCEL);

        // ===== Fields =====
        TextField tfNom = new TextField(selected.getNom());
        TextField tfSecteur = new TextField(selected.getSecteur());
        TextField tfForme = new TextField(selected.getFormeJuridique());
        DatePicker dpDate = new DatePicker(selected.getDateCreation());
        TextField tfCapital = new TextField(
                selected.getCapital() != null ? selected.getCapital().toString() : ""
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
        grid.add(tfForme, 1, 2);

        grid.add(new Label("Date création:"), 0, 3);
        grid.add(dpDate, 1, 3);

        grid.add(new Label("Capital:"), 0, 4);
        grid.add(tfCapital, 1, 4);

        grid.add(new Label("ID Propriétaire:"), 0, 5);
        grid.add(tfProprietaire, 1, 5);

        dialog.getDialogPane().setContent(grid);

        dialog.showAndWait().ifPresent(result -> {
            if (result == saveBtn) {
                try {
                    selected.setNom(tfNom.getText());
                    selected.setSecteur(tfSecteur.getText());
                    selected.setFormeJuridique(tfForme.getText());
                    selected.setDateCreation(dpDate.getValue());
                    selected.setCapital(new BigDecimal(tfCapital.getText()));
                    selected.setIdProprietaire(
                            Integer.parseInt(tfProprietaire.getText())
                    );

                    entrepriseService.modifier(selected);
                    loadEntreprises();

                    showAlert(Alert.AlertType.INFORMATION,
                            "Succès",
                            "Entreprise modifiée avec succès.");

                } catch (Exception e) {
                    showAlert(Alert.AlertType.ERROR,
                            "Erreur",
                            "Vérifiez les valeurs saisies.");
                }
            }
        });
    }
    @FXML
    private void sortEntreprise() {

        String choice = cbSort.getValue();

        if (choice == null) {
            showAlert(Alert.AlertType.WARNING,
                    "Tri",
                    "Veuillez choisir un critère de tri.");
            return;
        }

        tableEntreprise.getSortOrder().clear();

        switch (choice) {

            case "Nom (A → Z)":
                colNom.setSortType(TableColumn.SortType.ASCENDING);
                tableEntreprise.getSortOrder().add(colNom);
                break;

            case "Nom (Z → A)":
                colNom.setSortType(TableColumn.SortType.DESCENDING);
                tableEntreprise.getSortOrder().add(colNom);
                break;

            case "Date création (Ancienne → Récente)":
                colDate.setSortType(TableColumn.SortType.ASCENDING);
                tableEntreprise.getSortOrder().add(colDate);
                break;

            case "Date création (Récente → Ancienne)":
                colDate.setSortType(TableColumn.SortType.DESCENDING);
                tableEntreprise.getSortOrder().add(colDate);
                break;
        }
    }

}
