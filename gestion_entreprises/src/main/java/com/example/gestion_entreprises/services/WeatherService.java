package com.example.gestion_entreprises.services;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

public class WeatherService {

    private static final String API_KEY = "17b5dff300384b65bfc2acc837425db4";

    public static String getWeather(double lat, double lon) {

        try {

            String urlString =
                    "https://api.openweathermap.org/data/2.5/weather?lat="
                            + lat
                            + "&lon=" + lon
                            + "&appid=" + API_KEY
                            + "&units=metric";

            URL url = new URL(urlString);
            HttpURLConnection conn =
                    (HttpURLConnection) url.openConnection();

            conn.setRequestMethod("GET");

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

            return response.toString();

        } catch (Exception e) {
            e.printStackTrace();
        }

        return null;
    }
}