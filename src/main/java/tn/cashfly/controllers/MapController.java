package tn.cashfly.controllers;

import io.github.cdimascio.dotenv.Dotenv;
import tn.cashfly.entities.Entreprise;
import tn.cashfly.services.EntrepriseService;
import javafx.application.Platform;
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
import javafx.scene.control.ProgressIndicator;
import javafx.stage.Stage;

import java.net.HttpURLConnection;
import java.net.URL;
import java.sql.SQLException;
import java.util.List;
import java.util.ResourceBundle;

public class MapController implements Initializable {

    @FXML
    private Canvas canvas;

    @FXML
    private ProgressIndicator loadingIndicator;

    private GraphicsContext gc;

    private final EntrepriseService service = new EntrepriseService();

    private double centerLat = 36.8065;
    private double centerLon = 10.1815;
    private int zoom = 12;

    private double lastMouseX, lastMouseY;

    private static final int TILE_SIZE = 256;
    private static final Dotenv dotenv = Dotenv.load();
    private static final String API_KEY = dotenv.get("MAPBOX_API_KEY");
    private final java.util.Map<String, Image> tileCache = new java.util.HashMap<>();
    private final java.util.Set<String> loadingTiles = new java.util.HashSet<>();
    private List<Entreprise> cachedEntreprises;

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        System.setProperty("http.agent", "JavaFX-Mapbox-App");

        gc = canvas.getGraphicsContext2D();

        if (loadingIndicator != null) {
            loadingIndicator.setVisible(true);
        }

        if (canvas.getParent() instanceof javafx.scene.layout.Region region) {
            canvas.widthProperty().bind(region.widthProperty());
            canvas.heightProperty().bind(region.heightProperty());
        }

        canvas.widthProperty().addListener((obs, old, val) -> drawMap());
        canvas.heightProperty().addListener((obs, old, val) -> drawMap());

        // Pre-load entreprises
        try {
            cachedEntreprises = service.afficher();
        } catch (SQLException e) {
            e.printStackTrace();
        }

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
        double centerX = lonToX(centerLon, zoom);
        double centerY = latToY(centerLat, zoom);

        double newX = centerX - dx / TILE_SIZE;
        double newY = centerY - dy / TILE_SIZE;

