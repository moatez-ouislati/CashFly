package tn.cashfly.jpo.services;

import tn.cashfly.jpo.entities.JPO;
import tn.cashfly.jpo.entities.Participation;
import tn.cashfly.jpo.entities.Utilisateur;

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

public class QRCodeApiService {

    private static final String GOQR_API_URL = "https://api.qrserver.com/v1/create-qr-code/";
    private final HttpClient httpClient;

    public QRCodeApiService() {
        this.httpClient = HttpClient.newBuilder()
                .followRedirects(HttpClient.Redirect.NORMAL)
                .build();
    }

    public String generateQRCode(Utilisateur user, JPO event, Participation participation, int size)
            throws IOException, InterruptedException, QRCodeApiException {

        try {
            String qrData = buildQRData(user, event, participation);
            String encodedData = URLEncoder.encode(qrData, StandardCharsets.UTF_8);
            String apiUrl = String.format("%s?size=%dx%d&color=0D2440&bgcolor=E7F0FA&margin=10&data=%s",
                    GOQR_API_URL, size, size, encodedData);
            HttpRequest request = HttpRequest.newBuilder()
                    .uri(URI.create(apiUrl))
                    .timeout(java.time.Duration.ofSeconds(10))
                    .GET()
                    .build();
            HttpResponse<byte[]> response = httpClient.send(request, HttpResponse.BodyHandlers.ofByteArray());
            if (response.statusCode() != 200) {
                String errorBody = new String(response.body(), StandardCharsets.UTF_8);
                throw new QRCodeApiException("QR API returned status " + response.statusCode() +
                        ". Body: " + errorBody.substring(0, Math.min(200, errorBody.length())));
            }
            String contentType = response.headers().firstValue("Content-Type").orElse("");
            if (!contentType.contains("image")) {
                throw new QRCodeApiException("API did not return an image. Content-Type: " + contentType);
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
        } catch (IOException | InterruptedException e) {
            throw e;
        }
    }

    private String buildQRData(Utilisateur user, JPO event, Participation participation) {
        StringBuilder payload = new StringBuilder();
        payload.append("=== CASHFLY JPO TICKET ===\n");
        payload.append("Participant: ").append(user.getNomComplet()).append("\n");
        payload.append("Email: ").append(user.getEmail()).append("\n");
        payload.append("Status: ").append(participation.getStatut().toUpperCase()).append("\n");
        payload.append("---\n");
        payload.append("Event: ").append(event.getTitre()).append("\n");
        String dateStr = formatEventDateSafe(event.getDate_evenement());
        payload.append("Date: ").append(dateStr).append("\n");
        payload.append("Location: ").append(event.getLieu()).append("\n");
        payload.append("---\n");
        payload.append("Reg.ID: ").append(participation.getIdParticipation()).append("\n");
        payload.append("Verified: YES\n");
        payload.append("==========================");
        return payload.toString();
    }

    private String formatEventDateSafe(java.util.Date date) {
        if (date == null) {
            return "N/A";
        }
        try {
            long epochMillis = date.getTime();
            String formatted = java.time.Instant.ofEpochMilli(epochMillis)
                    .atZone(java.time.ZoneId.systemDefault())
                    .format(DateTimeFormatter.ofPattern("dd MMMM yyyy HH:mm"));
            return formatted;
        } catch (Exception e) {
            return date.toString();
        }
    }

    public static class QRCodeApiException extends Exception {
        public QRCodeApiException(String message) {
            super(message);
        }
    }
}
