package tn.cashfly.jpo.utils;

import com.google.zxing.BarcodeFormat;
import com.google.zxing.EncodeHintType;
import com.google.zxing.WriterException;
import com.google.zxing.common.BitMatrix;
import com.google.zxing.qrcode.QRCodeWriter;
import com.google.zxing.qrcode.decoder.ErrorCorrectionLevel;
import javafx.application.Platform;
import javafx.embed.swing.SwingFXUtils;
import javafx.scene.image.Image;
import javafx.scene.image.WritableImage;
import javafx.scene.canvas.Canvas;
import javafx.scene.canvas.GraphicsContext;
import javafx.scene.paint.Color;
import javafx.scene.text.Font;
import javafx.scene.text.FontWeight;
import tn.cashfly.jpo.entities.JPO;
import tn.cashfly.jpo.entities.Participation;
import tn.cashfly.jpo.entities.Utilisateur;

import javax.imageio.ImageIO;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.net.URI;
import java.net.URLEncoder;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.time.Instant;
import java.time.ZoneId;
import java.time.format.DateTimeFormatter;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.CountDownLatch;
import java.util.concurrent.atomic.AtomicReference;

public class BadgeGenerator {

    private static final int BADGE_WIDTH = 1024;
    private static final int BADGE_HEIGHT = 645;
    private static final int QR_SIZE = 400;

    private static final String GOQR_API_URL = "https://api.qrserver.com/v1/create-qr-code/";
    private static final HttpClient httpClient = HttpClient.newBuilder()
            .followRedirects(HttpClient.Redirect.NORMAL)
            .build();

    public static String generateBadge(Utilisateur user, JPO event, Participation participation)
            throws Exception {

        String qrFilePath = generateQRCodeLocal(user, event, participation, QR_SIZE);
        Canvas canvas = createCanvasOnFxThread(user, event, participation, qrFilePath);
        String badgePath = saveCanvasOnFxThread(canvas, participation.getIdParticipation());
        return badgePath;
    }

    public static String generateStandaloneQR(Utilisateur user, JPO event, Participation participation, int size)
            throws IOException, InterruptedException, WriterException {
        return generateQRCodeLocal(user, event, participation, size);
    }

    private static Canvas createCanvasOnFxThread(Utilisateur user, JPO event,
            Participation participation, String qrFilePath)
            throws Exception {

        if (Platform.isFxApplicationThread()) {
            Image qrImage = new Image(new File(qrFilePath).toURI().toString());
            return createBadgeCanvas(user, event, participation, qrImage);
        }

        AtomicReference<Canvas> canvasRef = new AtomicReference<>();
        AtomicReference<Exception> exceptionRef = new AtomicReference<>();
        CountDownLatch latch = new CountDownLatch(1);

        Platform.runLater(() -> {
            try {
                Image qrImage = new Image(new File(qrFilePath).toURI().toString());
                Canvas canvas = createBadgeCanvas(user, event, participation, qrImage);
                canvasRef.set(canvas);
            } catch (Exception e) {
                exceptionRef.set(e);
            } finally {
                latch.countDown();
            }
        });

        latch.await();

        if (exceptionRef.get() != null) {
            throw exceptionRef.get();
        }

        return canvasRef.get();
    }

    private static String saveCanvasOnFxThread(Canvas canvas, int participationId)
            throws Exception {

        if (Platform.isFxApplicationThread()) {
            return saveBadgeToFile(canvas, participationId);
        }

        AtomicReference<String> pathRef = new AtomicReference<>();
        AtomicReference<Exception> exceptionRef = new AtomicReference<>();
        CountDownLatch latch = new CountDownLatch(1);

        Platform.runLater(() -> {
            try {
                String path = saveBadgeToFile(canvas, participationId);
                pathRef.set(path);
            } catch (Exception e) {
                exceptionRef.set(e);
            } finally {
                latch.countDown();
            }
        });

        latch.await();

        if (exceptionRef.get() != null) {
            throw exceptionRef.get();
        }

        return pathRef.get();
    }

    private static String generateQRCodeViaApi(Utilisateur user, JPO event, Participation participation, int size)
            throws IOException, InterruptedException, WriterException {
        return generateQRCodeLocal(user, event, participation, size);
    }

    private static String generateQRCodeLocal(Utilisateur user, JPO event, Participation participation, int size)
            throws WriterException, IOException {

        String qrData = buildQRDataPayload(user, event, participation);

        Map<EncodeHintType, Object> hints = new HashMap<>();
        hints.put(EncodeHintType.ERROR_CORRECTION, ErrorCorrectionLevel.M);
        hints.put(EncodeHintType.MARGIN, 2);
        hints.put(EncodeHintType.CHARACTER_SET, "UTF-8");

        QRCodeWriter qrCodeWriter = new QRCodeWriter();
        BitMatrix bitMatrix = qrCodeWriter.encode(qrData, BarcodeFormat.QR_CODE, size, size, hints);

        WritableImage image = new WritableImage(size, size);
        for (int x = 0; x < size; x++) {
            for (int y = 0; y < size; y++) {
                image.getPixelWriter().setColor(x, y,
                        bitMatrix.get(x, y) ? Color.BLACK : Color.WHITE);
            }
        }

        String userHome = System.getProperty("user.home");
        Path qrDir = Paths.get(userHome, "CashFly", "QR_Codes");
        Files.createDirectories(qrDir);

        String filename = "qr_local_" + participation.getIdParticipation() + "_" +
                System.currentTimeMillis() + ".png";
        String filepath = qrDir.resolve(filename).toString();

        BufferedImage bufferedImage = SwingFXUtils.fromFXImage(image, null);
        ImageIO.write(bufferedImage, "png", new File(filepath));

        return filepath;
    }

