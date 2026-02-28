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
import java.time.OffsetDateTime;
import java.time.Duration;
import java.io.File;
import java.io.FileOutputStream;
import java.time.format.DateTimeFormatter;
import java.util.LinkedHashMap;
import java.util.Locale;
import java.util.Map;
import javafx.stage.FileChooser;
import com.itextpdf.text.Document;
import com.itextpdf.text.Paragraph;
import com.itextpdf.text.pdf.PdfWriter;

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
    private AreaChart<String, Number> areaChart;

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
    @FXML
    private ComboBox<String> currencySelector;
    @FXML
    private Label lblDynamicRate;
    @FXML
    private HBox exchangeRateBadge;

    private Map<String, BctExchangeRateService.ExchangeRate> cachedRates;

    // ─── Services ────────────────────────────────────────────────────────────
    private final ServiceInvest serviceInvest = new ServiceInvest();
    private final ServiceRendement serviceRendement = new ServiceRendement();
    private final NewsService newsService = new NewsService();
    private final AiRecommendationService aiService = new AiRecommendationService();
    private final BctExchangeRateService bctService = new BctExchangeRateService();

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
        loadExchangeRatesAsync();
    }

    // ─── Exchange Rates ───────────────────────────────────────────────────────
    private void loadExchangeRatesAsync() {
        CompletableFuture.runAsync(() -> {
            try {
                this.cachedRates = bctService.getTodayExchangeRates();

                Platform.runLater(() -> {
                    // Populate Selector
                    if (currencySelector != null) {
                        List<String> codes = cachedRates.keySet().stream().sorted().toList();
                        currencySelector.setItems(FXCollections.observableArrayList(codes));

                        // Default selection to EUR since it's most common for TND investors
                        if (codes.contains("EUR"))
                            currencySelector.setValue("EUR");
                        else if (!codes.isEmpty())
                            currencySelector.setValue(codes.get(0));

                        updateDynamicRate();

                        // Add listener
                        currencySelector.setOnAction(e -> updateDynamicRate());
                    }
                });
            } catch (Exception e) {
                Platform.runLater(() -> {
                    if (lblDynamicRate != null)
                        lblDynamicRate.setText("Service Indisponible");
                });
                System.err.println("Failed to load BCT rates: " + e.getMessage());
            }
        });
    }

    private void updateDynamicRate() {
        String selected = currencySelector.getValue();
        if (selected != null && cachedRates != null && cachedRates.containsKey(selected)) {
            double rate = cachedRates.get(selected).middleRate;
            lblDynamicRate.setText(String.format("%.3f TND", rate));
        }
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
        updateAreaChartPerRecord(areaChart);

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

    /** Dashboard home bar — aggregated grouped natively by month. */
    private void updateBarChartPerRecord(BarChart<String, Number> chart) {
        if (chart == null || investmentList.isEmpty())
            return;
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        series.setName("Montant (TND)");

        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("MMM yy", Locale.FRENCH);
        Map<String, BigDecimal> monthlyAmounts = new LinkedHashMap<>();

        // Group investments by chronological month to eliminate bottom label overlap
        for (Investissement i : investmentList) {
            if (i.getDateInvestissement() != null) {
                String monthLabel = i.getDateInvestissement().toLocalDate().format(formatter);
                monthlyAmounts.merge(monthLabel, i.getMontant(), BigDecimal::add);
            }
        }

        for (Map.Entry<String, BigDecimal> entry : monthlyAmounts.entrySet()) {
            series.getData().add(new XYChart.Data<>(entry.getKey(), entry.getValue()));
        }

        chart.getData().clear();
        chart.getData().add(series);

        // Add tooltips to each bar node
        Platform.runLater(() -> {
            for (XYChart.Data<String, Number> data : series.getData()) {
                Node node = data.getNode();
                if (node != null) {
                    Tooltip tooltip = new Tooltip(
                            "Mois: " + data.getXValue() + "\n" +
                                    "Montant: " + String.format("%,.0f TND", data.getYValue().doubleValue()));
                    tooltip.setStyle(
                            "-fx-font-size: 13px; -fx-background-color: #2c3e50; -fx-text-fill: white; -fx-padding: 8px; -fx-opacity: 0.9; -fx-background-radius: 4px;");
                    Tooltip.install(node, tooltip);

                    node.setOnMouseEntered(e -> node.setStyle("-fx-bar-fill: #e6511a; -fx-cursor: hand;"));
                    node.setOnMouseExited(e -> node.setStyle(""));
                }
            }
        });
    }

    /** Dashboard home area — cumulative gain grouped strictly by month. */
    private void updateAreaChartPerRecord(AreaChart<String, Number> chart) {
        if (chart == null || rendementList.isEmpty())
            return;
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        series.setName("Gains Cumulés (TND)");

        BigDecimal cumulative = BigDecimal.ZERO;
        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("MMM yy", Locale.FRENCH);
        Map<String, BigDecimal> monthlyGains = new LinkedHashMap<>();

        // Group by chronological month
        for (RendementInvestissement r : rendementList) {
            cumulative = cumulative.add(r.getGain());
            if (r.getDateCalcul() != null) {
                String monthLabel = r.getDateCalcul().toLocalDate().format(formatter);
                monthlyGains.put(monthLabel, cumulative); // Replaces earlier records in the same month with latest
                // cumul
            }
        }

        for (Map.Entry<String, BigDecimal> entry : monthlyGains.entrySet()) {
            series.getData().add(new XYChart.Data<>(entry.getKey(), entry.getValue()));
        }

        chart.getData().clear();
        chart.getData().add(series);

        // Add Tooltips to each line chart plot-point
        Platform.runLater(() -> {
            for (XYChart.Data<String, Number> data : series.getData()) {
                Node node = data.getNode();
                if (node != null) {
                    Tooltip tooltip = new Tooltip(
                            "Mois: " + data.getXValue() + "\n" +
                                    "Cumul: " + String.format("%,.2f TND", data.getYValue().doubleValue()));
                    tooltip.setStyle(
                            "-fx-font-size: 13px; -fx-background-color: #2c3e50; -fx-text-fill: white; -fx-padding: 8px; -fx-opacity: 0.9; -fx-background-radius: 4px;");
                    Tooltip.install(node, tooltip);

                    // Add subtle hover effect size expansion to points
                    node.setOnMouseEntered(e -> {
                        node.setScaleX(1.5);
                        node.setScaleY(1.5);
                    });

                    node.setOnMouseExited(e -> {
                        node.setScaleX(1);
                        node.setScaleY(1);
                    });
                }
            }
        });
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
        Label timeLabel = new Label(formatTimeAgo(article.publishedAt));
        timeLabel.setStyle("-fx-text-fill: #7f8c8d; -fx-font-size: 10px; -fx-font-weight: bold;");
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

        card.setOnMouseClicked(e -> {
            if (article.url != null && !article.url.isEmpty()) {
                try {
                    String url = article.url.trim();
                    if (java.awt.Desktop.isDesktopSupported()) {
                        java.awt.Desktop.getDesktop().browse(new java.net.URI(url));
                    } else {
                        // Fallback for Windows
                        new ProcessBuilder("cmd", "/c", "start", url).start();
                    }
                } catch (Exception ex) {
                    System.err.println("Could not open news URL: " + ex.getMessage());
                }
            }
        });

        card.setOnMouseEntered(e -> card.setStyle("-fx-background-color: #2c2f48; -fx-background-radius: 8; " +
                "-fx-padding: 12; -fx-cursor: hand; -fx-border-color: #5a5ce5; -fx-border-width: 0.5; -fx-border-radius: 8;"));
        card.setOnMouseExited(e -> card.setStyle("-fx-background-color: #1e2130; -fx-background-radius: 8; " +
                "-fx-padding: 12; -fx-cursor: hand; -fx-border-color: transparent;"));

        return card;
    }

    private String formatTimeAgo(String isoDate) {
        if (isoDate == null || isoDate.isEmpty() || isoDate.equals("Récent") || isoDate.contains("Il y a")
                || isoDate.contains("Aujourd'hui") || isoDate.contains("Cette semaine")) {
            return isoDate;
        }
        try {
            OffsetDateTime published = OffsetDateTime.parse(isoDate);
            OffsetDateTime now = OffsetDateTime.now();
            Duration diff = Duration.between(published, now);

            long seconds = diff.getSeconds();
            if (seconds < 60)
                return "À l'instant";
            long minutes = diff.toMinutes();
            if (minutes < 60)
                return "Il y a " + minutes + "m";
            long hours = diff.toHours();
            if (hours < 24)
                return "Il y a " + hours + "h";
            long days = diff.toDays();
            if (days == 1)
                return "Hier";
            if (days < 7)
                return "Il y a " + days + "j";

            return published.format(DateTimeFormatter.ofPattern("dd MMM yyyy", Locale.FRENCH));
        } catch (Exception e) {
            return isoDate;
        }
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

        card.setStyle("-fx-background-color: #1e2130; -fx-background-radius: 8; -fx-padding: 12;");

        String text = rec.detail;
        if (rec.title != null && !rec.title.contains("IA") && !rec.title.contains("Information")
                && !rec.title.contains("Alerte")) {
            text = rec.title + " - " + text;
        }

        Label detail = new Label(text);
        detail.setWrapText(true);
        detail.setStyle("-fx-font-size: 11px; -fx-text-fill: #bdc3c7; -fx-line-spacing: 2;");

        card.getChildren().add(detail);
        return card;
    }

    // ─── PDF Generation ───────────────────────────────────────────────────────
    @FXML
    public void generateAiPdfReport(ActionEvent event) {
        if (aiRecommendationsBox == null || aiRecommendationsBox.getChildren().isEmpty()) {
            showAlert(Alert.AlertType.INFORMATION, "Information", "Aucune recommandation à exporter.");
            return;
        }

        FileChooser fileChooser = new FileChooser();
        fileChooser.setTitle("Enregistrer le rapport PDF");
        fileChooser.getExtensionFilters().add(new FileChooser.ExtensionFilter("Fichiers PDF", "*.pdf"));
        fileChooser.setInitialFileName("Rapport_CashFly_Recommandations.pdf");

        Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
        File file = fileChooser.showSaveDialog(stage);

        if (file != null) {
            try {
                Document document = new Document();
                PdfWriter.getInstance(document, new FileOutputStream(file));
                document.open();

                com.itextpdf.text.Font titleFont = new com.itextpdf.text.Font(
                        com.itextpdf.text.Font.FontFamily.HELVETICA, 18, com.itextpdf.text.Font.BOLD);
                com.itextpdf.text.Font subTitleFont = new com.itextpdf.text.Font(
                        com.itextpdf.text.Font.FontFamily.HELVETICA, 14, com.itextpdf.text.Font.BOLD);
                com.itextpdf.text.Font normalFont = new com.itextpdf.text.Font(
                        com.itextpdf.text.Font.FontFamily.HELVETICA, 12, com.itextpdf.text.Font.NORMAL);

                document.add(new Paragraph("Rapport CashFly - Recommandations IA", titleFont));
                document.add(new Paragraph("Date : " + java.time.LocalDate.now(), normalFont));
                document.add(new Paragraph("\n"));

                document.add(new Paragraph("Analyse du Portefeuille:", subTitleFont));
                document.add(new Paragraph("Capital Investi: " + lblTotalInvested.getText(), normalFont));
                document.add(new Paragraph("Gains Totaux: " + lblTotalGain.getText(), normalFont));
                document.add(new Paragraph("Résultat Net: " + lblNetResult.getText(), normalFont));
                document.add(new Paragraph("Investissements Actifs: " + lblActiveCount.getText(), normalFont));
                document.add(new Paragraph("\n"));

                document.add(new Paragraph("Conseils Strategiques (Fournis par l'IA):", subTitleFont));
                document.add(new Paragraph("\n"));

                for (Node node : aiRecommendationsBox.getChildren()) {
                    if (node instanceof VBox) {
                        VBox vBox = (VBox) node;
                        for (Node child : vBox.getChildren()) {
                            if (child instanceof Label) {
                                document.add(new Paragraph("• " + ((Label) child).getText(), normalFont));
                                document.add(new Paragraph("\n"));
                            }
                        }
                    }
                }

                document.close();
                showAlert(Alert.AlertType.INFORMATION, "Succes",
                        "Rapport PDF genere avec succes dans :\n" + file.getAbsolutePath());
            } catch (Exception e) {
                showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la generation du PDF: " + e.getMessage());
            }
        }
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

        // Toggle Exchange Rate Badge: Only on Dashboard
        if (exchangeRateBadge != null) {
            boolean isDashboard = (view == dashboardView);
            exchangeRateBadge.setVisible(isDashboard);
            exchangeRateBadge.setManaged(isDashboard);
        }
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
