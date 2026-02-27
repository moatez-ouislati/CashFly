package tn.cashfly.services;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

import java.nio.charset.StandardCharsets;
import java.util.ArrayList;
import java.util.List;

/**
 * Service to fetch financial news from NewsAPI.org (free tier)
 * Free API key: get yours at https://newsapi.org/register
 * The key below is a public demo key – replace with your own if needed.
 */
public class NewsService {

    // NewsAPI endpoint for finance, economy, and investment strictly in Tunisia
    private static final String API_KEY = "";
    // Encoded advanced query: (finance OR économie OR bourse OR investissement) AND
    // tunisie
    private static final String NEWS_API_URL = "https://newsapi.org/v2/everything?q=%28finance+OR+%C3%A9conomie+OR+bourse+OR+investissement%29+AND+tunisie&language=fr&sortBy=publishedAt&apiKey="
            + API_KEY;

    public static class NewsArticle {
        public String title;
        public String description;
        public String url;
        public String publishedAt;
        public String source;

        public NewsArticle(String title, String description, String url, String publishedAt, String source) {
            this.title = title;
            this.description = description;
            this.url = url;
            this.publishedAt = publishedAt;
            this.source = source;
        }
    }

    /**
     * Fetches financial news using the free TheNewsAPI (no key needed for limited
     * use)
     * or falls back to hardcoded sample news.
     */
    public List<NewsArticle> fetchFinancialNews() {
        List<NewsArticle> articles = new ArrayList<>();

        try {
            // Try fetching from a free publically accessible news API
            String apiUrl = NEWS_API_URL;

            String response = makeGetRequest(apiUrl);
            if (response != null && !response.isEmpty()) {
                articles = parseNewsApiResponse(response);
            }
        } catch (Exception e) {
            System.err.println("News API fetch failed, using fallback: " + e.getMessage());
        }

        // Always add fallback/demo articles if we got nothing
        if (articles.isEmpty()) {
            articles = getFallbackNews();
        }

        return articles;
    }

    /**
     * Fetches news from MediaStack free API (1000 requests/month free)
     */
    public List<NewsArticle> fetchFromMediaStack(String apiKey) {
        List<NewsArticle> articles = new ArrayList<>();
        try {
            String url = "http://api.mediastack.com/v1/news?" +
                    "access_key=" + apiKey +
                    "&categories=business&languages=fr&limit=10";
            String response = makeGetRequest(url);
            if (response != null) {
                articles = parseMediaStackResponse(response);
            }
        } catch (Exception e) {
            System.err.println("MediaStack API error: " + e.getMessage());
        }
        return articles;
    }

    private String makeGetRequest(String apiUrl) throws Exception {
        URL url = new URL(apiUrl);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("GET");
        conn.setConnectTimeout(8000);
        conn.setReadTimeout(8000);
        conn.setRequestProperty("User-Agent", "CashFly/1.0");
        conn.setRequestProperty("Accept", "application/json");

        int responseCode = conn.getResponseCode();
        if (responseCode == 200) {
            BufferedReader reader = new BufferedReader(
                    new InputStreamReader(conn.getInputStream(), StandardCharsets.UTF_8));
            StringBuilder sb = new StringBuilder();
            String line;
            while ((line = reader.readLine()) != null)
                sb.append(line);
            reader.close();
            return sb.toString();
        }
        return null;
    }

    private List<NewsArticle> parseNewsApiResponse(String json) {
        List<NewsArticle> articles = new ArrayList<>();
        try {
            int articlesIndex = json.indexOf("\"articles\":");
            if (articlesIndex < 0)
                return articles;

            // Each article starts around '"source":{' in the json array
            String[] items = json.substring(articlesIndex).split("\"source\":\\{");
            for (int i = 1; i < items.length && articles.size() < 10; i++) {
                String item = items[i];
                String title = extractJsonValue(item, "title");
                String description = extractJsonValue(item, "description");
                String url = extractJsonValue(item, "url");
                String publishedAt = extractJsonValue(item, "publishedAt");

                // For source name, extract json value with key "name" which follows immediately
                // in the "source" object
                String source = extractJsonValue(item, "name");

                if (title != null && !title.isEmpty()) {
                    articles.add(new NewsArticle(title, description, url, publishedAt, source));
                }
            }
        } catch (Exception e) {
            System.err.println("Parse error: " + e.getMessage());
        }
        return articles;
    }

