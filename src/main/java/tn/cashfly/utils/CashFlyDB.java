package tn.cashfly.utils;

import java.sql.*;

public class CashFlyDB {

    // Instance unique de la classe (pattern Singleton)
    private static CashFlyDB instance;

    // Paramètres de connexion à la base de données
    final String URL = "jdbc:mysql://127.0.0.1:3306/cashfly_db";
    final String USERNAME ="root";
    final String PASSWORD ="";

    // Objet Connection JDBC
    private Connection connection;

    /**
     * Constructeur privé → empêche la création d'objets depuis l'extérieur
     * et force l'utilisation du Singleton.
     */

    private CashFlyDB() {
        try {
            connection = DriverManager.getConnection(URL,USERNAME,PASSWORD);

            connection.setAutoCommit(true);

            System.out.println("Base de donnees est connecteé ...");

        } catch(SQLException e)
        {
            System.out.println(e.getMessage());
        }
    }

    public static CashFlyDB getInstance()
    {
        if(instance==null)
            instance = new CashFlyDB();
        return instance;
    }

    public Connection getConnection()
    {
        return connection;
    }
}