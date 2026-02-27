package tn.cashfly.controllers;

import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.chart.*;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import javafx.stage.Stage;
import tn.cashfly.models.Investissement;
import tn.cashfly.models.RendementInvestissement;
import tn.cashfly.services.*;

import java.math.BigDecimal;

import java.net.URL;
import java.util.List;
import java.util.ResourceBundle;
import java.util.concurrent.CompletableFuture;

public class DashboardController implements Initializable {

    @FXML
    private ScrollPane dashboardView;
    @FXML
    private Parent investmentsView;
    @FXML
    private Parent rendementsView;
    @FXML
    private Parent utilisateursView;
    @FXML
    private Parent rapportsView;
    @FXML
    private Parent parametresView;
    @FXML
    private Parent notificationsView;

    // ─── Header ──────────────────────────────────────────────────────────────
    @FXML
    private Label pageTitle;
    @FXML
    private Label pageSubtitle;

    // ─── Dashboard KPI cards ─────────────────────────────────────────────────
    @FXML
    private Label lblTotalInvested;
    @FXML
    private Label lblTotalGain;
    @FXML
    private Label lblNetResult;
    @FXML
    private Label lblActiveCount;

    // ─── Charts ──────────────────────────────────────────────────────────────
    @FXML
    private PieChart pieChart;
    @FXML
    private BarChart<String, Number> barChart;
    @FXML
    private LineChart<String, Number> lineChart;

    // ─── Reports Charts ──────────────────────────────────────────────────────
    @FXML
    private PieChart pieChartReports;
    @FXML
    private BarChart<String, Number> barChartReports;
    @FXML
    private LineChart<String, Number> lineChartReports;

    // ─── AI / News area ──────────────────────────────────────────────────────
    @FXML
    private VBox aiRecommendationsBox;
    @FXML
    private VBox newsContainer;
    @FXML
    private Label aiLoadingLabel;
    @FXML
    private Label newsLoadingLabel;

    // ─── Services ────────────────────────────────────────────────────────────
    private final ServiceInvest serviceInvest = new ServiceInvest();
    private final ServiceRendement serviceRendement = new ServiceRendement();
    private final NewsService newsService = new NewsService();
    private final AiRecommendationService aiService = new AiRecommendationService();

    private ObservableList<Investissement> investmentList;
    private ObservableList<RendementInvestissement> rendementList;

    // ─── Init ─────────────────────────────────────────────────────────────────
    @Override
    public void initialize(URL location, ResourceBundle resources) {
        investmentList = FXCollections.observableArrayList();
        rendementList = FXCollections.observableArrayList();
        loadData();
        loadNewsAsync();
        loadAiRecommendationsAsync();
    }

    // ─── Data loading ─────────────────────────────────────────────────────────
    private void loadData() {
        try {
            investmentList.clear();
            rendementList.clear();
            investmentList.addAll(serviceInvest.getAll());
            rendementList.addAll(serviceRendement.getAll());
            updateKpiCards();
            updateCharts();
        } catch (Exception e) {
            System.err.println("Dashboard load error: " + e.getMessage());
        }
    }

    private void updateKpiCards() {
        BigDecimal totalInvested = investmentList.stream()
                .map(Investissement::getMontant)
                .reduce(BigDecimal.ZERO, BigDecimal::add);

        BigDecimal totalGain = rendementList.stream()
                .map(RendementInvestissement::getGain)
                .reduce(BigDecimal.ZERO, BigDecimal::add);
        BigDecimal totalPerte = rendementList.stream()
                .map(RendementInvestissement::getPerte)
                .reduce(BigDecimal.ZERO, BigDecimal::add);
        BigDecimal net = totalGain.subtract(totalPerte);
        long activeCount = investmentList.stream()
                .filter(i -> i.getStatut() != null &&
                        (i.getStatut().equalsIgnoreCase("actif") ||
                                i.getStatut().equalsIgnoreCase("en cours")))
                .count();

        if (lblTotalInvested != null)
            lblTotalInvested.setText(String.format("%,.0f TND", totalInvested.doubleValue()));
        if (lblTotalGain != null)
            lblTotalGain.setText(String.format("+%,.2f TND", totalGain.doubleValue()));
        if (lblNetResult != null) {
            lblNetResult.setText(String.format("%,.2f TND", net.doubleValue()));
            lblNetResult.setStyle(net.compareTo(BigDecimal.ZERO) >= 0
                    ? "-fx-text-fill: #2ecc71; -fx-font-size: 24px; -fx-font-weight: bold;"
                    : "-fx-text-fill: #e74c3c; -fx-font-size: 24px; -fx-font-weight: bold;");
        }
        if (lblActiveCount != null)
            lblActiveCount.setText(String.valueOf(activeCount));
    }

