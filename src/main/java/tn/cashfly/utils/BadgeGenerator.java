package tn.cashfly.utils;

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
import tn.cashfly.entities.JPO;
import tn.cashfly.entities.Participation;
import tn.cashfly.entities.Utilisateur;

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

/**
 * BadgeGenerator - Creates event badges with QR codes
 * Uses GoQR.me API for QR generation (free, no API key required)
 * Falls back to local ZXing if API fails
 */
public class BadgeGenerator {

    private static final int BADGE_WIDTH = 1024;
    private static final int BADGE_HEIGHT = 645;
    private static final int QR_SIZE = 400;

    private static final String GOQR_API_URL = "https://api.qrserver.com/v1/create-qr-code/";
    private static final HttpClient httpClient = HttpClient.newBuilder()
            .followRedirects(HttpClient.Redirect.NORMAL)
            .build();

    // ============================================
    // PUBLIC API METHODS
    // ============================================

    /**
     * Generates a complete badge with embedded QR code from API
     * This method can be called from any thread - it handles FX thread safety
     * internally
     */
    public static String generateBadge(Utilisateur user, JPO event, Participation participation)
            throws Exception {

        try {
            // Step 1: Generate QR code via API (can be done on background thread)
            String qrFilePath = generateQRCodeViaApi(user, event, participation, QR_SIZE);

            // Step 2: Create badge design (must be on FX thread)
            Canvas canvas = createCanvasOnFxThread(user, event, participation, qrFilePath);

            // Step 3: Snapshot and save (must be on FX thread)
            String badgePath = saveCanvasOnFxThread(canvas, participation.getIdParticipation());

            return badgePath;

        } catch (Exception e) {
            System.err.println("=== BadgeGenerator.generateBadge() FAILED ===");
            throw e;
        }
    }

    /**
     * Generates standalone QR code only (no badge design)
     * Can be called from any thread
     */
    public static String generateStandaloneQR(Utilisateur user, JPO event, Participation participation, int size)
            throws IOException, InterruptedException, WriterException {

        try {
            String qrPath = generateQRCodeViaApi(user, event, participation, size);
            return qrPath;
        } catch (Exception e) {
            System.err.println("=== BadgeGenerator.generateStandaloneQR() FAILED ===");
            throw e;
        }
    }

    // ============================================
    // FX THREAD SAFE METHODS
    // ============================================

    /**
     * Creates the badge canvas on FX thread using CountDownLatch for
     * synchronization
     */
    private static Canvas createCanvasOnFxThread(Utilisateur user, JPO event,
            Participation participation, String qrFilePath)
            throws Exception {

        AtomicReference<Canvas> canvasRef = new AtomicReference<>();
        AtomicReference<Exception> exceptionRef = new AtomicReference<>();
        CountDownLatch latch = new CountDownLatch(1);

        Platform.runLater(() -> {
            try {
                // Load QR image
                Image qrImage = new Image(new File(qrFilePath).toURI().toString());

                // Create badge design
                Canvas canvas = createBadgeCanvas(user, event, participation, qrImage);
                canvasRef.set(canvas);

            } catch (Exception e) {
                exceptionRef.set(e);
            } finally {
                latch.countDown();
            }
        });

        // Wait for FX thread to complete
        latch.await();

        if (exceptionRef.get() != null) {
            throw exceptionRef.get();
        }

        return canvasRef.get();
    }

    /**
     * Saves the canvas on FX thread using CountDownLatch for synchronization
     */
    private static String saveCanvasOnFxThread(Canvas canvas, int participationId)
            throws Exception {

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

        // Wait for FX thread to complete
        latch.await();

        if (exceptionRef.get() != null) {
            throw exceptionRef.get();
        }

        return pathRef.get();
    }

    // ============================================
    // QR CODE GENERATION (Background Thread Safe)
    // ============================================