    private List<NewsArticle> parseMediaStackResponse(String json) {
        List<NewsArticle> articles = new ArrayList<>();
        try {
            String[] items = json.split("\"author\":");
            for (int i = 1; i < items.length && i <= 10; i++) {
                String item = items[i];
                String title = extractJsonValue(item, "title");
                String description = extractJsonValue(item, "description");
                String url = extractJsonValue(item, "url");
                String publishedAt = extractJsonValue(item, "published_at");
                String source = extractJsonValue(item, "source");
                if (title != null && !title.isEmpty()) {
                    articles.add(new NewsArticle(title, description, url, publishedAt, source));
                }
            }
        } catch (Exception e) {
            System.err.println("Parse error: " + e.getMessage());
        }
        return articles;
    }

    private String extractJsonValue(String json, String key) {
        try {
            String searchKey = "\"" + key + "\":\"";
            int start = json.indexOf(searchKey);
            if (start < 0)
                return "";
            start += searchKey.length();
            int end = json.indexOf("\"", start);
            while (end > 0 && json.charAt(end - 1) == '\\') {
                end = json.indexOf("\"", end + 1);
            }
            if (end < 0)
                return "";
            return json.substring(start, end).replace("\\\"", "\"").replace("\\n", " ");
        } catch (Exception e) {
            return "";
        }
    }

    /**
     * Returns curated fallback news (realistic finance news) when API is
     * unavailable.
     */
    public List<NewsArticle> getFallbackNews() {
        List<NewsArticle> articles = new ArrayList<>();
        articles.add(new NewsArticle(
                "Les marchés boursiers mondiaux en hausse grâce aux données économiques positives",
                "Les indices boursiers ont enregistré des gains significatifs suite à la publication de données économiques meilleures que prévu, notamment le rapport sur l'emploi américain.",
                "https://www.bbc.com/news/business",
                "Aujourd'hui",
                "Reuters Finance"));
        articles.add(new NewsArticle(
                "La Banque Centrale Tunisienne maintient son taux directeur à 8%",
                "Le Conseil d'Administration de la BCT a décidé de maintenir le taux d'intérêt directeur inchangé, tout en surveillant les pressions inflationnistes.",
                "https://www.bct.gov.tn",
                "Cette semaine",
                "BCT Officiel"));
        articles.add(new NewsArticle(
                "Investissement en Afrique: les flux de capitaux étrangers augmentent de 15%",
                "Selon un rapport de la BAD, les investissements directs étrangers en Afrique subsaharienne ont connu une augmentation significative grâce aux réformes économiques.",
                "https://www.afdb.org",
                "Il y a 2 jours",
                "Banque Africaine de Développement"));
        articles.add(new NewsArticle(
                "Bitcoin dépasse les 65 000 dollars : retour des institucionnels",
                "La cryptomonnaie phare connaît un regain d'intérêt des investisseurs institutionnels, portant son cours à des niveaux record depuis plusieurs mois.",
                "https://www.coindesk.com",
                "Il y a 3 jours",
                "CoinDesk"));
        articles.add(new NewsArticle(
                "Le CAC 40 progresse de 1,2% grâce au secteur technologique",
                "Les valeurs technologiques françaises et européennes ont tiré l'indice vers le haut, stimulées par de bons résultats trimestriels.",
                "https://www.boursorama.com",
                "Il y a 4 jours",
                "Boursorama"));
        articles.add(new NewsArticle(
                "Immobilier : les taux hypothécaires commencent à baisser en Europe",
                "Les banques centrales européennes commencent à assouplir leur politique monétaire, ce qui se traduit par une baisse progressive des taux immobiliers.",
                "https://www.lefigaro.fr/immobilier",
                "Il y a 5 jours",
                "Le Figaro Immobilier"));
        return articles;
    }
}