    // ─── Charts ───────────────────────────────────────────────────────────────
    private void updateCharts() {
        // Dashboard home: per-record detail
        updatePieChartByEnterprise(pieChart);
        updateBarChartPerRecord(barChart);
        updateLineChartPerRecord(lineChart);

        // Reports: clean semi-annual (S1 Jan-Jun / S2 Jul-Dec) grouping
        updatePieChartByEnterprise(pieChartReports);
        updateBarChartSemiAnnual(barChartReports);
        updateLineChartSemiAnnual(lineChartReports);
    }

    /** Pie chart — portfolio split by enterprise. */
    private void updatePieChartByEnterprise(PieChart chart) {
        if (chart == null)
            return;
        java.util.LinkedHashMap<String, Double> map = new java.util.LinkedHashMap<>();
        for (Investissement i : investmentList) {
            String label = "Ent. #" + i.getIdEntreprise();
            map.merge(label, i.getMontant().doubleValue(), Double::sum);
        }
        ObservableList<PieChart.Data> data = FXCollections.observableArrayList();
        if (map.isEmpty()) {
            data.add(new PieChart.Data("Aucune donnée", 1));
        } else {
            map.forEach((k, v) -> data.add(new PieChart.Data(
                    k + " (" + String.format("%,.0f TND", v) + ")", v)));
        }
        chart.setData(data);
        chart.setLegendVisible(true);
        chart.setLabelsVisible(true);
    }

    /** Dashboard home bar — one bar per investment record. */
    private void updateBarChartPerRecord(BarChart<String, Number> chart) {
        if (chart == null || investmentList.isEmpty())
            return;
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        series.setName("Montant (TND)");
        for (Investissement i : investmentList) {
            series.getData().add(new XYChart.Data<>(
                    i.getDateInvestissement().toString(), i.getMontant()));
        }
        chart.getData().clear();
        chart.getData().add(series);
    }

    /** Dashboard home line — cumulative gain per rendement record. */
    private void updateLineChartPerRecord(LineChart<String, Number> chart) {
        if (chart == null || rendementList.isEmpty())
            return;
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        series.setName("Gains Cumulés (TND)");
        BigDecimal cumulative = BigDecimal.ZERO;
        for (RendementInvestissement r : rendementList) {
            cumulative = cumulative.add(r.getGain());
            series.getData().add(new XYChart.Data<>(
                    r.getDateCalcul().toString(), cumulative));
        }
        chart.getData().clear();
        chart.getData().add(series);
    }

    /** Reports bar — capital invested grouped by semester (S1 / S2). */
    private void updateBarChartSemiAnnual(BarChart<String, Number> chart) {
        if (chart == null)
            return;
        java.util.TreeMap<String, Double> semMap = new java.util.TreeMap<>();
        for (Investissement i : investmentList) {
            java.util.Calendar cal = java.util.Calendar.getInstance();
            cal.setTime(i.getDateInvestissement());
            int year = cal.get(java.util.Calendar.YEAR);
            int month = cal.get(java.util.Calendar.MONTH); // 0-based
            String key = year + (month < 6 ? " — S1" : " — S2");
            semMap.merge(key, i.getMontant().doubleValue(), Double::sum);
        }
        // Ensure at least the current year has both buckets
        if (semMap.isEmpty()) {
            int y = java.util.Calendar.getInstance().get(java.util.Calendar.YEAR);
            semMap.put(y + " — S1", 0.0);
            semMap.put(y + " — S2", 0.0);
        }
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        series.setName("Capital Investi (TND)");
        semMap.forEach((k, v) -> series.getData().add(new XYChart.Data<>(k, v)));
        chart.getData().clear();
        chart.setAnimated(false);
        chart.getData().add(series);
    }

