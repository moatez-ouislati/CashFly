package tn.cashfly.services;

import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
import java.math.BigDecimal;
import java.util.List;

/**
 * AI Recommendation Service
 * Primary: OpenAI (cloud)
 * Secondary: OpenRouter
 * Fallback: rule-based recommendations
 */
public class AiRecommendationService {

    // =========================
    // 🔹 OpenAI Configuration
    // =========================
    private static final String OPENAI_URL = "https://api.openai.com/v1/chat/completions";
    private static final String OPENAI_API_KEY = "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"; // 🔐 replace
    private static final String OPENAI_MODEL = "gpt-4o-mini";

    // =========================
    // 🔹 OpenRouter (optional fallback)
    // =========================
    private static final String OPENROUTER_URL = "https://openrouter.ai/api/v1/chat/completions";
    private static final String OPENROUTER_API_KEY = "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";
    private static final String MODEL = "mistralai/mistral-7b-instruct:free";

    public static class Recommendation {
        public String title;
        public String detail;
        public String type;

        public Recommendation(String title, String detail, String type) {
            this.title = title;
            this.detail = detail;
            this.type = type;
        }
    }

    public List<Recommendation> generateRecommendations(
            BigDecimal totalInvested,
            BigDecimal totalGain,
            BigDecimal totalPerte,
            int numberOfInvestments,
            double avgTaux) {

        String prompt = buildPrompt(totalInvested, totalGain, totalPerte, numberOfInvestments, avgTaux);

        // 🔹 Try OpenAI (Cloud)
        try {
            String response = callOpenAI(prompt);
            if (response != null && !response.isEmpty()) {
                return parseAiResponse(response);
            }
        } catch (Exception e) {
            System.out.println("OpenAI not available: " + e.getMessage());
        }

        // 🔹 Try OpenRouter (fallback)
        if (!OPENROUTER_API_KEY.equals("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")) {
            try {
                String response = callOpenRouter(prompt);
                if (response != null && !response.isEmpty()) {
                    return parseAiResponse(response);
                }
            } catch (Exception e) {
                System.out.println("OpenRouter not available: " + e.getMessage());
            }
        }

        // 🔹 Final fallback
        return getRuleBasedRecommendations(totalInvested, totalGain, totalPerte, numberOfInvestments, avgTaux);
    }

    private String buildPrompt(BigDecimal totalInvested, BigDecimal totalGain, BigDecimal totalPerte,
                               int numberOfInvestments, double avgTaux) {
        return String.format(
                "Tu es un conseiller financier expert. Analyse ce portefeuille d'investissement tunisien et donne 3 recommandations courtes et précises en français.\n\n"
                        +
                        "Données du portefeuille:\n" +
                        "- Montant total investi: %,.0f TND\n" +
                        "- Gains totaux: %,.2f TND\n" +
                        "- Pertes totales: %,.2f TND\n" +
                        "- Résultat net: %,.2f TND\n" +
                        "- Nombre d'investissements: %d\n" +
                        "- Taux de rendement moyen: %.1f%%\n\n" +
                        "Donne exactement 3 recommandations, chacune sur une ligne commençant par [CONSEIL], [ALERTE] ou [INFO].",
                totalInvested.doubleValue(),
                totalGain.doubleValue(),
                totalPerte.doubleValue(),
                totalGain.subtract(totalPerte).doubleValue(),
                numberOfInvestments,
                avgTaux);
    }

    // =========================
    // 🔹 OpenAI Call
    // =========================
    private String callOpenAI(String prompt) throws Exception {

        String jsonBody = "{"
                + "\"model\":\"" + OPENAI_MODEL + "\","
                + "\"messages\":["
                + "{\"role\":\"system\",\"content\":\"Tu es un expert financier professionnel.\"},"
                + "{\"role\":\"user\",\"content\":\"" + escapeJson(prompt) + "\"}"
                + "]"
                + "}";

        return makePostRequest(OPENAI_URL, jsonBody, OPENAI_API_KEY);
    }

