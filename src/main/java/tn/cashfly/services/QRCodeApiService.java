package tn.cashfly.services;

import tn.cashfly.entities.JPO;
import tn.cashfly.entities.Participation;
import tn.cashfly.entities.Utilisateur;

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
import java.time.format.DateTimeFormatter;

/**
 * Service for generating QR codes via external API (GoQR.me)
 * No API key required for basic usage
 */
public class QRCodeApiService {

    private static final String GOQR_API_URL = "https://api.qrserver.com/v1/create-qr-code/";
    private final HttpClient httpClient;

    public QRCodeApiService() {
        this.httpClient = HttpClient.newBuilder()
                .followRedirects(HttpClient.Redirect.NORMAL)
                .build();
    }

    /**
     * Generates QR code via GoQR.me API and saves to file
     */
    public String generateQRCode(Utilisateur user, JPO event, Participation participation, int size)
            throws IOException, InterruptedException, QRCodeApiException {

        try {
            // Build the data payload
            String qrData = buildQRData(user, event, participation);

            // URL encode the data
            String encodedData = URLEncoder.encode(qrData, StandardCharsets.UTF_8);

            // Build API URL with CashFly brand colors (dark blue on light blue)
            String apiUrl = String.format("%s?size=%dx%d&color=0D2440&bgcolor=E7F0FA&margin=10&data=%s",
                    GOQR_API_URL, size, size, encodedData);


            // Make HTTP request with timeout
            HttpRequest request = HttpRequest.newBuilder()
                    .uri(URI.create(apiUrl))
                    .timeout(java.time.Duration.ofSeconds(10))
                    .GET()
                    .build();

            HttpResponse<byte[]> response = httpClient.send(request, HttpResponse.BodyHandlers.ofByteArray());

                    response.headers().firstValue("Content-Type").orElse("unknown");

            if (response.statusCode() != 200) {
                String errorBody = new String(response.body(), StandardCharsets.UTF_8);
                throw new QRCodeApiException("QR API returned status " + response.statusCode() +
                        ". Body: " + errorBody.substring(0, Math.min(200, errorBody.length())));
            }

            // Verify we got an image
            String contentType = response.headers().firstValue("Content-Type").orElse("");
            if (!contentType.contains("image")) {
                throw new QRCodeApiException("API did not return an image. Content-Type: " + contentType);
            }

            // Save the image
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

        } catch (IOException | InterruptedException e) {
            System.err.println("ERROR in generateQRCode: " + e.getClass().getSimpleName() + " - " + e.getMessage());
            throw e;
        }
    }

    /**
     * Builds the data string that will be encoded in the QR code
     */
    private String buildQRData(Utilisateur user, JPO event, Participation participation) {
        StringBuilder payload = new StringBuilder();
        payload.append("=== CASHFLY JPO TICKET ===\n");
        payload.append("Participant: ").append(user.getNomComplet()).append("\n");
        payload.append("Email: ").append(user.getEmail()).append("\n");
        payload.append("Status: ").append(participation.getStatut().toUpperCase()).append("\n");
        payload.append("---\n");
        payload.append("Event: ").append(event.getTitre()).append("\n");

        // CRITICAL FIX: Handle java.sql.Date properly
        String dateStr = formatEventDateSafe(event.getDate_evenement());
        payload.append("Date: ").append(dateStr).append("\n");

        payload.append("Location: ").append(event.getLieu()).append("\n");
        payload.append("---\n");
        payload.append("Reg.ID: ").append(participation.getIdParticipation()).append("\n");
        payload.append("Verified: YES\n");
        payload.append("==========================");

        return payload.toString();
    }

    /**
     * SAFE date formatting that handles both java.util.Date and java.sql.Date
     */
    private String formatEventDateSafe(java.util.Date date) {
        if (date == null) {
            return "N/A";
        }

        try {
            // Use getTime() which works for BOTH java.util.Date and java.sql.Date
            long epochMillis = date.getTime();

            String formatted = java.time.Instant.ofEpochMilli(epochMillis)
                    .atZone(java.time.ZoneId.systemDefault())
                    .format(DateTimeFormatter.ofPattern("dd MMMM yyyy HH:mm"));

            return formatted;

        } catch (Exception e) {
            System.err.println("ERROR formatting date: " + e.getMessage());
            return date.toString(); // Fallback
        }
    }

    /**
     * Custom exception for API errors
     */
    public static class QRCodeApiException extends Exception {
        public QRCodeApiException(String message) {
            super(message);
        }
    }
}