    /** Reports line — gain, perte and net per semester with 3 series. */
    private void updateLineChartSemiAnnual(LineChart<String, Number> chart) {
        if (chart == null)
            return;
        // [0] = gain, [1] = perte per semester key
        java.util.TreeMap<String, double[]> semMap = new java.util.TreeMap<>();
        for (RendementInvestissement r : rendementList) {
            java.util.Calendar cal = java.util.Calendar.getInstance();
            cal.setTime(r.getDateCalcul());
            int year = cal.get(java.util.Calendar.YEAR);
            int month = cal.get(java.util.Calendar.MONTH);
            String key = year + (month < 6 ? " — S1" : " — S2");
            semMap.computeIfAbsent(key, k2 -> new double[] { 0.0, 0.0 });
            semMap.get(key)[0] += r.getGain().doubleValue();
            semMap.get(key)[1] += r.getPerte().doubleValue();
        }
        if (semMap.isEmpty()) {
            int y = java.util.Calendar.getInstance().get(java.util.Calendar.YEAR);
            semMap.put(y + " — S1", new double[] { 0.0, 0.0 });
            semMap.put(y + " — S2", new double[] { 0.0, 0.0 });
        }
        XYChart.Series<String, Number> gainSeries = new XYChart.Series<>();
        gainSeries.setName("Gains (TND)");
        XYChart.Series<String, Number> perteSeries = new XYChart.Series<>();
        perteSeries.setName("Pertes (TND)");
        XYChart.Series<String, Number> netSeries = new XYChart.Series<>();
        netSeries.setName("Net (TND)");
        semMap.forEach((k, v) -> {
            gainSeries.getData().add(new XYChart.Data<>(k, v[0]));
            perteSeries.getData().add(new XYChart.Data<>(k, v[1]));
            netSeries.getData().add(new XYChart.Data<>(k, v[0] - v[1]));
        });
        chart.getData().clear();
        chart.setAnimated(false);
        chart.getData().addAll(gainSeries, perteSeries, netSeries);
    }

    // ─── News (async) ─────────────────────────────────────────────────────────
    private void loadNewsAsync() {
        if (newsContainer == null)
            return;
        if (newsLoadingLabel != null)
            newsLoadingLabel.setVisible(true);

        CompletableFuture.supplyAsync(() -> newsService.fetchFinancialNews())
                .thenAccept(articles -> Platform.runLater(() -> {
                    if (newsLoadingLabel != null)
                        newsLoadingLabel.setVisible(false);
                    renderNews(articles);
                }))
                .exceptionally(ex -> {
                    Platform.runLater(() -> {
                        if (newsLoadingLabel != null)
                            newsLoadingLabel.setText("Actualités non disponibles.");
                        renderNews(newsService.getFallbackNews());
                    });
                    return null;
                });
    }

    private void renderNews(List<NewsService.NewsArticle> articles) {
        if (newsContainer == null)
            return;
        newsContainer.getChildren().clear();

        for (NewsService.NewsArticle article : articles) {
            VBox card = createNewsCard(article);
            newsContainer.getChildren().add(card);
        }
    }

    private VBox createNewsCard(NewsService.NewsArticle article) {
        VBox card = new VBox(6);
        card.setStyle("-fx-background-color: #1e2130; -fx-background-radius: 8; " +
                "-fx-padding: 12; -fx-cursor: hand;");
        card.setPadding(new Insets(12));

        HBox header = new HBox(8);
        header.setAlignment(Pos.CENTER_LEFT);
        Label sourceBadge = new Label(article.source != null && !article.source.isEmpty()
                ? article.source
                : "Actualités");
        sourceBadge.setStyle("-fx-background-color: #5a5ce533; -fx-text-fill: #5a5ce5; " +
                "-fx-background-radius: 10; -fx-padding: 2 8; -fx-font-size: 10px; -fx-font-weight: bold;");
        Region sp = new Region();
        HBox.setHgrow(sp, Priority.ALWAYS);
        Label timeLabel = new Label(article.publishedAt != null ? article.publishedAt : "Récent");
        timeLabel.setStyle("-fx-text-fill: #6c757d; -fx-font-size: 10px;");
        header.getChildren().addAll(sourceBadge, sp, timeLabel);

        Label title = new Label(article.title);
        title.setWrapText(true);
        title.setStyle("-fx-text-fill: #ecf0f1; -fx-font-size: 12px; -fx-font-weight: bold;");

        if (article.description != null && !article.description.isEmpty()) {
            Label desc = new Label(article.description);
            desc.setWrapText(true);
            desc.setStyle("-fx-text-fill: #95a5a6; -fx-font-size: 11px;");
            card.getChildren().addAll(header, title, desc);
        } else {
            card.getChildren().addAll(header, title);
        }

        card.setOnMouseEntered(e -> card.setStyle("-fx-background-color: #252840; -fx-background-radius: 8; " +
                "-fx-padding: 12; -fx-cursor: hand;"));
        card.setOnMouseExited(e -> card.setStyle("-fx-background-color: #1e2130; -fx-background-radius: 8; " +
                "-fx-padding: 12; -fx-cursor: hand;"));

        return card;
    }

