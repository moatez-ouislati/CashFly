package tn.cashfly.controllers;

import tn.cashfly.services.DocumentService;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.chart.BarChart;
import javafx.scene.chart.XYChart;

import java.net.URL;
import java.sql.SQLException;
import java.util.List;
import java.util.ResourceBundle;

public class StatsController implements Initializable {

    @FXML
    private BarChart<String, Number> barChart;

    private final DocumentService documentService = new DocumentService();

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        loadData();
    }

    private void loadData() {

        XYChart.Series<String, Number> series =
                new XYChart.Series<>();

        series.setName("Documents par Entreprise");

        try {
            List<Object[]> data =
                    documentService.countDocumentsPerEntreprise();

            for (Object[] row : data) {
                String entreprise = (String) row[0];
                int total = (int) row[1];

                series.getData().add(
                        new XYChart.Data<>(entreprise, total)
                );
            }

            barChart.getData().clear();
            barChart.getData().add(series);

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
