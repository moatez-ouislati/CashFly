package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;
import tn.cashfly.models.Investissement;
import tn.cashfly.services.ServiceInvest;

import java.net.URL;
import java.sql.Date;
import java.time.LocalDate;
import java.util.Optional;
import java.util.ResourceBundle;

public class DashboardController implements Initializable {

    // Navigation
    @FXML
    private ScrollPane dashboardView;
    @FXML
    private VBox investmentsView;
    @FXML
    private VBox rendementsView;
    @FXML
    private VBox utilisateursView;
    @FXML
    private VBox rapportsView;
    @FXML
    private VBox parametresView;
    @FXML
    private VBox notificationsView;
    @FXML
    private Label pageTitle;
    @FXML
    private Label pageSubtitle;

    // Table
    @FXML
    private TableView<Investissement> investTable;
    @FXML
    private TableColumn<Investissement, Integer> colId;
    @FXML
    private TableColumn<Investissement, String> colDescription;
    @FXML
    private TableColumn<Investissement, Double> colMontant;
    @FXML
    private TableColumn<Investissement, String> colStatut;
    @FXML
    private TableColumn<Investissement, Date> colDate;
    @FXML
    private TableColumn<Investissement, Double> colTaux;
    @FXML
    private TableColumn<Investissement, Integer> colDuree;

    // New Views Elements
    @FXML
    private TableView<?> returnsTable;
    @FXML
    private TableView<?> usersTable;

    // Form
    @FXML
    private TextField txtDescription;
    @FXML
    private TextField txtMontant;
    @FXML
    private TextField txtStatut;
    @FXML
    private TextField txtTaux;
    @FXML
    private TextField txtDuree;
    @FXML
    private TextField txtIdEntreprise;
    @FXML
    private TextField txtIdInvestisseur;

    private ServiceInvest serviceInvest;
    private ObservableList<Investissement> investmentList;

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        serviceInvest = new ServiceInvest();
        investmentList = FXCollections.observableArrayList();

