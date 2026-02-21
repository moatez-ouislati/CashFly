package tn.cashfly.utils;

import javafx.embed.swing.SwingFXUtils;
import javafx.scene.image.Image;
import javafx.scene.image.WritableImage;
import javafx.scene.canvas.Canvas;
import javafx.scene.canvas.GraphicsContext;
import javafx.scene.paint.Color;
import javafx.scene.text.Font;
import javafx.scene.text.FontWeight;
import com.google.zxing.BarcodeFormat;
import com.google.zxing.WriterException;
import com.google.zxing.common.BitMatrix;
import com.google.zxing.qrcode.QRCodeWriter;
import tn.cashfly.entities.JPO;
import tn.cashfly.entities.Utilisateur;

import javax.imageio.ImageIO;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.IOException;

public class BadgeGenerator {

    private static final int BADGE_WIDTH = 1024;  // 86mm at 300dpi
    private static final int BADGE_HEIGHT = 645;  // 54mm at 300dpi


    public static String generateBadge(Utilisateur user, JPO event, int idParticipation) throws IOException, WriterException {
        // Create canvas
        Canvas canvas = new Canvas(BADGE_WIDTH, BADGE_HEIGHT);
        GraphicsContext gc = canvas.getGraphicsContext2D();

        // Background gradient
        gc.setFill(Color.web("#1A1C2C"));
        gc.fillRect(0, 0, BADGE_WIDTH, BADGE_HEIGHT);

        // Decorative gradient bar at top
        gc.setFill(Color.web("#0083ff"));
        gc.fillRect(0, 0, BADGE_WIDTH, 20);

        // Event title
        gc.setFill(Color.web("#00fffd"));
        gc.setFont(Font.font("Arial", FontWeight.BOLD, 48));
        gc.fillText(event.getTitre(), 50, 100);

        // Event details
        gc.setFill(Color.WHITE);
        gc.setFont(Font.font("Arial", 28));
        gc.fillText("📅 " + event.getDate_evenement().toString(), 50, 160);
        gc.fillText("📍 " + event.getLieu(), 50, 210);

        // Separator line
        gc.setStroke(Color.web("#0083ff"));
        gc.setLineWidth(3);
        gc.strokeLine(50, 250, 600, 250);

        // Participant name
        gc.setFill(Color.web("#00fffd"));
        gc.setFont(Font.font("Arial", FontWeight.BOLD, 42));
        gc.fillText("PARTICIPANT", 50, 320);

        gc.setFill(Color.WHITE);
        gc.setFont(Font.font("Arial", 36));
        gc.fillText(user.getNomComplet(), 50, 380);
        gc.setFont(Font.font("Arial", 24));
        gc.fillText(user.getEmail(), 50, 430);

        // Generate QR Code
        Image qrImage = generateQRCode(idParticipation);
        gc.drawImage(qrImage, 700, 280, 280, 280);

        // QR label
        gc.setFill(Color.web("#00fffd"));
        gc.setFont(Font.font("Arial", 20));
        gc.fillText("Scan for verification", 720, 580);

        // Save to file
        WritableImage writableImage = new WritableImage(BADGE_WIDTH, BADGE_HEIGHT);
        canvas.snapshot(null, writableImage);

        String filename = "badge_" + idParticipation + "_" + System.currentTimeMillis() + ".png";
        String filepath = "src/main/resources/tn/cashfly/Badges/" + filename;

        File dir = new File("src/main/resources/tn/cashfly/Badges/");
        if (!dir.exists()) dir.mkdirs();

        BufferedImage bufferedImage = SwingFXUtils.fromFXImage(writableImage, null);
        ImageIO.write(bufferedImage, "png", new File(filepath));

        return "@Badges/" + filename;
    }

    private static Image generateQRCode(int idParticipation) throws WriterException {
        QRCodeWriter qrCodeWriter = new QRCodeWriter();
        BitMatrix bitMatrix = qrCodeWriter.encode(
                "CASHFLY-JPO-" + idParticipation,
                BarcodeFormat.QR_CODE,
                280, 280
        );

        WritableImage image = new WritableImage(280, 280);
        for (int x = 0; x < 280; x++) {
            for (int y = 0; y < 280; y++) {
                image.getPixelWriter().setColor(x, y,
                        bitMatrix.get(x, y) ? Color.BLACK : Color.WHITE);
            }
        }
        return image;
    }
}