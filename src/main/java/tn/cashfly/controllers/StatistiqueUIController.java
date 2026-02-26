package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.chart.BarChart;
import javafx.scene.chart.CategoryAxis;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.NumberAxis;
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
import java.time.format.DateTimeFormatter;
import java.util.Comparator;
import java.util.List;
import java.util.Map;
import java.util.TreeMap;
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
    private LineChart<String, Number> lineChart;
    @FXML
    private Label revenusLabel;
    @FXML
    private Label depensesLabel;
    @FXML
    private Label soldeLabel;
    @FXML
    private Label predictionLabel;

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
            updateLine(filtered);
            updatePrediction(filtered);
        } catch (SQLException e) {
            Alert a = new Alert(Alert.AlertType.ERROR);
            a.setHeaderText(null);
            a.setContentText("Erreur lors du chargement des statistiques.");
            a.showAndWait();
            clearCharts();
        }
    }

    private void updatePie(List<OPÉRATIONS> list) {
        double revenus = list.stream().filter(o -> o.getType() == OPÉRATIONS.TypeOperation.revenu)
                .mapToDouble(OPÉRATIONS::getMontant).sum();
        double depenses = list.stream().filter(o -> o.getType() == OPÉRATIONS.TypeOperation.depense)
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
        series.setName("Par catégorie");
        sums.forEach((cat, sum) -> series.getData().add(new XYChart.Data<>(cat, sum)));
        barChart.getData().add(series);
    }

    private void updateLine(List<OPÉRATIONS> list) {
        lineChart.getData().clear();
        
        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("MMM yyyy");
        
        Map<String, Double> revByMonth = new TreeMap<>();
        Map<String, Double> depByMonth = new TreeMap<>();
        
        list.stream()
            .sorted(Comparator.comparing(OPÉRATIONS::getDateOperation))
            .forEach(op -> {
                String month = op.getDateOperation().format(formatter);
                if (op.getType() == OPÉRATIONS.TypeOperation.revenu) {
                    revByMonth.merge(month, op.getMontant(), Double::sum);
                } else {
                    depByMonth.merge(month, op.getMontant(), Double::sum);
                }
            });

        XYChart.Series<String, Number> revSeries = new XYChart.Series<>();
        revSeries.setName("Revenus");
        revByMonth.forEach((m, v) -> revSeries.getData().add(new XYChart.Data<>(m, v)));

        XYChart.Series<String, Number> depSeries = new XYChart.Series<>();
        depSeries.setName("Dépenses");
        depByMonth.forEach((m, v) -> depSeries.getData().add(new XYChart.Data<>(m, v)));

        lineChart.getData().addAll(revSeries, depSeries);
    }

    private void updatePrediction(List<OPÉRATIONS> list) {
        if (list.size() < 3) {
            predictionLabel.setText("Pas assez de données pour une prédiction fiable (min 3 opérations).");
            return;
        }

        // Moyenne mensuelle des dépenses sur les 3 derniers mois
        double totalDepenses = list.stream()
                .filter(o -> o.getType() == OPÉRATIONS.TypeOperation.depense)
                .mapToDouble(OPÉRATIONS::getMontant).sum();
        
        long months = list.stream()
                .map(o -> o.getDateOperation().format(DateTimeFormatter.ofPattern("yyyy-MM")))
                .distinct().count();
        
        if (months == 0) months = 1;
        double avgMonthlyDepense = totalDepenses / months;
        double currentSolde = list.stream()
                .mapToDouble(o -> o.getType() == OPÉRATIONS.TypeOperation.revenu ? o.getMontant() : -o.getMontant())
                .sum();

        String msg;
        if (currentSolde < avgMonthlyDepense) {
            msg = String.format("⚠️ Attention : Votre solde actuel (%.2f TND) est inférieur à vos dépenses mensuelles moyennes (%.2f TND). Vous pourriez être à découvert le mois prochain si les revenus n'augmentent pas.", 
                    currentSolde, avgMonthlyDepense);
        } else {
            msg = String.format("✅ Santé financière stable : Vos dépenses mensuelles moyennes sont de %.2f TND. Avec un solde de %.2f TND, vous devriez pouvoir couvrir vos charges le mois prochain.", 
                    avgMonthlyDepense, currentSolde);
        }
        predictionLabel.setText(msg);
    }

    private void clearCharts() {
        pieChart.getData().clear();
        barChart.getData().clear();
        lineChart.getData().clear();
        revenusLabel.setText("0 TND");
        depensesLabel.setText("0 TND");
        soldeLabel.setText("0 TND");
        predictionLabel.setText("Aucune donnée.");
    }
}

