

package com.example.gestion_entreprises.controllers;

import com.example.gestion_entreprises.entities.Entreprise;
import com.example.gestion_entreprises.services.EntrepriseService;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.canvas.Canvas;
import javafx.scene.canvas.GraphicsContext;
import javafx.scene.image.Image;
import javafx.scene.input.ScrollEvent;
import javafx.scene.paint.Color;
import javafx.stage.Stage;

import java.net.HttpURLConnection;
import java.net.URL;
import java.sql.SQLException;
import java.util.List;
import java.util.ResourceBundle;

public class MapController implements Initializable {

    @FXML
    private Canvas canvas;

    private GraphicsContext gc;

    private final EntrepriseService service = new EntrepriseService();

    private double centerLat = 36.8065;
    private double centerLon = 10.1815;
    private int zoom = 12;

    private double lastMouseX, lastMouseY;

    private static final int TILE_SIZE = 256;
    private static final String API_KEY = System.getenv("MAPBOX_API_KEY");
    private final java.util.Map<String, Image> tileCache = new java.util.HashMap<>();

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        System.setProperty("http.agent", "JavaFX-Mapbox-App");

        gc = canvas.getGraphicsContext2D();

        canvas.widthProperty().bind(
                ((javafx.scene.layout.AnchorPane) canvas.getParent()).widthProperty());

        canvas.heightProperty().bind(
                ((javafx.scene.layout.AnchorPane) canvas.getParent())
                        .heightProperty()
                        .subtract(40));

        canvas.setOnMousePressed(e -> {
            lastMouseX = e.getX();
            lastMouseY = e.getY();
        });

        canvas.setOnMouseDragged(e -> {
            double dx = e.getX() - lastMouseX;
            double dy = e.getY() - lastMouseY;

            moveMap(dx, dy);

            lastMouseX = e.getX();
            lastMouseY = e.getY();

            drawMap();
        });

        canvas.addEventHandler(ScrollEvent.SCROLL, e -> {
            if (e.getDeltaY() > 0 && zoom < 18) zoom++;
            if (e.getDeltaY() < 0 && zoom > 2) zoom--;
            drawMap();
        });

        drawMap();
    }

    private void moveMap(double dx, double dy) {

        double scale = TILE_SIZE * Math.pow(2, zoom);

        centerLon -= dx / scale * 360;

        double latRad = Math.toRadians(centerLat);
        double mercatorY = Math.log(Math.tan(Math.PI / 4 + latRad / 2));
        mercatorY += dy / scale * 2 * Math.PI;

        centerLat = Math.toDegrees(
                2 * Math.atan(Math.exp(mercatorY)) - Math.PI / 2
        );
    }

    private void drawMap() {

        gc.clearRect(0, 0, canvas.getWidth(), canvas.getHeight());

        double scale = Math.pow(2, zoom);

        double centerPixelX = (centerLon + 180) / 360 * TILE_SIZE * scale;
        double centerPixelY =
                (1 - Math.log(Math.tan(Math.toRadians(centerLat))
                        + 1 / Math.cos(Math.toRadians(centerLat))) / Math.PI)
                        / 2 * TILE_SIZE * scale;

        double startPixelX = centerPixelX - canvas.getWidth() / 2;
        double startPixelY = centerPixelY - canvas.getHeight() / 2;

        int startTileX = (int) Math.floor(startPixelX / TILE_SIZE);
        int startTileY = (int) Math.floor(startPixelY / TILE_SIZE);

        int endTileX = (int) Math.floor(
                (centerPixelX + canvas.getWidth() / 2) / TILE_SIZE);
        int endTileY = (int) Math.floor(
                (centerPixelY + canvas.getHeight() / 2) / TILE_SIZE);

        int maxTile = (int) Math.pow(2, zoom);

        for (int x = startTileX; x <= endTileX; x++) {
            for (int y = startTileY; y <= endTileY; y++) {

                if (y < 0 || y >= maxTile) continue;

                int wrappedX = ((x % maxTile) + maxTile) % maxTile;

                String key = zoom + "_" + wrappedX + "_" + y;

                Image tile;

                if (tileCache.containsKey(key)) {
                    tile = tileCache.get(key);
                } else {
                    String url = "https://api.mapbox.com/styles/v1/mapbox/streets-v12/tiles/256/"
                            + zoom + "/" + wrappedX + "/" + y
                            + "@2x?access_token=" + API_KEY;

                    tile = new Image(url, true);
                    tileCache.put(key, tile);
                }

                double drawX = x * TILE_SIZE - startPixelX;
                double drawY = y * TILE_SIZE - startPixelY;

                if (!tile.isError()) {
                    gc.drawImage(tile, drawX, drawY);
                }
            }
        }

        drawMarkers(centerPixelX, centerPixelY);
    }

    private void drawMarkers(double centerPixelX, double centerPixelY) {

        try {
            List<Entreprise> entreprises = service.afficher();

            for (Entreprise e : entreprises) {

                if (e.getLatitude() == 0 && e.getLongitude() == 0)
                    continue;

                double markerPixelX = lonToPixelX(e.getLongitude());
                double markerPixelY = latToPixelY(e.getLatitude());

                double x = canvas.getWidth() / 2
                        + (markerPixelX - centerPixelX);

                double y = canvas.getHeight() / 2
                        + (markerPixelY - centerPixelY);

                gc.setFill(Color.RED);
                gc.fillOval(x - 6, y - 6, 12, 12);
            }

        } catch (SQLException ignored) {}
    }

    private double lonToPixelX(double lon) {
        double scale = TILE_SIZE * Math.pow(2, zoom);
        return (lon + 180) / 360 * scale;
    }

    private double latToPixelY(double lat) {
        double latRad = Math.toRadians(lat);
        double scale = TILE_SIZE * Math.pow(2, zoom);
        return (1 - Math.log(Math.tan(latRad)
                + 1 / Math.cos(latRad)) / Math.PI)
                / 2 * scale;
    }

    @FXML
    private void retour(ActionEvent event) {
        try {
            FXMLLoader loader = new FXMLLoader(
                    getClass().getResource(
                            "/com/example/gestion_entreprises/MainMenu.fxml"));

            Parent root = loader.load();

            Stage stage = (Stage) ((Node) event.getSource())
                    .getScene()
                    .getWindow();

            stage.setScene(new Scene(root));
            stage.show();

        } catch (Exception ignored) {}
    }
}