    // ─── AI Recommendations (async) ───────────────────────────────────────────
    private void loadAiRecommendationsAsync() {
        if (aiRecommendationsBox == null)
            return;
        if (aiLoadingLabel != null) {
            aiLoadingLabel.setText("⏳ Génération des recommandations IA...");
            aiLoadingLabel.setVisible(true);
        }

        CompletableFuture.supplyAsync(() -> {
            // Re-load data for AI analysis
            try {
                List<Investissement> invs = serviceInvest.getAll();
                List<RendementInvestissement> rdts = serviceRendement.getAll();

                BigDecimal totalInvested = invs.stream().map(Investissement::getMontant)
                        .reduce(BigDecimal.ZERO, BigDecimal::add);
                BigDecimal totalGain = rdts.stream().map(RendementInvestissement::getGain)
                        .reduce(BigDecimal.ZERO, BigDecimal::add);
                BigDecimal totalPerte = rdts.stream().map(RendementInvestissement::getPerte)
                        .reduce(BigDecimal.ZERO, BigDecimal::add);
                double avgTaux = invs.stream()
                        .mapToDouble(i -> i.getTauxRendementPrevu().doubleValue())
                        .average().orElse(0);

                return aiService.generateRecommendations(
                        totalInvested, totalGain, totalPerte, invs.size(), avgTaux);
            } catch (Exception e) {
                return aiService.getRuleBasedRecommendations(
                        BigDecimal.ZERO, BigDecimal.ZERO, BigDecimal.ZERO, 0, 0);
            }
        }).thenAccept(recs -> Platform.runLater(() -> {
            if (aiLoadingLabel != null)
                aiLoadingLabel.setVisible(false);
            renderAiRecommendations(recs);
        })).exceptionally(ex -> {
            Platform.runLater(() -> {
                if (aiLoadingLabel != null)
                    aiLoadingLabel.setText("IA non disponible.");
            });
            return null;
        });
    }

    private void renderAiRecommendations(List<AiRecommendationService.Recommendation> recs) {
        if (aiRecommendationsBox == null)
            return;
        aiRecommendationsBox.getChildren().clear();

        for (AiRecommendationService.Recommendation rec : recs) {
            VBox card = createAiCard(rec);
            aiRecommendationsBox.getChildren().add(card);
        }
    }

    private VBox createAiCard(AiRecommendationService.Recommendation rec) {
        VBox card = new VBox(6);
        card.setPadding(new Insets(12));

        String borderColor = switch (rec.type) {
            case "success" -> "#2ecc71";
            case "warning" -> "#f39c12";
            default -> "#3498db";
        };
        card.setStyle("-fx-background-color: " + borderColor + "15; " +
                "-fx-background-radius: 8; -fx-border-color: " + borderColor + "44; " +
                "-fx-border-radius: 8; -fx-border-width: 1; -fx-padding: 12;");

        Label title = new Label(rec.title);
        title.setStyle("-fx-font-size: 12px; -fx-font-weight: bold; -fx-text-fill: " + borderColor + ";");

        Label detail = new Label(rec.detail);
        detail.setWrapText(true);
        detail.setStyle("-fx-font-size: 11px; -fx-text-fill: #bdc3c7; -fx-line-spacing: 2;");

        card.getChildren().addAll(title, detail);
        return card;
    }

    // ─── Navigation ───────────────────────────────────────────────────────────
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

    @FXML
    public void showDashboard() {
        hideAllViews();
        show(dashboardView, "Console de Pilotage", "Analyse en temps réel de votre écosystème financier.");
    }

    @FXML
    public void showInvestments() {
        hideAllViews();
        show(investmentsView, "Investissements", "Gérez vos investissements en vue cartes.");
    }

    @FXML
    public void showRendements() {
        hideAllViews();
        show(rendementsView, "Rendements", "Analyse des performances et KPI.");
    }

    @FXML
    public void showUtilisateurs() {
        hideAllViews();
        show(utilisateursView, "Utilisateurs", "Gestion des investisseurs et entreprises.");
    }

    @FXML
    public void showRapports() {
        hideAllViews();
        show(rapportsView, "Rapports & Analytics", "Statistiques globales et comparaisons.");
    }

    @FXML
    public void showParametres() {
        hideAllViews();
        show(parametresView, "Paramètres", "Configuration de l'application.");
    }

    @FXML
    public void showNotifications() {
        hideAllViews();
        show(notificationsView, "Notifications", "Centre d'alertes et messages.");
    }

    private void show(Node view, String title, String subtitle) {
        if (view != null)
            view.setVisible(true);
        if (pageTitle != null)
            pageTitle.setText(title);
        if (pageSubtitle != null)
            pageSubtitle.setText(subtitle);
    }

    // ─── Logout ───────────────────────────────────────────────────────────────
    @FXML
    private void handleLogout(ActionEvent event) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/fxml/login.fxml"));
            Parent root = loader.load();
            Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
            stage.setScene(new Scene(root));
            stage.centerOnScreen();
            stage.show();
        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Impossible de se déconnecter: " + e.getMessage());
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
