package tn.cashfly.jpo.utils;

import io.github.cdimascio.dotenv.Dotenv;
import java.sql.*;

public class CashFlyDB {

    private static CashFlyDB instance;
    private static final Dotenv dotenv = Dotenv.load();
    final String URL = dotenv.get("DB_URL");
    final String USERNAME = dotenv.get("DB_USER");
    final String PASSWORD = dotenv.get("DB_PASS");
    private Connection connection;

    private CashFlyDB() {
        try {
            connection = DriverManager.getConnection(URL, USERNAME, PASSWORD);
        } catch (SQLException e) {
        }
    }

    public static CashFlyDB getInstance() {
        if (instance == null)
            instance = new CashFlyDB();
        return instance;
    }

    public Connection getConnection() {
        try {
            if (connection == null || connection.isClosed()) {
                connection = DriverManager.getConnection(URL, USERNAME, PASSWORD);
            }
        } catch (SQLException e) {
        }
        return connection;
    }
}
