package tn.cashfly.controllers;

import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.chart.*;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import tn.cashfly.entities.Investissement;
import tn.cashfly.entities.RendementInvestissement;
import tn.cashfly.services.*;
import com.itextpdf.text.*;
import com.itextpdf.text.pdf.PdfPTable;
import com.itextpdf.text.pdf.PdfWriter;
import java.io.FileOutputStream;
import java.io.File;

import java.math.BigDecimal;
import java.net.URL;
import java.util.List;
import java.util.ArrayList;
import java.util.ResourceBundle;
import java.util.concurrent.CompletableFuture;
import java.util.Map;
import java.util.LinkedHashMap;
import java.util.Comparator;
import javafx.scene.Node;

public class InvestorHomeController implements Initializable {

    @FXML private Label lblTotalInvested;
    @FXML private Label lblTotalGain;
    @FXML private Label lblNetResult;
    @FXML private Label lblActiveCount;
    @FXML private PieChart pieChart;
    @FXML private BarChart<String, Number> barChart;
    @FXML private AreaChart<String, Number> areaChart;
    @FXML private VBox aiRecommendationsBox;
    @FXML private VBox newsContainer;
    @FXML private Label aiLoadingLabel;
    @FXML private Label newsLoadingLabel;
    @FXML private Button btnPdf;
    @FXML private ComboBox<String> currencySelector;
    @FXML private Label lblDynamicRate;
    @FXML private Label lblSelectedCurrency;

    private ServiceInvest serviceInvest;
    private ServiceRendement serviceRendement;
    private AiRecommendationService aiService;
    private NewsService newsService;
    private BctExchangeRateService exchangeRateService;
    private List<AiRecommendationService.Recommendation> currentAiRecommendations;

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        serviceInvest = new ServiceInvest();
        serviceRendement = new ServiceRendement();
        aiService = new AiRecommendationService();
        newsService = new NewsService();
        exchangeRateService = new BctExchangeRateService();

