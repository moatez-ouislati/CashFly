package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.collections.transformation.FilteredList;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.stage.Stage;
import tn.cashfly.entities.JPO;
import tn.cashfly.services.ServiceJPO;

import java.io.IOException;
import java.sql.SQLException;
import java.util.Date;
import java.util.List;

public class ListJPOController {

    @FXML
    private Button backButton;
    @FXML
    private TextField filterField;
    @FXML
    private TableView<JPO> jpoTable;
    @FXML
    private TableColumn<JPO, Integer> idCol;
    @FXML
    private TableColumn<JPO, String> titreCol;
    @FXML
    private TableColumn<JPO, Date> dateCol;
    @FXML
    private TableColumn<JPO, String> lieuCol;
    @FXML
    private TableColumn<JPO, String> descCol;
    @FXML
    private Label statusLabel;

    private ServiceJPO serviceJPO;
    private ObservableList<JPO> jpoList;
    private FilteredList<JPO> filteredList;

    @FXML
    public void initialize() {
        serviceJPO = new ServiceJPO();
        setupTable();
        loadData();
        setupFilter();
    }

    private void setupTable() {
        idCol.setCellValueFactory(new PropertyValueFactory<>("id_evenement"));
        titreCol.setCellValueFactory(new PropertyValueFactory<>("titre"));
        dateCol.setCellValueFactory(new PropertyValueFactory<>("date_evenement"));
        lieuCol.setCellValueFactory(new PropertyValueFactory<>("lieu"));
        descCol.setCellValueFactory(new PropertyValueFactory<>("description"));

        // Auto-resize columns
        jpoTable.setColumnResizePolicy(TableView.CONSTRAINED_RESIZE_POLICY);
    }

    private void loadData() {
        try {
            List<JPO> list = serviceJPO.getAll();
            jpoList = FXCollections.observableArrayList(list);
            filteredList = new FilteredList<>(jpoList, p -> true);
            jpoTable.setItems(filteredList);
            updateStatus();
        } catch (SQLException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur de chargement", e.getMessage());
        }
    }

    private void setupFilter() {
        filterField.textProperty().addListener((observable, oldValue, newValue) -> {
            filteredList.setPredicate(jpo -> {
                if (newValue == null || newValue.isEmpty()) {
                    return true;
                }
                String lowerCaseFilter = newValue.toLowerCase();
                return jpo.getTitre().toLowerCase().contains(lowerCaseFilter) ||
                        jpo.getLieu().toLowerCase().contains(lowerCaseFilter);
            });
            updateStatus();
        });
    }

    @FXML
    private void handleRefresh() {
        loadData();
        filterField.clear();
    }

    @FXML
    private void handleBack() {
        navigateToMain();
    }

    private void updateStatus() {
        int count = filteredList != null ? filteredList.size() : 0;
        statusLabel.setText(count + " JPO(s) trouvée(s)");
    }

    private void navigateToMain() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/MainJPO.fxml"));
            Parent root = loader.load();
            Stage stage = (Stage) backButton.getScene().getWindow();
            Scene scene = new Scene(root);
            scene.getStylesheets().add(getClass().getResource("/tn/cashfly/Styles/custom.css").toExternalForm());
            stage.setScene(scene);
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void showAlert(Alert.AlertType type, String title, String header, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(header);
        alert.setContentText(content);
        alert.showAndWait();
    }
}