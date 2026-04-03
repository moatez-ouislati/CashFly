package tn.cashfly.services;

import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

import java.io.IOException;
import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.HashMap;
import java.util.Map;

public class ExchangeRateService {

    private static final String API_URL = "https://open.er-api.com/v6/latest/";
    private final HttpClient httpClient;

    public ExchangeRateService() {
        this.httpClient = HttpClient.newBuilder()
                .followRedirects(HttpClient.Redirect.NORMAL)
                .build();
    }

    /**
     * Récupère le taux de change entre deux devises.
     * Exemple : getExchangeRate("USD", "TND") retourne le taux pour 1 USD en TND.
     */
    public double getExchangeRate(String from, String to) throws IOException, InterruptedException {
        if (from == null || to == null) return 1.0;
        if (from.equalsIgnoreCase(to)) return 1.0;

        String url = API_URL + from.toUpperCase();
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(url))
                .GET()
                .build();

        HttpResponse<String> response = httpClient.send(request, HttpResponse.BodyHandlers.ofString());

        if (response.statusCode() == 200) {
            JsonObject jsonObject = JsonParser.parseString(response.body()).getAsJsonObject();
            if ("success".equals(jsonObject.get("result").getAsString())) {
                JsonObject rates = jsonObject.getAsJsonObject("rates");
                if (rates.has(to.toUpperCase())) {
                    return rates.get(to.toUpperCase()).getAsDouble();
                } else {
                    throw new IOException("Devise de destination non supportée : " + to);
                }
            } else {
                throw new IOException("Erreur API : " + jsonObject.get("error-type").getAsString());
            }
        } else {
            throw new IOException("Erreur lors de la récupération du taux de change: " + response.statusCode());
        }
    }

    /**
     * Récupère tous les taux de change pour une devise de base.
     */
    public Map<String, Double> getAllRates(String base) throws IOException, InterruptedException {
        String url = API_URL + base.toUpperCase();
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(url))
                .GET()
                .build();

        HttpResponse<String> response = httpClient.send(request, HttpResponse.BodyHandlers.ofString());

        Map<String, Double> ratesMap = new HashMap<>();
        if (response.statusCode() == 200) {
            JsonObject jsonObject = JsonParser.parseString(response.body()).getAsJsonObject();
            if ("success".equals(jsonObject.get("result").getAsString())) {
                JsonObject rates = jsonObject.getAsJsonObject("rates");
                for (String key : rates.keySet()) {
                    ratesMap.put(key, rates.get(key).getAsDouble());
                }
            }
        } else {
            throw new IOException("Erreur lors de la récupération des taux de change: " + response.statusCode());
        }
        return ratesMap;
    }
}