    private static Canvas createBadgeCanvas(Utilisateur user, JPO event, Participation participation, Image qrImage) {
        Canvas canvas = new Canvas(BADGE_WIDTH, BADGE_HEIGHT);
        GraphicsContext gc = canvas.getGraphicsContext2D();

        gc.setFill(Color.web("#0D2440"));
        gc.fillRect(0, 0, BADGE_WIDTH, BADGE_HEIGHT);

        gc.setFill(Color.web("#2E5E99"));
        gc.fillRect(0, 0, BADGE_WIDTH, 20);

        gc.setFill(Color.web("#E7F0FA"));
        gc.setFont(Font.font("Segoe UI", FontWeight.BOLD, 48));
        gc.fillText(truncateText(event.getTitre(), 35), 50, 100);

        gc.setFill(Color.WHITE);
        gc.setFont(Font.font("Segoe UI", 28));
        String dateStr = formatEventDateSafe(event.getDate_evenement());
        gc.fillText("Date: " + dateStr, 50, 160);
        gc.fillText("Lieu: " + truncateText(event.getLieu(), 40), 50, 210);

        gc.setStroke(Color.web("#7BA4D0"));
        gc.setLineWidth(2);
        gc.strokeLine(50, 250, 580, 250);

        gc.setFill(Color.web("#7BA4D0"));
        gc.setFont(Font.font("Segoe UI", FontWeight.NORMAL, 32));
        gc.fillText("PARTICIPANT", 50, 310);

        gc.setFill(Color.WHITE);
        gc.setFont(Font.font("Segoe UI", FontWeight.BOLD, 42));
        gc.fillText(truncateText(user.getNomComplet(), 30), 50, 370);

        gc.setFill(Color.web("#E7F0FA"));
        gc.setFont(Font.font("Segoe UI", 24));
        gc.fillText(truncateText(user.getEmail(), 40), 50, 410);

        String statusText = participation.getStatut().toUpperCase();
        Color statusColor = switch (participation.getStatut().toLowerCase()) {
            case "confirmé" -> Color.web("#28a745");
            case "en_attente" -> Color.web("#fd7e14");
            case "annulé" -> Color.web("#dc3545");
            default -> Color.web("#6c757d");
        };

        gc.setFill(statusColor);
        gc.fillRoundRect(50, 460, 240, 50, 25, 25);
        gc.setFill(Color.WHITE);
        gc.setFont(Font.font("Segoe UI", FontWeight.BOLD, 22));
        gc.fillText("STATUT : " + statusText, 70, 492);

        gc.drawImage(qrImage, 600, 160, 380, 380);

        gc.setFill(Color.web("#7BA4D0"));
        gc.setFont(Font.font("Segoe UI", FontWeight.NORMAL, 16));
        gc.fillText("Scanner pour validation sécurisée", 670, 570);

        return canvas;
    }

    private static String saveBadgeToFile(Canvas canvas, int participationId) throws IOException {
        WritableImage writableImage = new WritableImage(BADGE_WIDTH, BADGE_HEIGHT);
        canvas.snapshot(null, writableImage);

        String userHome = System.getProperty("user.home");
        Path badgeDir = Paths.get(userHome, "CashFly", "Badges");
        Files.createDirectories(badgeDir);

        String filename = "badge_" + participationId + "_" +
                System.currentTimeMillis() + ".png";
        String filepath = badgeDir.resolve(filename).toString();

        BufferedImage bufferedImage = SwingFXUtils.fromFXImage(writableImage, null);
        ImageIO.write(bufferedImage, "png", new File(filepath));

        return filepath;
    }

    private static String buildQRDataPayload(Utilisateur user, JPO event, Participation participation) {
        StringBuilder payload = new StringBuilder();
        payload.append("=== CASHFLY JPO TICKET ===\n");
        payload.append("Participant: ").append(user.getNomComplet()).append("\n");
        payload.append("Email: ").append(user.getEmail()).append("\n");
        payload.append("Status: ").append(participation.getStatut().toUpperCase()).append("\n");
        payload.append("---\n");
        payload.append("Event: ").append(event.getTitre()).append("\n");
        payload.append("Date: ").append(formatEventDateSafe(event.getDate_evenement())).append("\n");
        payload.append("Location: ").append(event.getLieu()).append("\n");
        payload.append("---\n");
        payload.append("Verified: YES\n");
        payload.append("CashFly - JPO System");
        return payload.toString();
    }

    private static String formatEventDateSafe(Date date) {
        if (date == null) {
            return "N/A";
        }
        try {
            long epochMillis = date.getTime();
            return Instant.ofEpochMilli(epochMillis)
                    .atZone(ZoneId.systemDefault())
                    .format(DateTimeFormatter.ofPattern("dd MMMM yyyy HH:mm"));
        } catch (Exception e) {
            return date.toString();
        }
    }

    private static String truncateText(String text, int maxLength) {
        if (text == null)
            return "";
        if (text.length() <= maxLength)
            return text;
        return text.substring(0, maxLength - 3) + "...";
    }
}
