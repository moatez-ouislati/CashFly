package tn.cashfly.utils;

import io.github.cdimascio.dotenv.Dotenv;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class MyDataBase {
    private static MyDataBase instance ;
    private static final Dotenv dotenv = Dotenv.load();
    final String URL = dotenv.get("DB_URL");
    final String USERNAME = dotenv.get("DB_USER");
    final String PASSWORD = dotenv.get("DB_PASS");
    private Connection cnx ;
    private MyDataBase(){
        try {
            cnx = DriverManager.getConnection(URL,USERNAME,PASSWORD);

            System.out.println("Connected .... ");
        } catch (SQLException e) {
            System.out.println(e.getMessage());
        }
    }


    public static MyDataBase getInstance(){
        if (instance == null)
            instance = new MyDataBase();
        return instance;
    }

    public Connection getCnx() {
        return cnx;
    }
}

