package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.chart.BarChart;
import javafx.scene.chart.PieChart;
import javafx.scene.chart.XYChart;
import javafx.scene.control.Alert;
import javafx.scene.control.ComboBox;
import javafx.scene.control.DatePicker;
import javafx.scene.control.Label;
import tn.cashfly.entities.OPÉRATIONS;
import tn.cashfly.session.UserSession;

import java.sql.SQLException;
import java.time.LocalDate;
import java.time.LocalDateTime;
import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

public class StatistiqueUIController {

    @FXML
    private ComboBox<String> scopeBox;
    @FXML
    private DatePicker fromDate;
    @FXML
    private DatePicker toDate;
    @FXML
    private PieChart pieChart;
    @FXML
    private BarChart<String, Number> barChart;
    @FXML
    private Label revenusLabel;
    @FXML
    private Label depensesLabel;
    @FXML
    private Label soldeLabel;

    private final OperationController operationController = new OperationController();

    @FXML
    public void initialize() {
        scopeBox.setItems(FXCollections.observableArrayList("Entreprise", "Trésorerie"));
        if (UserSession.getCurrentTresorerieId() != null) {
            scopeBox.setValue("Trésorerie");
        } else {
            scopeBox.setValue("Entreprise");
        }
        onRefresh();
    }

    @FXML
    public void onRefresh() {
        Integer entId = UserSession.getCurrentEntrepriseId();
        if (entId == null) {
            Alert a = new Alert(Alert.AlertType.INFORMATION);
            a.setHeaderText(null);
            a.setContentText("Veuillez d'abord sélectionner une entreprise.");
            a.showAndWait();
            clearCharts();
            return;
        }
        try {
            List<OPÉRATIONS> all = operationController.getAllOperations();
            List<OPÉRATIONS> filtered = all.stream()
                    .filter(op -> op.getTresorerie() != null)
                    .filter(op -> {
                        if ("Trésorerie".equals(scopeBox.getValue())) {
                            Integer t = UserSession.getCurrentTresorerieId();
                            return t != null && op.getTresorerie().getIdTresorerie() == t;
                        } else {
                            return op.getTresorerie().getIdEntreprise() == entId;
                        }
                    })
                    .filter(op -> {
                        LocalDateTime d = op.getDateOperation();
                        if (d == null) return true;
                        LocalDate fd = fromDate.getValue();
                        LocalDate td = toDate.getValue();
                        boolean ok = true;
                        if (fd != null) ok = ok && !d.toLocalDate().isBefore(fd);
                        if (td != null) ok = ok && !d.toLocalDate().isAfter(td);
                        return ok;
                    })
                    .collect(Collectors.toList());
            updatePie(filtered);
            updateBar(filtered);
        } catch (SQLException e) {
            Alert a = new Alert(Alert.AlertType.ERROR);
            a.setHeaderText(null);
            a.setContentText("Erreur lors du chargement des statistiques.");
            a.showAndWait();
            clearCharts();
        }
    }

    private void updatePie(List<OPÉRATIONS> list) {
        double revenus = list.stream().filter(o -> "revenu".equalsIgnoreCase(o.getType()))
                .mapToDouble(OPÉRATIONS::getMontant).sum();
        double depenses = list.stream().filter(o -> "depense".equalsIgnoreCase(o.getType()))
                .mapToDouble(OPÉRATIONS::getMontant).sum();
        ObservableList<PieChart.Data> pie = FXCollections.observableArrayList(
                new PieChart.Data("Revenus", revenus),
                new PieChart.Data("Dépenses", depenses)
        );
        pieChart.setData(pie);
        revenusLabel.setText(String.format("%.2f TND", revenus));
        depensesLabel.setText(String.format("%.2f TND", depenses));
        soldeLabel.setText(String.format("%.2f TND", revenus - depenses));
    }

    private void updateBar(List<OPÉRATIONS> list) {
        Map<String, Double> sums = list.stream()
                .collect(Collectors.groupingBy(
                        o -> o.getCategorie() == null || o.getCategorie().isBlank() ? "(Aucune)" : o.getCategorie(),
                        Collectors.summingDouble(OPÉRATIONS::getMontant)
                ));
        barChart.getData().clear();
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        sums.forEach((cat, sum) -> series.getData().add(new XYChart.Data<>(cat, sum)));
        barChart.getData().add(series);
    }

    private void clearCharts() {
        pieChart.getData().clear();
        barChart.getData().clear();
        revenusLabel.setText("0 TND");
        depensesLabel.setText("0 TND");
        soldeLabel.setText("0 TND");
    }
}