        centerLon = xToLon(newX, zoom);
        centerLat = yToLat(newY, zoom);
    }

    private double xToLon(double x, int z) {
        return x / Math.pow(2, z) * 360 - 180;
    }

    private double yToLat(double y, int z) {
        double n = Math.PI - 2.0 * Math.PI * y / Math.pow(2, z);
        return Math.toDegrees(Math.atan(Math.sinh(n)));
    }

    private void drawMap() {
        if (canvas.getWidth() <= 0 || canvas.getHeight() <= 0) return;
        
        // Use a background color to avoid white flashes
        gc.setFill(Color.web("#f1f5f9")); // Slate-100 (matches dashboard background)
        gc.fillRect(0, 0, canvas.getWidth(), canvas.getHeight());
        
        // Draw a subtle grid for the "loading" state
        gc.setStroke(Color.web("#e2e8f0"));
        gc.setLineWidth(0.5);
        for (int i = 0; i < canvas.getWidth(); i += 50) gc.strokeLine(i, 0, i, canvas.getHeight());
        for (int i = 0; i < canvas.getHeight(); i += 50) gc.strokeLine(0, i, canvas.getWidth(), i);

        gc.setImageSmoothing(true);

        double centerX = lonToX(centerLon, zoom);
        double centerY = latToY(centerLat, zoom);

        int numTilesX = (int) Math.ceil(canvas.getWidth() / TILE_SIZE) + 2;
        int numTilesY = (int) Math.ceil(canvas.getHeight() / TILE_SIZE) + 2;

        double startX = centerX - (canvas.getWidth() / 2) / TILE_SIZE;
        double startY = centerY - (canvas.getHeight() / 2) / TILE_SIZE;

        int startTileX = (int) Math.floor(startX);
        int startTileY = (int) Math.floor(startY);

        double offsetX = (startX - startTileX) * TILE_SIZE;
        double offsetY = (startY - startTileY) * TILE_SIZE;

        // Draw Tiles
        for (int x = 0; x < numTilesX; x++) {
            for (int y = 0; y < numTilesY; y++) {
                int tileX = startTileX + x;
                int tileY = startTileY + y;

                int maxTiles = (int) Math.pow(2, zoom);
                int wrappedX = (tileX % maxTiles + maxTiles) % maxTiles;

                if (tileY >= 0 && tileY < maxTiles) {
                    drawTile(wrappedX, tileY, x * TILE_SIZE - offsetX, y * TILE_SIZE - offsetY);
                }
            }
        }

        // Draw Markers ON TOP of everything
        drawMarkers(startX, startY);

        if (loadingIndicator != null && !tileCache.isEmpty()) {
            loadingIndicator.setVisible(false);
        }
    }

    private void drawTile(int x, int y, double dx, double dy) {
        String url = String.format("https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/%d/%d/%d?access_token=%s", zoom, x, y, API_KEY);

        if (tileCache.containsKey(url)) {
            gc.drawImage(tileCache.get(url), Math.floor(dx), Math.floor(dy), TILE_SIZE, TILE_SIZE);
        } else if (!loadingTiles.contains(url)) {
            loadingTiles.add(url);
            Image image = new Image(url, true);
            image.progressProperty().addListener((obs, old, nv) -> {
                if (nv.doubleValue() == 1.0) {
                    Platform.runLater(() -> {
                        tileCache.put(url, image);
                        loadingTiles.remove(url);
                        drawMap();
                    });
                }
            });
            image.errorProperty().addListener((obs, was, is) -> {
                if (is) {
                    Platform.runLater(() -> {
                        loadingTiles.remove(url);
                        if (loadingIndicator != null) {
                            loadingIndicator.setVisible(false);
                        }
                    });
                }
            });
        }
    }

    private void drawMarkers(double startX, double startY) {
        if (cachedEntreprises == null) return;
        
        for (Entreprise e : cachedEntreprises) {
            double x = (lonToX(e.getLongitude(), zoom) - startX) * TILE_SIZE;
            double y = (latToY(e.getLatitude(), zoom) - startY) * TILE_SIZE;

            if (x >= -50 && x <= canvas.getWidth() + 50 && y >= -50 && y <= canvas.getHeight() + 50) {
                gc.setFill(Color.web("#ef4444"));
                gc.setStroke(Color.WHITE);
                gc.setLineWidth(2);
                
                gc.fillOval(x - 8, y - 8, 16, 16);
                gc.strokeOval(x - 8, y - 8, 16, 16);
                
                gc.setFill(Color.web("#1e293b", 0.8));
                gc.fillRoundRect(x + 10, y - 12, e.getNom().length() * 8 + 10, 20, 10, 10);
                
                gc.setFill(Color.WHITE);
                gc.setFont(javafx.scene.text.Font.font("System", javafx.scene.text.FontWeight.BOLD, 12));
                gc.fillText(e.getNom(), x + 15, y + 2);
            }
        }
    }

    private double lonToX(double lon, int z) {
        return (lon + 180) / 360 * Math.pow(2, z);
    }

    private double latToY(double lat, int z) {
        double latRad = Math.toRadians(lat);
        return (1 - Math.log(Math.tan(latRad) + 1 / Math.cos(latRad)) / Math.PI) / 2 * Math.pow(2, z);
    }

    public void forceRedraw() {
        if (canvas != null && canvas.getParent() instanceof javafx.scene.layout.Region region) {
            double parentWidth = region.getWidth();
            double parentHeight = region.getHeight();
            if (canvas.getWidth() != parentWidth || canvas.getHeight() != parentHeight) {
                canvas.setWidth(parentWidth);
                canvas.setHeight(parentHeight);
            }
            drawMap();
        }
    }

    @FXML
    private void retour(ActionEvent event) {
        if (tn.cashfly.DashboardController.getInstance() != null) {
            tn.cashfly.DashboardController.getInstance().showEntreprises();
        } else if (tn.cashfly.controllers.InvestisseurDashboard.getInstance() != null) {
            tn.cashfly.controllers.InvestisseurDashboard.getInstance().showHome();
        }
    }
}