        if (btnPdf != null) btnPdf.setDisable(true);
        loadData();
        loadAiRecommendations();
        loadNews();
        initCurrencySelector();
    }

    private void loadData() {
        try {
            List<Investissement> investments = serviceInvest.afficher();
            List<RendementInvestissement> rendements = serviceRendement.afficher();

            BigDecimal totalInvested = investments.stream()
                    .map(Investissement::getMontant)
                    .reduce(BigDecimal.ZERO, BigDecimal::add);
            
            BigDecimal totalGain = rendements.stream()
                    .map(RendementInvestissement::getGain)
                    .reduce(BigDecimal.ZERO, BigDecimal::add);
            
            BigDecimal totalPerte = rendements.stream()
                    .map(RendementInvestissement::getPerte)
                    .reduce(BigDecimal.ZERO, BigDecimal::add);
            
            BigDecimal net = totalGain.subtract(totalPerte);

            lblTotalInvested.setText(String.format("%,.0f TND", totalInvested.doubleValue()));
            lblTotalGain.setText(String.format("+%,.2f TND", totalGain.doubleValue()));
            lblNetResult.setText(String.format("%,.2f TND", net.doubleValue()));
            lblActiveCount.setText(String.valueOf(investments.size()));

            updateCharts(investments, rendements);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void updateCharts(List<Investissement> investments, List<RendementInvestissement> rendements) {
        // Pie Chart
        ObservableList<PieChart.Data> pieData = FXCollections.observableArrayList();
        Map<Integer, BigDecimal> byEntreprise = new LinkedHashMap<>();
        for (Investissement i : investments) {
            byEntreprise.merge(i.getIdEntreprise(), i.getMontant(), BigDecimal::add);
        }
        byEntreprise.forEach((id, amount) -> pieData.add(new PieChart.Data("Ent. #" + id, amount.doubleValue())));
        pieChart.setData(pieData);

        // Bar Chart (Simplified)
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        series.setName("Investissements");
        for (Investissement i : investments) {
            series.getData().add(new XYChart.Data<>("Inv #" + i.getIdInvestissement(), i.getMontant()));
        }
        barChart.getData().setAll(series);

        XYChart.Series<String, Number> gainSeries = new XYChart.Series<>();
        gainSeries.setName("Gains Cumulés");

        Map<String, Double> dailyGains = new LinkedHashMap<>();
        rendements.stream()
                .sorted(Comparator.comparing(RendementInvestissement::getDateCalcul))
                .forEach(r -> {
                    String label = r.getDateCalcul().toString();
                    dailyGains.merge(label, r.getGain().doubleValue(), Double::sum);
                });

        double cumulative = 0;
        for (Map.Entry<String, Double> entry : dailyGains.entrySet()) {
            cumulative += entry.getValue();
            gainSeries.getData().add(new XYChart.Data<>(entry.getKey(), cumulative));
        }

        areaChart.getData().setAll(gainSeries);

        if (areaChart.getXAxis() instanceof CategoryAxis) {
            CategoryAxis xAxis = (CategoryAxis) areaChart.getXAxis();
            xAxis.setTickLabelRotation(45);
        }
        areaChart.setCreateSymbols(true);
        areaChart.setLegendVisible(true);

        Platform.runLater(() -> {
            if (!areaChart.getData().isEmpty()) {
                XYChart.Series<String, Number> s = areaChart.getData().get(0);
                if (s.getNode() != null) {
                    Node areaFill = s.getNode().lookup(".chart-series-area-fill");
                    Node areaLine = s.getNode().lookup(".chart-series-area-line");
                    if (areaFill != null) {
                        areaFill.setStyle("-fx-fill: rgba(99,102,241,0.15);");
                    }
                    if (areaLine != null) {
                        areaLine.setStyle("-fx-stroke: #6366f1; -fx-stroke-width: 2px;");
                    }
                }
                for (XYChart.Data<String, Number> d : s.getData()) {
                    if (d.getNode() != null) {
                        d.getNode().setStyle(
                                "-fx-background-color: white, #6366f1;" +
                                "-fx-background-insets: 0, 2;" +
                                "-fx-background-radius: 6px;"
                        );
                    }
                }
            }
        });
    }

    private void loadAiRecommendations() {
        CompletableFuture.runAsync(() -> {
            try {
                // Get real data for AI
                List<Investissement> investments = serviceInvest.afficher();
                List<RendementInvestissement> rendements = serviceRendement.afficher();
                
                BigDecimal totalInvested = investments.stream().map(Investissement::getMontant).reduce(BigDecimal.ZERO, BigDecimal::add);
                BigDecimal totalGain = rendements.stream().map(RendementInvestissement::getGain).reduce(BigDecimal.ZERO, BigDecimal::add);
                BigDecimal totalPerte = rendements.stream().map(RendementInvestissement::getPerte).reduce(BigDecimal.ZERO, BigDecimal::add);
                
                currentAiRecommendations = aiService.generateRecommendations(
                        totalInvested, totalGain, totalPerte, investments.size(), 5.0);

                Platform.runLater(() -> {
                    aiLoadingLabel.setVisible(false);
                    aiRecommendationsBox.getChildren().clear();
                    if (btnPdf != null) btnPdf.setDisable(false);
                    if (currentAiRecommendations != null) {
                        for (AiRecommendationService.Recommendation rec : currentAiRecommendations) {
                            VBox item = new VBox(4);
                            item.getStyleClass().add("notification-item");
                            item.setPadding(new Insets(10));
                            
                            Label title = new Label(rec.title);
                            title.getStyleClass().add("notification-title");
                            title.setStyle("-fx-font-size: 13px;");
                            
                            Label detail = new Label(rec.detail);
                            detail.setWrapText(true);
                            detail.getStyleClass().add("notification-date");
                            detail.setStyle("-fx-font-size: 11px;");
                            
                            item.getChildren().addAll(title, detail);
                            aiRecommendationsBox.getChildren().add(item);
                        }
                    }
                });
            } catch (Exception e) {
                Platform.runLater(() -> aiLoadingLabel.setText("Erreur IA"));
            }
        });
    }

    private void loadNews() {
        CompletableFuture.runAsync(() -> {
            try {
                List<NewsService.NewsArticle> articles = newsService.fetchFinancialNews();
                Platform.runLater(() -> {
                    if (newsLoadingLabel != null) newsLoadingLabel.setVisible(false);
                    if (newsContainer != null) {
                        newsContainer.getChildren().clear();
                        for (NewsService.NewsArticle art : articles) {
                            VBox card = new VBox(8);
                            card.setPadding(new Insets(15));
                            card.getStyleClass().add("notification-item");
                            card.setCursor(javafx.scene.Cursor.HAND);
                            
                            HBox header = new HBox(10);
                            header.setAlignment(Pos.CENTER_LEFT);
                            
                            Label tag = new Label("FINANCE");
                            tag.setStyle("-fx-background-color: rgba(99, 102, 241, 0.2); -fx-text-fill: #818cf8; " +
                                       "-fx-font-size: 9px; -fx-font-weight: bold; -fx-padding: 2 6; -fx-background-radius: 4;");
                            
                            Region spacer = new Region();
                            HBox.setHgrow(spacer, Priority.ALWAYS);
                            
                            Label dateLabel = new Label(art.publishedAt != null ? art.publishedAt.split("T")[0] : "Récemment");
                            dateLabel.getStyleClass().add("notification-date");
                            
                            header.getChildren().addAll(tag, spacer, dateLabel);

                            Label titleLabel = new Label(art.title);
                            titleLabel.setWrapText(true);
                            titleLabel.getStyleClass().add("notification-title");
                            titleLabel.setStyle("-fx-font-size: 14px; -fx-line-spacing: 2;");
                            
                            HBox footer = new HBox(5);
                            footer.setAlignment(Pos.CENTER_LEFT);
                            
                            Label sourceLabel = new Label("via " + (art.source != null ? art.source : "Source Inconnue"));
                            sourceLabel.setStyle("-fx-text-fill: #94a3b8; -fx-font-size: 11px; -fx-font-style: italic;");
                            
                            Region fSpacer = new Region();
                            HBox.setHgrow(fSpacer, Priority.ALWAYS);
                            
                            Label readMoreLabel = new Label("Lire l'article →");
                            readMoreLabel.setStyle("-fx-text-fill: #6366f1; -fx-font-size: 11px; -fx-font-weight: bold;");
                            
                            footer.getChildren().addAll(sourceLabel, fSpacer, readMoreLabel);
                            
                            card.getChildren().addAll(header, titleLabel, footer);
                            
                            // Open URL on click
                            card.setOnMouseClicked(e -> {
                                try {
                                    if (java.awt.Desktop.isDesktopSupported() && art.url != null) {
                                        java.awt.Desktop.getDesktop().browse(new java.net.URI(art.url));
                                    }
                                } catch (Exception ex) { ex.printStackTrace(); }
                            });
                            
                            newsContainer.getChildren().add(card);
                        }
                    }
                });
            } catch (Exception e) {
                e.printStackTrace();
                Platform.runLater(() -> {
                    if (newsLoadingLabel != null) newsLoadingLabel.setText("Erreur actualités");
                });
            }
        });
    }

    private void initCurrencySelector() {
        CompletableFuture.runAsync(() -> {
            try {
                Map<String, BctExchangeRateService.ExchangeRate> rates = exchangeRateService.getTodayExchangeRates();
                List<String> codes = new ArrayList<>(rates.keySet());
                codes.sort(String::compareTo);

                Platform.runLater(() -> {
                    currencySelector.setItems(FXCollections.observableArrayList(codes));
                    if (!codes.isEmpty()) {
                        currencySelector.setValue("EUR");
                    }
                    currencySelector.setOnAction(e -> updateExchangeRate());
                    updateExchangeRate();
                });
            } catch (Exception e) {
                Platform.runLater(() -> {
                    currencySelector.setItems(FXCollections.observableArrayList("USD", "EUR", "GBP", "SAR"));
                    currencySelector.setValue("EUR");
                    currencySelector.setOnAction(ev -> updateExchangeRate());
                    updateExchangeRate();
                });
            }
        });
    }

    private void updateExchangeRate() {
        String currency = currencySelector.getValue();
        if (lblSelectedCurrency != null) lblSelectedCurrency.setText(currency);
        
        CompletableFuture.runAsync(() -> {
            try {
                Map<String, BctExchangeRateService.ExchangeRate> rates = exchangeRateService.getTodayExchangeRates();
                if (rates.containsKey(currency)) {
                    double rate = rates.get(currency).middleRate;
                    Platform.runLater(() -> {
                        if (lblDynamicRate != null) lblDynamicRate.setText(String.format("%.3f TND", rate));
                    });
                }
            } catch (Exception e) {
                Platform.runLater(() -> {
                    if (lblDynamicRate != null) lblDynamicRate.setText("Error");
                });
            }
        });
    }

    @FXML
    private void generateAiPdfReport() {
        try {
            String dest = "Rapport_Investisseur_" + System.currentTimeMillis() + ".pdf";
            Document document = new Document();
            PdfWriter.getInstance(document, new FileOutputStream(dest));
            document.open();

            // Font styles
            Font titleFont = FontFactory.getFont(FontFactory.HELVETICA_BOLD, 18, BaseColor.BLUE);
            Font headerFont = FontFactory.getFont(FontFactory.HELVETICA_BOLD, 12, BaseColor.BLACK);
            Font normalFont = FontFactory.getFont(FontFactory.HELVETICA, 10, BaseColor.BLACK);

            // Title
            Paragraph title = new Paragraph("Rapport d'Analyse Financière Cashfly", titleFont);
            title.setAlignment(Element.ALIGN_CENTER);
            title.setSpacingAfter(20);
            document.add(title);

            // Fetch data
            List<Investissement> investments = serviceInvest.afficher();
            List<RendementInvestissement> rendements = serviceRendement.afficher();

            // Summary Section
            document.add(new Paragraph("Résumé du Portfolio", headerFont));
            document.add(new Paragraph("Total Investi: " + lblTotalInvested.getText(), normalFont));
            document.add(new Paragraph("Gains Totaux: " + lblTotalGain.getText(), normalFont));
            document.add(new Paragraph("Résultat Net: " + lblNetResult.getText(), normalFont));
            document.add(new Paragraph("Investissements Actifs: " + lblActiveCount.getText(), normalFont));
            document.add(new Paragraph("\n"));

            // AI Recommendations Section
            if (currentAiRecommendations != null && !currentAiRecommendations.isEmpty()) {
                document.add(new Paragraph("Conseils Stratégiques IA", headerFont));
                com.itextpdf.text.List list = new com.itextpdf.text.List(com.itextpdf.text.List.UNORDERED);
                list.setListSymbol("• ");
                for (AiRecommendationService.Recommendation rec : currentAiRecommendations) {
                    ListItem item = new ListItem(rec.title + ": " + rec.detail, normalFont);
                    item.setSpacingAfter(5);
                    list.add(item);
                }
                document.add(list);
                document.add(new Paragraph("\n"));
            }

            // Investments Table
            document.add(new Paragraph("Liste des Investissements", headerFont));
            PdfPTable table = new PdfPTable(3);
            table.setWidthPercentage(100);
            table.setSpacingBefore(10);
            table.addCell("Description");
            table.addCell("Montant");
            table.addCell("Statut");

            for (Investissement inv : investments) {
                table.addCell(inv.getDescription());
                table.addCell(inv.getMontant().toString() + " TND");
                table.addCell(inv.getStatut());
            }
            document.add(table);

            document.close();
            
            // Show Success
            Alert alert = new Alert(Alert.AlertType.INFORMATION);
            alert.setTitle("Rapport PDF");
            alert.setHeaderText("Rapport généré avec succès");
            alert.setContentText("Le fichier a été enregistré sous : " + new File(dest).getAbsolutePath());
            alert.showAndWait();
            
            // Open file
            if (java.awt.Desktop.isDesktopSupported()) {
                java.awt.Desktop.getDesktop().open(new File(dest));
            }

        } catch (Exception e) {
            e.printStackTrace();
            Alert alert = new Alert(Alert.AlertType.ERROR);
            alert.setTitle("Erreur PDF");
            alert.setHeaderText("Échec de la génération");
            alert.setContentText(e.getMessage());
            alert.showAndWait();
        }
    }
}
