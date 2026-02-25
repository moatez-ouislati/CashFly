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
import tn.cashfly.models.RendementInvestissement;
import tn.cashfly.models.Utilisateur;
import tn.cashfly.services.ServiceInvest;
import tn.cashfly.services.ServiceRendement;
import tn.cashfly.services.ServiceUtilisateur;
import javafx.scene.chart.*;

import java.math.BigDecimal;
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

    // Rendements Table
    @FXML
    private TableView<RendementInvestissement> returnsTable;
    @FXML
    private TableColumn<RendementInvestissement, Integer> colRdtId;
    @FXML
    private TableColumn<RendementInvestissement, Integer> colRdtInvId;
    @FXML
    private TableColumn<RendementInvestissement, Date> colRdtDate;
    @FXML
    private TableColumn<RendementInvestissement, BigDecimal> colRdtGain;
    @FXML
    private TableColumn<RendementInvestissement, BigDecimal> colRdtPerte;
    @FXML
    private TableColumn<RendementInvestissement, BigDecimal> colRdtValeur;

    // Charts
    @FXML
    private PieChart pieChart;
    @FXML
    private BarChart<String, Number> barChart;
    @FXML
    private LineChart<String, Number> lineChart;

    // Reports View Charts
    @FXML
    private PieChart pieChartReports;
    @FXML
    private BarChart<String, Number> barChartReports;
    @FXML
    private LineChart<String, Number> lineChartReports;

    // Users Table
    @FXML
    private TableView<Utilisateur> usersTable;
    @FXML
    private TableColumn<Utilisateur, Integer> colUserId;
    @FXML
    private TableColumn<Utilisateur, String> colUserNom;
    @FXML
    private TableColumn<Utilisateur, String> colUserType;
    @FXML
    private TableColumn<Utilisateur, String> colUserEmail;
    @FXML
    private TableColumn<Utilisateur, String> colUserStatut;

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

    // Rendement Form
    @FXML
    private TextField txtIdInvestissementRDT;
    @FXML
    private TextField txtDateCalcul;
    @FXML
    private TextField txtGain;
    @FXML
    private TextField txtPerte;
    @FXML
    private TextField txtValeurPortefeuille;

    private ServiceInvest serviceInvest;
    private ServiceRendement serviceRendement;
    private ServiceUtilisateur serviceUtilisateur;
    private ObservableList<Investissement> investmentList;
    private ObservableList<RendementInvestissement> rendementList;
    private ObservableList<Utilisateur> userList;

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        serviceInvest = new ServiceInvest();
        serviceRendement = new ServiceRendement();
        serviceUtilisateur = new ServiceUtilisateur();
        investmentList = FXCollections.observableArrayList();
        rendementList = FXCollections.observableArrayList();
        userList = FXCollections.observableArrayList();

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

        // Rendements Table
        colRdtId.setCellValueFactory(new PropertyValueFactory<>("idRendement"));
        colRdtInvId.setCellValueFactory(new PropertyValueFactory<>("idInvestissement"));
        colRdtDate.setCellValueFactory(new PropertyValueFactory<>("dateCalcul"));
        colRdtGain.setCellValueFactory(new PropertyValueFactory<>("gain"));
        colRdtPerte.setCellValueFactory(new PropertyValueFactory<>("perte"));
        colRdtValeur.setCellValueFactory(new PropertyValueFactory<>("valeurPortefeuille"));

        returnsTable.setItems(rendementList);

        // Users Table
        colUserId.setCellValueFactory(new PropertyValueFactory<>("idUtilisateur"));
        colUserNom.setCellValueFactory(new PropertyValueFactory<>("nomComplet"));
        colUserType.setCellValueFactory(new PropertyValueFactory<>("role"));
        colUserEmail.setCellValueFactory(new PropertyValueFactory<>("email"));
        // Assuming 'statut' might not exist in Utilisateur yet, but we'll map role to
        // it for now or just skip
        colUserStatut.setCellValueFactory(new PropertyValueFactory<>("role"));

        usersTable.setItems(userList);
    }

    private void loadData() {
        try {
            investmentList.clear();
            investmentList.addAll(serviceInvest.getAll());

            rendementList.clear();
            rendementList.addAll(serviceRendement.getAll());

            userList.clear();
            userList.addAll(serviceUtilisateur.getAll());

            updateCharts();
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur de chargement",
                    "Impossible de charger les données : " + e.getMessage());
        }
    }

    private void setupSelectionListener() {
        investTable.getSelectionModel().selectedItemProperty().addListener((obs, oldSelection, newSelection) -> {
            if (newSelection != null) {
                fillForm(newSelection);
            }
        });

        returnsTable.getSelectionModel().selectedItemProperty().addListener((obs, oldSelection, newSelection) -> {
            if (newSelection != null) {
                fillRendementForm(newSelection);
            }
        });
    }

    private void fillRendementForm(RendementInvestissement r) {
        txtIdInvestissementRDT.setText(String.valueOf(r.getIdInvestissement()));
        txtDateCalcul.setText(r.getDateCalcul().toString());
        txtGain.setText(String.valueOf(r.getGain()));
        txtPerte.setText(String.valueOf(r.getPerte()));
        txtValeurPortefeuille.setText(String.valueOf(r.getValeurPortefeuille()));
    }

    private void updateCharts() {
        updatePieChart(pieChart);
        updatePieChart(pieChartReports);
        updateBarChart(barChart);
        updateBarChart(barChartReports);
        updateLineChart(lineChart);
        updateLineChart(lineChartReports);
    }

    private void updatePieChart(PieChart chart) {
        if (chart == null)
            return;
        ObservableList<PieChart.Data> pieData = FXCollections.observableArrayList();

        // Group investments by Enterprise ID
        java.util.Map<String, Double> dataMap = new java.util.HashMap<>();
        for (Investissement i : investmentList) {
            String label = "Entreprise #" + i.getIdEntreprise();
            dataMap.put(label, dataMap.getOrDefault(label, 0.0) + i.getMontant().doubleValue());
        }

        dataMap.forEach((k, v) -> pieData.add(new PieChart.Data(k, v)));
        chart.setData(pieData);
    }

    private void updateBarChart(BarChart<String, Number> chart) {
        if (chart == null)
            return;
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        series.setName("Montant Investi");

        for (Investissement i : investmentList) {
            series.getData().add(new XYChart.Data<>(i.getDateInvestissement().toString(), i.getMontant()));
        }

        chart.getData().clear();
        chart.getData().add(series);
    }

    private void updateLineChart(LineChart<String, Number> chart) {
        if (chart == null)
            return;
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        series.setName("Gains Cumulés");

        BigDecimal cumulativeGain = BigDecimal.ZERO;
        for (RendementInvestissement r : rendementList) {
            cumulativeGain = cumulativeGain.add(r.getGain());
            series.getData().add(new XYChart.Data<>(r.getDateCalcul().toString(), cumulativeGain));
        }

        chart.getData().clear();
        chart.getData().add(series);
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
                    new BigDecimal(txtMontant.getText()),
                    Date.valueOf(LocalDate.now()), // Default to today
                    txtStatut.getText(),
                    new BigDecimal(txtTaux.getText()),
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
            selected.setMontant(new BigDecimal(txtMontant.getText()));
            selected.setStatut(txtStatut.getText());
            selected.setTauxRendementPrevu(new BigDecimal(txtTaux.getText()));
            selected.setDureeMois(Integer.parseInt(txtDuree.getText()));
            selected.setDescription(txtDescription.getText());

            serviceInvest.update(selected);
            loadData();
            clearForm();
            showAlert(Alert.AlertType.INFORMATION, "Succès", "Investissement mis à jour avec succès.");

        } catch (NumberFormatException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Veuillez vérifier les champs numériques.");
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la mise à jour : " + e.getMessage());
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
            try {
                serviceInvest.delete(selected);
                loadData();
                clearForm();
            } catch (Exception e) {
                showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la suppression : " + e.getMessage());
            }
        }
    }

    @FXML
    private void handleClear(ActionEvent event) {
        clearForm();
        investTable.getSelectionModel().clearSelection();
    }

    // --- Rendements CRUD ---

    @FXML
    private void handleAddRendement(ActionEvent event) {
        try {
            RendementInvestissement r = new RendementInvestissement(
                    Integer.parseInt(txtIdInvestissementRDT.getText()),
                    Date.valueOf(txtDateCalcul.getText()),
                    new BigDecimal(txtGain.getText()),
                    new BigDecimal(txtPerte.getText()),
                    new BigDecimal(txtValeurPortefeuille.getText()));

            serviceRendement.add(r);
            loadData();
            clearRendementForm();
            showAlert(Alert.AlertType.INFORMATION, "Succès", "Rendement ajouté avec succès.");
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de l'ajout du rendement : " + e.getMessage());
        }
    }

    @FXML
    private void handleUpdateRendement(ActionEvent event) {
        RendementInvestissement selected = returnsTable.getSelectionModel().getSelectedItem();
        if (selected == null) {
            showAlert(Alert.AlertType.WARNING, "Attention", "Veuillez sélectionner un rendement à modifier.");
            return;
        }

        try {
            selected.setIdInvestissement(Integer.parseInt(txtIdInvestissementRDT.getText()));
            selected.setDateCalcul(Date.valueOf(txtDateCalcul.getText()));
            selected.setGain(new BigDecimal(txtGain.getText()));
            selected.setPerte(new BigDecimal(txtPerte.getText()));
            selected.setValeurPortefeuille(new BigDecimal(txtValeurPortefeuille.getText()));

            serviceRendement.update(selected);
            loadData();
            clearRendementForm();
            showAlert(Alert.AlertType.INFORMATION, "Succès", "Rendement mis à jour.");
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la modification : " + e.getMessage());
        }
    }

    @FXML
    private void handleDeleteRendement(ActionEvent event) {
        RendementInvestissement selected = returnsTable.getSelectionModel().getSelectedItem();
        if (selected == null) {
            showAlert(Alert.AlertType.WARNING, "Attention", "Veuillez sélectionner un rendement à supprimer.");
            return;
        }

        Alert alert = new Alert(Alert.AlertType.CONFIRMATION);
        alert.setTitle("Confirmation");
        alert.setHeaderText("Supprimer ce rendement ?");
        alert.setContentText("Ceci est irréversible.");

        Optional<ButtonType> result = alert.showAndWait();
        if (result.isPresent() && result.get() == ButtonType.OK) {
            try {
                serviceRendement.delete(selected);
                loadData();
                clearRendementForm();
            } catch (Exception e) {
                showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la suppression : " + e.getMessage());
            }
        }
    }

    @FXML
    private void handleClearRendement(ActionEvent event) {
        clearRendementForm();
        returnsTable.getSelectionModel().clearSelection();
    }

    private void clearRendementForm() {
        txtIdInvestissementRDT.clear();
        txtDateCalcul.clear();
        txtGain.clear();
        txtPerte.clear();
        txtValeurPortefeuille.clear();
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