    private String callOpenRouter(String prompt) throws Exception {
        String jsonBody = "{"
                + "\"model\":\"" + MODEL + "\","
                + "\"messages\":[{\"role\":\"user\",\"content\":\"" + escapeJson(prompt) + "\"}]"
                + "}";
        return makePostRequest(OPENROUTER_URL, jsonBody, OPENROUTER_API_KEY);
    }

    private String makePostRequest(String apiUrl, String jsonBody, String authKey) throws Exception {

        URL url = new URL(apiUrl);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();

        conn.setRequestMethod("POST");
        conn.setConnectTimeout(15000);
        conn.setReadTimeout(30000);
        conn.setDoOutput(true);

        conn.setRequestProperty("Content-Type", "application/json");
        conn.setRequestProperty("Accept", "application/json");

        if (authKey != null) {
            conn.setRequestProperty("Authorization", "Bearer " + authKey);
        }

        try (OutputStream os = conn.getOutputStream()) {
            os.write(jsonBody.getBytes(StandardCharsets.UTF_8));
        }

        int responseCode = conn.getResponseCode();

        if (responseCode == 200) {
            BufferedReader reader = new BufferedReader(
                    new InputStreamReader(conn.getInputStream(), StandardCharsets.UTF_8));

            StringBuilder sb = new StringBuilder();
            String line;

            while ((line = reader.readLine()) != null)
                sb.append(line);

            reader.close();
            return extractTextFromJson(sb.toString());
        }

        return null;
    }

    private String extractTextFromJson(String json) {

        int contentIdx = json.indexOf("\"content\":\"");
        if (contentIdx >= 0) {
            int start = contentIdx + 11;
            int end = json.indexOf("\"", start);
            if (end > start) {
                return json.substring(start, end)
                        .replace("\\n", "\n")
                        .replace("\\\"", "\"");
            }
        }

        return json;
    }

    // =========================
    // 🔹 Parsing
    // =========================
    private List<Recommendation> parseAiResponse(String text) {
        List<Recommendation> recs = new java.util.ArrayList<>();
        String[] lines = text.split("\n");

        for (String line : lines) {
            line = line.trim();

            if (line.startsWith("[CONSEIL]")) {
                recs.add(new Recommendation("💡 Conseil IA",
                        line.replace("[CONSEIL]", "").trim(), "success"));
            } else if (line.startsWith("[ALERTE]")) {
                recs.add(new Recommendation("⚠️ Alerte",
                        line.replace("[ALERTE]", "").trim(), "warning"));
            } else if (line.startsWith("[INFO]")) {
                recs.add(new Recommendation("ℹ️ Information",
                        line.replace("[INFO]", "").trim(), "info"));
            }
        }

        return recs;
    }

    private String escapeJson(String text) {
        return text.replace("\\", "\\\\")
                .replace("\"", "\\\"")
                .replace("\n", "\\n")
                .replace("\r", "\\r")
                .replace("\t", "\\t");
    }

    // Your existing rule-based method stays unchanged
    public List<Recommendation> getRuleBasedRecommendations(
            BigDecimal totalInvested, BigDecimal totalGain,
            BigDecimal totalPerte, int count, double avgTaux) {

        List<Recommendation> recs = new java.util.ArrayList<>();
        BigDecimal net = totalGain.subtract(totalPerte);

        if (net.compareTo(BigDecimal.ZERO) > 0) {
            double roi = totalInvested.compareTo(BigDecimal.ZERO) > 0
                    ? net.divide(totalInvested, 4, java.math.RoundingMode.HALF_UP)
                    .multiply(BigDecimal.valueOf(100)).doubleValue()
                    : 0;

            recs.add(new Recommendation(
                    "💡 Performance Positive",
                    String.format("Votre portefeuille affiche un ROI de %.1f%%.", roi),
                    "success"));
        } else {
            recs.add(new Recommendation(
                    "⚠️ Attention aux Pertes",
                    "Vos pertes dépassent vos gains. Revoyez votre stratégie.",
                    "warning"));
        }

        return recs;
    }
}