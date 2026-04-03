package tn.cashfly.services;

import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
import java.math.BigDecimal;
import java.util.List;
import io.github.cdimascio.dotenv.Dotenv;

public class AiRecommendationService {

    private static final Dotenv dotenv = Dotenv.load();

    private static final String OPENAI_URL = "https://api.openai.com/v1/chat/completions";
    private static final String OPENAI_API_KEY = dotenv.get("OPENAI_API_KEY");
    private static final String OPENAI_MODEL = "gpt-4o-mini";

    private static final String OPENROUTER_URL = "https://openrouter.ai/api/v1/chat/completions";
    private static final String OPENROUTER_API_KEY = dotenv.get("OPENROUTER_API_KEY");
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

        try {
            String response = callOpenAI(prompt);
            if (response != null && !response.isEmpty()) {
                return parseAiResponse(response);
            }
        } catch (Exception e) {
            System.out.println("OpenAI not available: " + e.getMessage());
        }

        if (OPENROUTER_API_KEY != null && !OPENROUTER_API_KEY.isEmpty()) {
            try {
                String response = callOpenRouter(prompt);
                if (response != null && !response.isEmpty()) {
                    return parseAiResponse(response);
                }
            } catch (Exception e) {
                System.out.println("OpenRouter not available: " + e.getMessage());
            }
        }

        return getRuleBasedRecommendations(totalInvested, totalGain, totalPerte, numberOfInvestments, avgTaux);
    }

    private String buildPrompt(BigDecimal totalInvested, BigDecimal totalGain, BigDecimal totalPerte,
                               int numberOfInvestments, double avgTaux) {
        return String.format(
                "Tu es un conseiller financier expert et stratégique pour un investisseur de haut niveau en Tunisie. " +
                        "Analyse ce portefeuille et formule 4 recommandations pointues et professionnelles en français.\n"
                        +
                        "En plus de l'analyse des chiffres, tu dois IMPÉRATIVEMENT inclure des conseils sur la manière de "
                        +
                        "choisir les prochaines entreprises dans lesquelles investir (critères d'évaluation, secteurs porteurs, "
                        +
                        "analyse des fondamentaux et des risques).\n\n" +
                        "Données du portefeuille :\n" +
                        "- Montant total investi: %,.0f TND\n" +
                        "- Gains totaux: %,.2f TND\n" +
                        "- Pertes totales: %,.2f TND\n" +
                        "- Résultat net: %,.2f TND\n" +
                        "- Nombre d'investissements: %d\n" +
                        "- Taux de rendement moyen: %.1f%%\n\n" +
                        "Format exigé : Donne EXACTEMENT 4 recommandations distinctes, chacune sur sa propre ligne et commençant OBLIGATOIREMENT par [CONSEIL], [ALERTE] ou [INFO].",
                totalInvested.doubleValue(),
                totalGain.doubleValue(),
                totalPerte.doubleValue(),
                totalGain.subtract(totalPerte).doubleValue(),
                numberOfInvestments,
                avgTaux);
    }

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
                    String.format(
                            "Votre portefeuille affiche un ROI net de %.1f%%. Maintenez cette rigueur de sélection.",
                            roi),
                    "success"));
        } else {
            recs.add(new Recommendation(
                    "⚠️ Attention aux Pertes",
                    "Vos pertes dépassent vos gains. Il est impératif de resserrer vos critères de sélection et d'auditer vos actifs sous-performants.",
                    "warning"));
        }
        recs.add(new Recommendation(
                "💡 Évaluation d'Entreprise",
                "Pour vos prochains financements, privilégiez les entreprises ayant un flux de trésorerie disponible (Free Cash Flow) positif et un modèle de revenus récurrents sécurisé (B2B, SaaS, contrats longs).",
                "info"));
        recs.add(new Recommendation(
                "💡 Analyse des Risques",
                "Ne vous fiez pas uniquement aux promesses de rendement prévisionnel. Exigez toujours l'analyse du BFR (Besoin en Fonds de Roulement) et du ratio d'endettement net avant toute prise de participation.",
                "success"));
        return recs;
    }
}
