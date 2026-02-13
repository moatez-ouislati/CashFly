package tn.cashfly.utils;

import java.sql.*;

public class CashFlyDB {

    private static CashFlyDB instance;
    final String URL = "jdbc:mysql://127.0.0.1:3306/cashflydb";
    final String USERNAME ="root";
    final String PASSWORD ="";
    private Connection connection;

    private CashFlyDB() {
        try {
            connection = DriverManager.getConnection(URL,USERNAME,PASSWORD);

            System.out.println("Base de donnees CashFlyDB est connectée ...");

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
