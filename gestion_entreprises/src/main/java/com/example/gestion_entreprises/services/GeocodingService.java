package com.example.gestion_entreprises.services;

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
            conn.setRequestProperty("User-Agent", "gestion-entreprises-app");
            conn.setConnectTimeout(5000);
            conn.setReadTimeout(5000);

            if (conn.getResponseCode() != 200) {
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

            org.json.JSONArray array =
                    new org.json.JSONArray(response.toString());

            if (array.length() > 0) {
                org.json.JSONObject obj = array.getJSONObject(0);

                double lat =
                        Double.parseDouble(obj.getString("lat"));
                double lon =
                        Double.parseDouble(obj.getString("lon"));

                return new double[]{lat, lon};
            }

        } catch (Exception e) {
            e.printStackTrace();
        }

        return new double[]{0, 0};
    }
}