        setupTable();
        loadData();
        setupSelectionListener();
    }

    private void setupTable() {
        colId.setCellValueFactory(new PropertyValueFactory<>("idInvestissement"));
        colDescription.setCellValueFactory(new PropertyValueFactory<>("description"));
        colMontant.setCellValueFactory(new PropertyValueFactory<>("montant"));
        colStatut.setCellValueFactory(new PropertyValueFactory<>("statut"));
        colDate.setCellValueFactory(new PropertyValueFactory<>("dateInvestissement"));
        colTaux.setCellValueFactory(new PropertyValueFactory<>("tauxRendementPrevu"));
        colDuree.setCellValueFactory(new PropertyValueFactory<>("dureeMois"));

        investTable.setItems(investmentList);
    }

    private void loadData() {
        investmentList.clear();
        investmentList.addAll(serviceInvest.getAll());
    }

    private void setupSelectionListener() {
        investTable.getSelectionModel().selectedItemProperty().addListener((obs, oldSelection, newSelection) -> {
            if (newSelection != null) {
                fillForm(newSelection);
            }
        });
    }

    private void fillForm(Investissement i) {
        txtDescription.setText(i.getDescription());
        txtMontant.setText(String.valueOf(i.getMontant()));
        txtStatut.setText(i.getStatut());
        txtTaux.setText(String.valueOf(i.getTauxRendementPrevu()));
        txtDuree.setText(String.valueOf(i.getDureeMois()));
        txtIdEntreprise.setText(String.valueOf(i.getIdEntreprise()));
        txtIdInvestisseur.setText(String.valueOf(i.getIdInvestisseur()));
    }

    @FXML
    private void handleAdd(ActionEvent event) {
        try {
            Investissement i = new Investissement(
                    Integer.parseInt(txtIdInvestisseur.getText()),
                    Integer.parseInt(txtIdEntreprise.getText()),
                    Double.parseDouble(txtMontant.getText()),
                    Date.valueOf(LocalDate.now()), // Default to today
                    txtStatut.getText(),
                    Double.parseDouble(txtTaux.getText()),
                    Integer.parseInt(txtDuree.getText()),
                    txtDescription.getText());

            serviceInvest.add(i);
            loadData();
            clearForm();
            showAlert(Alert.AlertType.INFORMATION, "Succès", "Investissement ajouté avec succès.");

        } catch (NumberFormatException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Veuillez vérifier les champs numériques.");
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de l'ajout : " + e.getMessage());
        }
    }

    @FXML
    private void handleUpdate(ActionEvent event) {
        Investissement selected = investTable.getSelectionModel().getSelectedItem();
        if (selected == null) {
            showAlert(Alert.AlertType.WARNING, "Attention", "Veuillez sélectionner un investissement à modifier.");
            return;
        }

        try {
            selected.setIdInvestisseur(Integer.parseInt(txtIdInvestisseur.getText()));
            selected.setIdEntreprise(Integer.parseInt(txtIdEntreprise.getText()));
            selected.setMontant(Double.parseDouble(txtMontant.getText()));
            selected.setStatut(txtStatut.getText());
            selected.setTauxRendementPrevu(Double.parseDouble(txtTaux.getText()));
            selected.setDureeMois(Integer.parseInt(txtDuree.getText()));
            selected.setDescription(txtDescription.getText());

            serviceInvest.update(selected);
            loadData();
            clearForm();
            showAlert(Alert.AlertType.INFORMATION, "Succès", "Investissement mis à jour avec succès.");

        } catch (NumberFormatException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Veuillez vérifier les champs numériques.");
        }
    }

    @FXML
    private void handleDelete(ActionEvent event) {
        Investissement selected = investTable.getSelectionModel().getSelectedItem();
        if (selected == null) {
            showAlert(Alert.AlertType.WARNING, "Attention", "Veuillez sélectionner un investissement à supprimer.");
            return;
        }

        Alert alert = new Alert(Alert.AlertType.CONFIRMATION);
        alert.setTitle("Confirmation");
        alert.setHeaderText("Supprimer l'investissement ?");
        alert.setContentText("Êtes-vous sûr de vouloir supprimer cet investissement ?");

        Optional<ButtonType> result = alert.showAndWait();
        if (result.isPresent() && result.get() == ButtonType.OK) {
            serviceInvest.delete(selected);
            loadData();
            clearForm();
        }
    }

    @FXML
    private void handleClear(ActionEvent event) {
        clearForm();
        investTable.getSelectionModel().clearSelection();
    }

    private void clearForm() {
        txtDescription.clear();
        txtMontant.clear();
        txtStatut.clear();
        txtTaux.clear();
        txtDuree.clear();
        txtIdEntreprise.clear();
        txtIdInvestisseur.clear();
    }

    // Simple navigation handler (connect to buttons in FXML if needed, for now just
    // programmatic example)
    private void hideAllViews() {
        if (dashboardView != null)
            dashboardView.setVisible(false);
        if (investmentsView != null)
            investmentsView.setVisible(false);
        if (rendementsView != null)
            rendementsView.setVisible(false);
        if (utilisateursView != null)
            utilisateursView.setVisible(false);
        if (rapportsView != null)
            rapportsView.setVisible(false);
        if (parametresView != null)
            parametresView.setVisible(false);
        if (notificationsView != null)
            notificationsView.setVisible(false);
    }

    public void showDashboard() {
        hideAllViews();
        if (dashboardView != null)
            dashboardView.setVisible(true);
        pageTitle.setText("Console de Pilotage");
        pageSubtitle.setText("Analyse en temps réel de votre écosystème financier.");
    }

    public void showInvestments() {
        hideAllViews();
        if (investmentsView != null)
            investmentsView.setVisible(true);
        pageTitle.setText("Investissements");
        pageSubtitle.setText("Gestion des investissements et transactions.");
    }

    public void showRendements() {
        hideAllViews();
        if (rendementsView != null)
            rendementsView.setVisible(true);
        pageTitle.setText("Rendements");
        pageSubtitle.setText("Analyse des performances et KPI.");
    }

    public void showUtilisateurs() {
        hideAllViews();
        if (utilisateursView != null)
            utilisateursView.setVisible(true);
        pageTitle.setText("Utilisateurs / Clients");
        pageSubtitle.setText("Gestion des investisseurs et entreprises.");
    }

    public void showRapports() {
        hideAllViews();
        if (rapportsView != null)
            rapportsView.setVisible(true);
        pageTitle.setText("Rapports & Analytics");
        pageSubtitle.setText("Statistiques globales et comparaisons.");
    }

    public void showParametres() {
        hideAllViews();
        if (parametresView != null)
            parametresView.setVisible(true);
        pageTitle.setText("Paramètres");
        pageSubtitle.setText("Configuration de l'application.");
    }

    public void showNotifications() {
        hideAllViews();
        if (notificationsView != null)
            notificationsView.setVisible(true);
        pageTitle.setText("Notifications");
        pageSubtitle.setText("Centre d'alertes et messages.");
    }

    @FXML
    private void handleLogout(ActionEvent event) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/fxml/login.fxml"));
            Parent root = loader.load();

            Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
            Scene scene = new Scene(root);
            stage.setScene(scene);
            stage.centerOnScreen();
            stage.show();
        } catch (Exception e) {
            e.printStackTrace();
            showAlert(Alert.AlertType.ERROR, "Erreur", "Impossible de se déconnecter : " + e.getMessage());
        }
    }

    private void showAlert(Alert.AlertType type, String title, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(content);
        alert.showAndWait();
    }
}
