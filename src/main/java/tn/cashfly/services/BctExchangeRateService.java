package tn.cashfly.services;

import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * Banque Centrale de Tunisie - Official Exchange Rates API (via fallback JSON feed).
 * Source institution: https://www.bct.gov.tn/
 * Fallback provider: https://open.er-api.com/v6/latest/TND (no API key required).
 */
public class BctExchangeRateService {

    // Fallback API endpoint since BCT bo_change.json is currently returning 404
    private static final String BCT_API_URL = "https://open.er-api.com/v6/latest/TND";

    /**
     * Get today's official TND exchange rates.
     * The API returns how much foreign currency 1 TND buys, so we invert
     * the value to obtain how many TND 1 unit of foreign currency costs.
     */
    public Map<String, ExchangeRate> getTodayExchangeRates() throws Exception {
        System.out.println("Fetching exchange rates...");

        String response = makeRequest(BCT_API_URL);
        JsonObject root = JsonParser.parseString(response).getAsJsonObject();

        if (root.has("result") && !"success".equalsIgnoreCase(root.get("result").getAsString())) {
            String error = root.has("error-type") ? root.get("error-type").getAsString() : "unknown";
            throw new Exception("BCT API error: " + error);
        }
        if (!root.has("rates") || !root.get("rates").isJsonObject()) {
            throw new Exception("BCT API malformed response: missing rates");
        }

        JsonObject rates = root.getAsJsonObject("rates");
        Map<String, ExchangeRate> rateMap = new HashMap<>();

        LocalDate today = LocalDate.now();

        for (String currencyCode : rates.keySet()) {
            double rateValue = rates.get(currencyCode).getAsDouble();
            if (rateValue == 0) continue;

            ExchangeRate rate = new ExchangeRate();
            rate.currencyCode = currencyCode;
            rate.currencyName = currencyCode; // Name not provided directly by API

            double tndCost = 1.0 / rateValue;
            rate.buyRate = tndCost;
            rate.sellRate = tndCost;
            rate.middleRate = tndCost;
            rate.date = today;

            rateMap.put(currencyCode, rate);
        }

        System.out.println("✅ Fetched " + rateMap.size() + " exchange rates");
        return rateMap;
    }

    /**
     * Convert amount from TND to foreign currency
     */
    public double convertFromTND(String toCurrency, double amountTND) throws Exception {
        Map<String, ExchangeRate> rates = getTodayExchangeRates();

        if (!rates.containsKey(toCurrency)) {
            throw new IllegalArgumentException("Currency not supported: " + toCurrency);
        }

        ExchangeRate rate = rates.get(toCurrency);
        return amountTND / rate.middleRate;
    }

    /**
     * Convert amount from foreign currency to TND
     */
    public double convertToTND(String fromCurrency, double amount) throws Exception {
        Map<String, ExchangeRate> rates = getTodayExchangeRates();

        if (!rates.containsKey(fromCurrency)) {
            throw new IllegalArgumentException("Currency not supported: " + fromCurrency);
        }

        ExchangeRate rate = rates.get(fromCurrency);
        return amount * rate.middleRate;
    }

    /**
     * Get specific currency rate
     */
    public ExchangeRate getRate(String currencyCode) throws Exception {
        Map<String, ExchangeRate> rates = getTodayExchangeRates();
        return rates.get(currencyCode);
    }

    /**
     * Get main currencies (USD, EUR, GBP, SAR)
     */
    public List<ExchangeRate> getMainCurrencies() throws Exception {
        Map<String, ExchangeRate> allRates = getTodayExchangeRates();
        List<ExchangeRate> mainRates = new ArrayList<>();

        String[] mainCodes = { "USD", "EUR", "GBP", "SAR", "CAD", "JPY", "CHF" };

        for (String code : mainCodes) {
            if (allRates.containsKey(code)) {
                mainRates.add(allRates.get(code));
            }
        }

        return mainRates;
    }

    /**
     * Format amount with currency
     */
    public String formatAmount(double amount, String currency) {
        return String.format("%,.2f %s", amount, currency);
    }

    /**
     * Calculate investment value in multiple currencies
     */
    public InvestmentMultiCurrency calculateInvestmentValue(double amountTND) throws Exception {
        InvestmentMultiCurrency result = new InvestmentMultiCurrency();
        result.amountTND = amountTND;

        Map<String, ExchangeRate> rates = getTodayExchangeRates();

        if (rates.containsKey("USD")) {
            result.amountUSD = amountTND / rates.get("USD").middleRate;
        }
        if (rates.containsKey("EUR")) {
            result.amountEUR = amountTND / rates.get("EUR").middleRate;
        }
        if (rates.containsKey("GBP")) {
            result.amountGBP = amountTND / rates.get("GBP").middleRate;
        }

        return result;
    }

    private String makeRequest(String urlString) throws Exception {
        URL url = new URL(urlString);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("GET");
        conn.setConnectTimeout(10000);
        conn.setReadTimeout(10000);
        conn.setRequestProperty("User-Agent", "CashFly/1.0");

        int responseCode = conn.getResponseCode();
        if (responseCode != 200) {
            throw new Exception("BCT API returned code: " + responseCode);
        }

        BufferedReader reader = new BufferedReader(
                new InputStreamReader(conn.getInputStream(), "UTF-8"));
        StringBuilder response = new StringBuilder();
        String line;
        while ((line = reader.readLine()) != null) {
            response.append(line);
        }
        reader.close();
        conn.disconnect();

        return response.toString();
    }

    // ==================== DATA CLASSES ====================

    public static class ExchangeRate {
        public String currencyCode;
        public String currencyName;
        public double buyRate;
        public double sellRate;
        public double middleRate;
        public LocalDate date;

        @Override
        public String toString() {
            return String.format(
                    "%s (%s): Achat=%.4f, Vente=%.4f, Moyen=%.4f TND",
                    currencyCode, currencyName, buyRate, sellRate, middleRate);
        }
    }

    public static class InvestmentMultiCurrency {
        public double amountTND;
        public double amountUSD;
        public double amountEUR;
        public double amountGBP;

        @Override
        public String toString() {
            return String.format(
                    "TND: %,.2f | USD: $%,.2f | EUR: €%,.2f | GBP: £%,.2f",
                    amountTND, amountUSD, amountEUR, amountGBP);
        }
    }
}
