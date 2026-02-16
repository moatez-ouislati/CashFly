package controllers;

import entities.Utilisateur;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.HBox;
import javafx.stage.Stage;
import services.UtilisateurCrud;

public class ListeUtilisateursController {

    @FXML private TableView<Utilisateur> tableView;
    @FXML private TableColumn<Utilisateur, Integer> colId;
    @FXML private TableColumn<Utilisateur, Integer> colCin;
    @FXML private TableColumn<Utilisateur, String> colNumTel;
    @FXML private TableColumn<Utilisateur, String> colNom;
    @FXML private TableColumn<Utilisateur, String> colPrenom;
    @FXML private TableColumn<Utilisateur, String> colEmail;
    @FXML private TableColumn<Utilisateur, String> colRole;
    @FXML private TableColumn<Utilisateur, Void> colAction;
    @FXML private TextField tfrecherche;

    private final UtilisateurCrud crud = new UtilisateurCrud();
    private ObservableList<Utilisateur> data;

    @FXML
    public void initialize() {

        colId.setCellValueFactory(c ->
                new javafx.beans.property.SimpleIntegerProperty(c.getValue().getId()).asObject());
        colCin.setCellValueFactory(c ->
                new javafx.beans.property.SimpleIntegerProperty(c.getValue().getCin()).asObject());
        colNumTel.setCellValueFactory(c ->
                new javafx.beans.property.SimpleStringProperty(c.getValue().getTel()));
        colNom.setCellValueFactory(c ->
                new javafx.beans.property.SimpleStringProperty(c.getValue().getNom()));
        colPrenom.setCellValueFactory(c ->
                new javafx.beans.property.SimpleStringProperty(c.getValue().getPrenom()));
        colEmail.setCellValueFactory(c ->
                new javafx.beans.property.SimpleStringProperty(c.getValue().getEmail()));
        colRole.setCellValueFactory(c ->
                new javafx.beans.property.SimpleStringProperty(c.getValue().getRoles()));

        data = FXCollections.observableArrayList(crud.afficher());
        tableView.setItems(data);

        // 🔍 SEARCH PAR EMAIL (STREAM)
        tfrecherche.textProperty().addListener((obs, oldVal, newVal) -> {

            if (newVal == null || newVal.isEmpty()) {
                tableView.setItems(data);
                return;
            }

            String keyword = newVal.toLowerCase();

            ObservableList<Utilisateur> filtered =
                    FXCollections.observableArrayList(
                            data.stream()
                                    .filter(u ->
                                            u.getEmail() != null &&
                                                    u.getEmail().toLowerCase().contains(keyword)
                                    )
                                    .toList()
                    );

            tableView.setItems(filtered);
        });

        addActionButtons();
    }

    private void refreshTable() {
        data.setAll(crud.afficher());
        tableView.setItems(data);
    }

    private void addActionButtons() {

        colAction.setCellFactory(param -> new TableCell<>() {

            private final Button btnEdit = new Button("Modifier");
            private final Button btnDelete = new Button("Supprimer");
            private final HBox box = new HBox(5, btnEdit, btnDelete);

            {
                btnEdit.setStyle("-fx-background-color:#4CAF50; -fx-text-fill:white;");
                btnDelete.setStyle("-fx-background-color:#f44336; -fx-text-fill:white;");

                btnEdit.setOnAction(e -> {
                    Utilisateur u = getTableView().getItems().get(getIndex());
                    openProfil(u);
                });

                btnDelete.setOnAction(e -> {
                    Utilisateur u = getTableView().getItems().get(getIndex());
                    crud.supprimer(u.getId());
                    refreshTable();
                });
            }

            @Override
            protected void updateItem(Void item, boolean empty) {
                super.updateItem(item, empty);
                setGraphic(empty ? null : box);
            }
        });
    }

    private void openProfil(Utilisateur u) {

        try {
            FXMLLoader loader;
            Parent root;

            if (u.getRoles().contains("ROLE_ADMIN")) {

                System.out.println("OPEN PROFIL ADMIN");
                loader = new FXMLLoader(getClass().getResource("/ProfilAdmin.fxml"));
                root = loader.load();
                ProfilAdminController controller = loader.getController();
                controller.initData(u);

            } else if (u.getRoles().contains("ROLE_PROPRIETAIRE")) {

                System.out.println("OPEN PROFIL PROPRIETAIRE");
                loader = new FXMLLoader(getClass().getResource("/ProfilProp.fxml"));
                root = loader.load();
                ProfilPropController controller = loader.getController();
                controller.initData(u);

            } else {

                System.out.println("OPEN PROFIL MEMBRE");
                loader = new FXMLLoader(getClass().getResource("/ProfilMembre.fxml"));
                root = loader.load();
                ProfilMembreController controller = loader.getController();
                controller.initData(u);
            }

            Stage stage = new Stage();
            stage.setScene(new Scene(root));
            stage.setOnHidden(e -> refreshTable());
            stage.show();

        } catch (Exception e) {
            e.printStackTrace();
        }
    }



}
