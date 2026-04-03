package tn.cashfly.controllers;

import javafx.fxml.FXML;
import javafx.scene.canvas.Canvas;
import javafx.scene.canvas.GraphicsContext;
import javafx.scene.control.Label;
import javafx.scene.paint.Color;
import javafx.scene.shape.ArcType;
import tn.cashfly.services.UtilisateurCrud;
import tn.cashfly.entities.Utilisateur;

public class AdminStatsController {

    @FXML private Canvas canvasInvest, canvasProp;
    @FXML private Label lblInvest, lblProp;

    private final UtilisateurCrud crud = new UtilisateurCrud();

    @FXML
    public void initialize() {
        loadDonutStats();
    }

    private void loadDonutStats() {
        long invest = crud.afficher().stream().filter(Utilisateur::isInvestisseur).count();
        long prop = crud.afficher().stream().filter(Utilisateur::isProprietaire).count();

        long total = invest + prop;
        if (total == 0) total = 1;

        drawDonut(canvasInvest, (double) invest / total, "#6366f1");
        drawDonut(canvasProp, (double) prop / total, "#ef4444");

        lblInvest.setText(String.valueOf(invest));
        lblProp.setText(String.valueOf(prop));
    }

    private void drawDonut(Canvas canvas, double percent, String color) {
        GraphicsContext g = canvas.getGraphicsContext2D();
        g.clearRect(0, 0, canvas.getWidth(), canvas.getHeight());

        double cx = canvas.getWidth() / 2;
        double cy = canvas.getHeight() / 2;
        double r = Math.min(cx, cy) - 10;

        g.setLineWidth(15);
        g.setStroke(Color.web("#f1f5f9"));
        g.strokeOval(cx - r, cy - r, r * 2, r * 2);

        g.setStroke(Color.web(color));
        g.strokeArc(cx - r, cy - r, r * 2, r * 2, 90, -360 * percent, ArcType.OPEN);
    }
}
