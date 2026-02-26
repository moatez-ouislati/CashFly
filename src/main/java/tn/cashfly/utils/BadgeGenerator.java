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
     * This method can be called from any thread - it handles FX thread safety internally
     */
    public static String generateBadge(Utilisateur user, JPO event, Participation participation)
            throws Exception {

        System.out.println("=== BadgeGenerator.generateBadge() START ===");

        try {
            // Step 1: Generate QR code via API (can be done on background thread)
            System.out.println("Step 1: Generating QR code via API...");
            String qrFilePath = generateQRCodeViaApi(user, event, participation, QR_SIZE);
            System.out.println("QR code saved at: " + qrFilePath);

            // Step 2: Create badge design (must be on FX thread)
            System.out.println("Step 2: Creating badge canvas on FX thread...");
            Canvas canvas = createCanvasOnFxThread(user, event, participation, qrFilePath);
            System.out.println("Badge canvas created");

            // Step 3: Snapshot and save (must be on FX thread)
            System.out.println("Step 3: Saving badge on FX thread...");
            String badgePath = saveCanvasOnFxThread(canvas, participation.getIdParticipation());
            System.out.println("Badge saved at: " + badgePath);

            System.out.println("=== BadgeGenerator.generateBadge() SUCCESS ===");
            return badgePath;

        } catch (Exception e) {
            System.err.println("=== BadgeGenerator.generateBadge() FAILED ===");
            e.printStackTrace();
            throw e;
        }
    }

    /**
     * Generates standalone QR code only (no badge design)
     * Can be called from any thread
     */
    public static String generateStandaloneQR(Utilisateur user, JPO event, Participation participation, int size)
            throws IOException, InterruptedException, WriterException {

        System.out.println("=== BadgeGenerator.generateStandaloneQR() START ===");

        try {
            String qrPath = generateQRCodeViaApi(user, event, participation, size);
            System.out.println("=== BadgeGenerator.generateStandaloneQR() SUCCESS ===");
            return qrPath;
        } catch (Exception e) {
            System.err.println("=== BadgeGenerator.generateStandaloneQR() FAILED ===");
            e.printStackTrace();
            throw e;
        }
    }

    // ============================================
    // FX THREAD SAFE METHODS
    // ============================================

    /**
     * Creates the badge canvas on FX thread using CountDownLatch for synchronization
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
            System.out.println("DEBUG - QR data length: " + qrData.length());

            String encodedData = URLEncoder.encode(qrData, StandardCharsets.UTF_8);

            String apiUrl = String.format("%s?size=%dx%d&color=0D2440&bgcolor=E7F0FA&margin=10&ecc=M&data=%s",
                    GOQR_API_URL, size, size, encodedData);

            System.out.println("DEBUG - API URL length: " + apiUrl.length());

            if (apiUrl.length() > 2000) {
                System.out.println("WARN - URL too long, falling back to local ZXing");
                return generateQRCodeLocal(user, event, participation, size);
            }

            HttpRequest request = HttpRequest.newBuilder()
                    .uri(URI.create(apiUrl))
                    .timeout(java.time.Duration.ofSeconds(15))
                    .GET()
                    .build();

            System.out.println("DEBUG - Sending HTTP request...");
            HttpResponse<byte[]> response = httpClient.send(request, HttpResponse.BodyHandlers.ofByteArray());

            System.out.println("DEBUG - Response status: " + response.statusCode());

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

            System.out.println("DEBUG - QR saved: " + filepath + " (" + response.body().length + " bytes)");
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

        System.out.println("=== Using LOCAL ZXing fallback ===");

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

        System.out.println("DEBUG - Local QR saved: " + filepath);
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

        // Background
        gc.setFill(Color.web("#1A1C2C"));
        gc.fillRect(0, 0, BADGE_WIDTH, BADGE_HEIGHT);

        // Top accent bar
        gc.setFill(Color.web("#0083ff"));
        gc.fillRect(0, 0, BADGE_WIDTH, 20);

        // Event title
        gc.setFill(Color.web("#00fffd"));
        gc.setFont(Font.font("Arial", FontWeight.BOLD, 48));
        gc.fillText(truncateText(event.getTitre(), 35), 50, 100);

        // Event details
        gc.setFill(Color.WHITE);
        gc.setFont(Font.font("Arial", 28));
        String dateStr = formatEventDateSafe(event.getDate_evenement());
        gc.fillText("📅 " + dateStr, 50, 160);
        gc.fillText("📍 " + truncateText(event.getLieu(), 40), 50, 210);

        // Separator line
        gc.setStroke(Color.web("#0083ff"));
        gc.setLineWidth(3);
        gc.strokeLine(50, 250, 600, 250);

        // Participant section
        gc.setFill(Color.web("#00fffd"));
        gc.setFont(Font.font("Arial", FontWeight.BOLD, 42));
        gc.fillText("PARTICIPANT", 50, 320);

        gc.setFill(Color.WHITE);
        gc.setFont(Font.font("Arial", 36));
        gc.fillText(truncateText(user.getNomComplet(), 30), 50, 380);

        gc.setFont(Font.font("Arial", 24));
        gc.fillText(truncateText(user.getEmail(), 40), 50, 430);

        // Status badge
        String statusText = participation.getStatut().toUpperCase();
        Color statusColor = switch (participation.getStatut().toLowerCase()) {
            case "confirmé" -> Color.web("#28a745");
            case "en_attente" -> Color.web("#ffc107");
            case "annulé" -> Color.web("#dc3545");
            default -> Color.web("#6c757d");
        };

        gc.setFill(statusColor);
        gc.fillRoundRect(50, 470, 220, 45, 22, 22);
        gc.setFill(Color.WHITE);
        gc.setFont(Font.font("Arial", FontWeight.BOLD, 20));
        gc.fillText(statusText, 75, 500);

        // Draw QR code
        gc.drawImage(qrImage, 620, 180, 360, 360);

        // QR label
        gc.setFill(Color.web("#00fffd"));
        gc.setFont(Font.font("Arial", 18));
        gc.fillText("Scan for verification", 680, 560);

        // Registration ID
        gc.setFill(Color.web("#7BA4D0"));
        gc.setFont(Font.font("Arial", 14));
        gc.fillText("Reg. ID: " + participation.getIdParticipation() + " | CashFly JPO System", 50, 610);

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
        payload.append("Reg.ID: ").append(participation.getIdParticipation()).append("\n");
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
        if (text == null) return "";
        if (text.length() <= maxLength) return text;
        return text.substring(0, maxLength - 3) + "...";
    }
}