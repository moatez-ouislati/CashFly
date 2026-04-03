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
    @FXML
    private Label totalEntreprisesLabel;
    @FXML
    private Label totalDocumentsLabel;
    @FXML
    private BarChart<String, Number> docsBarChart;
    @FXML
    private PieChart sectorPieChart;

    private final OperationController operationController = new OperationController();
    private final tn.cashfly.services.EntrepriseService entrepriseService = new tn.cashfly.services.EntrepriseService();
    private final tn.cashfly.services.DocumentService documentService = new tn.cashfly.services.DocumentService();

    @FXML
    public void initialize() {
        try {
            if (scopeBox != null) {
                scopeBox.setItems(FXCollections.observableArrayList("Global", "Entreprise", "Trésorerie"));
                if (UserSession.getCurrentTresorerieId() != null) {
                    scopeBox.setValue("Trésorerie");
                } else if (UserSession.getCurrentEntrepriseId() != null) {
                    scopeBox.setValue("Entreprise");
                } else {
                    scopeBox.setValue("Global");
                }
            }
            onRefresh();
        } catch (Exception ex) {
            ex.printStackTrace();
            clearCharts();
        }
    }

    @FXML
    public void onRefresh() {
        try {
            Integer entId = UserSession.getCurrentEntrepriseId();
            Integer userId = UserSession.getUserId();
            String scope = scopeBox != null ? scopeBox.getValue() : "Global";

            List<OPÉRATIONS> all = operationController.getAllOperations();
            List<OPÉRATIONS> filtered;

            if ("Global".equals(scope) || entId == null) {
                // Global scope: if we can resolve owned enterprises, filter by them,
                // otherwise show all operations so that the page is never empty.
                List<Integer> myEntrepriseIds = null;
                if (userId != null) {
                    myEntrepriseIds = entrepriseService.afficher().stream()
                            .filter(e -> e.getIdProprietaire() == userId)
                            .map(tn.cashfly.entities.Entreprise::getIdEntreprise)
                            .collect(Collectors.toList());
                }

                if (myEntrepriseIds == null || myEntrepriseIds.isEmpty()) {
                    filtered = all;
                } else {
                    final List<Integer> ownedIds = myEntrepriseIds;
                    filtered = all.stream()
                            .filter(op -> op.getTresorerie() != null && ownedIds.contains(op.getTresorerie().getIdEntreprise()))
                            .collect(Collectors.toList());
                }
            } else {
                // Enterprise or Treasury scope
                filtered = all.stream()
                        .filter(op -> op.getTresorerie() != null)
                        .filter(op -> {
                            if ("Trésorerie".equals(scope)) {
                                Integer t = UserSession.getCurrentTresorerieId();
                                return t != null && op.getTresorerie().getIdTresorerie() == t;
                            } else {
                                return op.getTresorerie().getIdEntreprise() == entId;
                            }
                        })
                        .collect(Collectors.toList());
            }

            // Apply date filters
            filtered = filtered.stream()
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
            updateGlobalStats();
        } catch (Exception e) {
            Alert a = new Alert(Alert.AlertType.ERROR);
            a.setHeaderText(null);
            a.setContentText("Erreur lors du chargement des statistiques.");
            a.showAndWait();
            clearCharts();
        }
    }

    private void updatePie(List<OPÉRATIONS> list) {
        double revenus = list.stream().filter(o -> o.getType() != null && o.getType() == OPÉRATIONS.TypeOperation.revenu)
                .mapToDouble(OPÉRATIONS::getMontant).sum();
        double depenses = list.stream().filter(o -> o.getType() != null && o.getType() == OPÉRATIONS.TypeOperation.depense)
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
            .filter(op -> op.getDateOperation() != null)
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
                .filter(o -> o.getDateOperation() != null)
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

    private void updateGlobalStats() {
        try {
            Integer userId = UserSession.getUserId();
            if (userId == null) return;

            // 1. Documents par Entreprise
            List<tn.cashfly.entities.Entreprise> myEntreprises = entrepriseService.afficher().stream()
                    .filter(e -> e.getIdProprietaire() == userId)
                    .collect(Collectors.toList());

            totalEntreprisesLabel.setText(String.valueOf(myEntreprises.size()));

            docsBarChart.getData().clear();
            XYChart.Series<String, Number> docSeries = new XYChart.Series<>();
            docSeries.setName("Documents");

            int totalDocs = 0;
            for (tn.cashfly.entities.Entreprise e : myEntreprises) {
                int count = documentService.getByEntreprise(e.getIdEntreprise()).size();
                totalDocs += count;
                docSeries.getData().add(new XYChart.Data<>(e.getNom(), count));
            }
            docsBarChart.getData().add(docSeries);
            totalDocumentsLabel.setText(String.valueOf(totalDocs));

            // 2. Répartition par Secteur
            Map<String, Long> sectors = myEntreprises.stream()
                    .collect(Collectors.groupingBy(
                            e -> e.getSecteur() == null || e.getSecteur().isBlank() ? "Autre" : e.getSecteur(),
                            Collectors.counting()
                    ));

            ObservableList<PieChart.Data> sectorData = FXCollections.observableArrayList();
            sectors.forEach((s, count) -> sectorData.add(new PieChart.Data(s, count)));
            sectorPieChart.setData(sectorData);

        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    private void clearCharts() {
        pieChart.getData().clear();
        barChart.getData().clear();
        lineChart.getData().clear();
        docsBarChart.getData().clear();
        sectorPieChart.getData().clear();
        revenusLabel.setText("0 TND");
        depensesLabel.setText("0 TND");
        soldeLabel.setText("0 TND");
        predictionLabel.setText("Aucune donnée.");
    }
}
