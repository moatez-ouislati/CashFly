package tn.cashfly.controllers;

import tn.cashfly.entities.Document;
import tn.cashfly.entities.Entreprise;
import tn.cashfly.services.DocumentService;
import tn.cashfly.services.EntrepriseService;

import javafx.animation.FadeTransition;
import javafx.collections.FXCollections;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.chart.*;
import javafx.scene.control.Button;
import javafx.scene.control.ComboBox;
import javafx.scene.layout.StackPane;
import javafx.stage.Stage;
import javafx.util.Duration;
import javafx.application.Platform;

import java.io.IOException;
import java.net.URL;
import java.sql.SQLException;
import java.util.*;
import java.util.stream.Collectors;

public class MainController implements Initializable {

    @FXML private StackPane chartHolder;
    @FXML private ComboBox<String> statutFilter;
    @FXML private Button switchBtn;

    private final DocumentService documentService = new DocumentService();
    private final EntrepriseService entrepriseService = new EntrepriseService();

    private boolean barMode = true;

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {

        statutFilter.setItems(FXCollections.observableArrayList(
                "ALL", "EN_ATTENTE", "VALIDE", "REJETE"
        ));

        statutFilter.setValue("ALL");

        statutFilter.setOnAction(e -> loadChart());

        switchBtn.setOnAction(e -> {
            barMode = !barMode;
            loadChart();
        });

        loadChart();
    }

    private void loadChart() {

        chartHolder.getChildren().clear();

        try {

            List<Document> documents = documentService.afficher();
            List<Entreprise> entreprises = entrepriseService.afficher();

            if (!statutFilter.getValue().equals("ALL")) {
                documents = documents.stream()
                        .filter(d -> d.getStatut().name()
                                .equals(statutFilter.getValue()))
                        .toList();
            }

            Map<Integer, Long> grouped =
                    documents.stream()
                            .collect(Collectors.groupingBy(
                                    Document::getIdEntreprise,
                                    Collectors.counting()
                            ));

            Map<String, Long> nameMap = new HashMap<>();

            for (Entreprise e : entreprises) {
                long count = grouped.getOrDefault(e.getIdEntreprise(), 0L);
                nameMap.put(e.getNom(), count);
            }

            if (barMode) {

                CategoryAxis xAxis = new CategoryAxis();
                NumberAxis yAxis = new NumberAxis();

                xAxis.setLabel("Entreprise");
                yAxis.setLabel("Nombre de Documents");

                BarChart<String, Number> chart =
                        new BarChart<>(xAxis, yAxis);

                chart.setTitle("Documents par Entreprise");
                chart.setAnimated(true);

                XYChart.Series<String, Number> series =
                        new XYChart.Series<>();

                for (var entry : nameMap.entrySet()) {
                    series.getData().add(
                            new XYChart.Data<>(
                                    entry.getKey(),
                                    entry.getValue()
                            )
                    );
                }

                chart.getData().add(series);
                animate(chart);
                chartHolder.getChildren().add(chart);

            } else {

                PieChart pieChart = new PieChart();
                pieChart.setTitle("Répartition des Documents");

                for (var entry : nameMap.entrySet()) {
                    pieChart.getData().add(
                            new PieChart.Data(
                                    entry.getKey(),
                                    entry.getValue()
                            )
                    );
                }

                pieChart.setAnimated(true);
                animate(pieChart);
                chartHolder.getChildren().add(pieChart);
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private void animate(Node node) {
        FadeTransition ft = new FadeTransition(Duration.millis(600), node);
        ft.setFromValue(0);
        ft.setToValue(1);
        ft.play();
    }

    // ===================== NAVIGATION =====================

    @FXML
    private void goToEntreprises(ActionEvent event) {
        changeScreen(event,
                "/AjouterEntreprise.fxml",
                "Gestion des Entreprises");
    }

    @FXML
    private void goToDocuments(ActionEvent event) {
        changeScreen(event,
                "/AjouterDocument.fxml",
                "Gestion des Documents");
    }

    @FXML
    private void afficherEntreprises(ActionEvent event) {
        changeScreen(event,
                "/AfficherEntreprise.fxml",
                "Liste des Entreprises");
    }

    @FXML
    private void afficherDocuments(ActionEvent event) {
        changeScreen(event,
                "/AfficherDocument.fxml",
                "Liste des Documents");
    }

    @FXML
    private void openMap(ActionEvent event) {
        changeScreen(event,
                "/map.fxml",
                "Carte des Entreprises");
    }

    @FXML
    private void exitApp(ActionEvent event) {
        System.exit(0);
    }

    private void changeScreen(ActionEvent event,
                              String fxmlPath,
                              String title) {
        try {
            FXMLLoader loader =
                    new FXMLLoader(getClass().getResource(fxmlPath));
            Parent root = loader.load();
            Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
            stage.setTitle(title);
            stage.setScene(new Scene(root));
            stage.show();
            Object controller = loader.getController();
            if (controller instanceof MapController) {
                Platform.runLater(((MapController) controller)::forceRedraw);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
