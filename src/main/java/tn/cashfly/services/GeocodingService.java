package tn.cashfly.services;

import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

public class GeocodingService {

    public static double[] getCoordinates(String adresse) {

        if (adresse == null || adresse.isBlank()) {
            return new double[]{0, 0};
        }

        try {

            String urlString =
                    "https://nominatim.openstreetmap.org/search?q="
                            + java.net.URLEncoder.encode(adresse, "UTF-8")
                            + "&format=json&limit=1";

            URL url = new URL(urlString);
            HttpURLConnection conn =
                    (HttpURLConnection) url.openConnection();

            conn.setRequestMethod("GET");
            conn.setRequestProperty("User-Agent", "CashFly-App/1.0 (contact: support@cashfly.tn)");
            conn.setConnectTimeout(8000);
            conn.setReadTimeout(8000);

            if (conn.getResponseCode() != 200) {
                System.err.println("Nominatim API error: " + conn.getResponseCode());
                return new double[]{0, 0};
            }

            BufferedReader reader =
                    new BufferedReader(
                            new InputStreamReader(conn.getInputStream())
                    );

            StringBuilder response = new StringBuilder();
            String line;

            while ((line = reader.readLine()) != null) {
                response.append(line);
            }

            reader.close();

            JsonArray array = JsonParser.parseString(response.toString()).getAsJsonArray();

            if (array.size() > 0) {
                JsonObject obj = array.get(0).getAsJsonObject();

                double lat = obj.get("lat").getAsDouble();
                double lon = obj.get("lon").getAsDouble();

                return new double[]{lat, lon};
            }

        } catch (Exception e) {
            e.printStackTrace();
        }

        return new double[]{0, 0};
    }
}
