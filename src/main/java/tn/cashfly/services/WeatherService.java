package tn.cashfly.services;

import io.github.cdimascio.dotenv.Dotenv;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

public class WeatherService {
    private static final Dotenv dotenv = Dotenv.load();
    private static final String API_KEY = dotenv.get("WEATHER_API_KEY");

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