    /**
     * Calls GoQR.me API to generate QR code
     */
    private static String generateQRCodeViaApi(Utilisateur user, JPO event, Participation participation, int size)
            throws IOException, InterruptedException, WriterException {

        try {
            String qrData = buildQRDataPayload(user, event, participation);

            String encodedData = URLEncoder.encode(qrData, StandardCharsets.UTF_8);

            String apiUrl = String.format("%s?size=%dx%d&color=0D2440&bgcolor=E7F0FA&margin=10&ecc=M&data=%s",
                    GOQR_API_URL, size, size, encodedData);

            if (apiUrl.length() > 2000) {
                return generateQRCodeLocal(user, event, participation, size);
            }

            HttpRequest request = HttpRequest.newBuilder()
                    .uri(URI.create(apiUrl))
                    .timeout(java.time.Duration.ofSeconds(15))
                    .GET()
                    .build();

            HttpResponse<byte[]> response = httpClient.send(request, HttpResponse.BodyHandlers.ofByteArray());

            if (response.statusCode() != 200) {
                String errorBody = new String(response.body(), StandardCharsets.UTF_8);
                System.err.println("ERROR - API returned " + response.statusCode() + ": " + errorBody);
                return generateQRCodeLocal(user, event, participation, size);
            }

            String contentType = response.headers().firstValue("Content-Type").orElse("");
            if (!contentType.contains("image")) {
                System.err.println("ERROR - API returned non-image content: " + contentType);
                return generateQRCodeLocal(user, event, participation, size);
            }

            String userHome = System.getProperty("user.home");
            Path qrDir = Paths.get(userHome, "CashFly", "QR_Codes");
            Files.createDirectories(qrDir);

            String filename = "qr_api_" + participation.getIdParticipation() + "_" +
                    System.currentTimeMillis() + ".png";
            String filepath = qrDir.resolve(filename).toString();

            try (FileOutputStream fos = new FileOutputStream(filepath)) {
                fos.write(response.body());
            }

            return filepath;

        } catch (java.net.UnknownHostException | java.net.ConnectException e) {
            System.err.println("ERROR - Network error, no internet: " + e.getMessage());
            return generateQRCodeLocal(user, event, participation, size);
        }
    }

    /**
     * Fallback: Generate QR code locally using ZXing
     */
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

    // ============================================
    // BADGE DESIGN (Called on FX Thread)
    // ============================================

    /**
     * Creates the visual badge design - MUST be called on FX thread
     */
    private static Canvas createBadgeCanvas(Utilisateur user, JPO event, Participation participation, Image qrImage) {

        Canvas canvas = new Canvas(BADGE_WIDTH, BADGE_HEIGHT);
        GraphicsContext gc = canvas.getGraphicsContext2D();

        // Background (Deep Navy)
        gc.setFill(Color.web("#0D2440"));
        gc.fillRect(0, 0, BADGE_WIDTH, BADGE_HEIGHT);

        // Top accent bar (Cashfly Sapphire Blue)
        gc.setFill(Color.web("#2E5E99"));
        gc.fillRect(0, 0, BADGE_WIDTH, 20);

        // Event title
        gc.setFill(Color.web("#E7F0FA"));
        gc.setFont(Font.font("Segoe UI", FontWeight.BOLD, 48));
        gc.fillText(truncateText(event.getTitre(), 35), 50, 100);

        // Event details
        gc.setFill(Color.WHITE);
        gc.setFont(Font.font("Segoe UI", 28));
        String dateStr = formatEventDateSafe(event.getDate_evenement());
        gc.fillText("Date: " + dateStr, 50, 160);
        gc.fillText("Lieu: " + truncateText(event.getLieu(), 40), 50, 210);

        // Separator line
        gc.setStroke(Color.web("#7BA4D0"));
        gc.setLineWidth(2);
        gc.strokeLine(50, 250, 580, 250);

        // Participant section
        gc.setFill(Color.web("#7BA4D0"));
        gc.setFont(Font.font("Segoe UI", FontWeight.NORMAL, 32));
        gc.fillText("PARTICIPANT", 50, 310);

        gc.setFill(Color.WHITE);
        gc.setFont(Font.font("Segoe UI", FontWeight.BOLD, 42));
        gc.fillText(truncateText(user.getNomComplet(), 30), 50, 370);

        gc.setFill(Color.web("#E7F0FA"));
        gc.setFont(Font.font("Segoe UI", 24));
        gc.fillText(truncateText(user.getEmail(), 40), 50, 410);

        // Status badge format
        String statusText = participation.getStatut().toUpperCase();
        Color statusColor = switch (participation.getStatut().toLowerCase()) {
            case "confirmé" -> Color.web("#28a745");
            case "en_attente" -> Color.web("#fd7e14");
            case "annulé" -> Color.web("#dc3545");
            default -> Color.web("#6c757d");
        };

        // Draw pill background
        gc.setFill(statusColor);
        gc.fillRoundRect(50, 460, 240, 50, 25, 25);
        gc.setFill(Color.WHITE);
        gc.setFont(Font.font("Segoe UI", FontWeight.BOLD, 22));
        gc.fillText("STATUT : " + statusText, 70, 492);

        // Draw QR code with modern placement
        gc.drawImage(qrImage, 600, 160, 380, 380);

        // QR label
        gc.setFill(Color.web("#7BA4D0"));
        gc.setFont(Font.font("Segoe UI", FontWeight.NORMAL, 16));
        gc.fillText("Scanner pour validation sécurisée", 670, 570);

        return canvas;
    }

    /**
     * Saves the canvas as PNG file - MUST be called on FX thread
     */
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

    // ============================================
    // UTILITY METHODS
    // ============================================

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
            System.err.println("ERROR - formatEventDateSafe failed: " + e.getMessage());
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
