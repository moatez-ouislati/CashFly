package com.example.gestion_entreprises.main;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.stage.Stage;

import java.io.IOException;

public class MainClass extends Application {

    @Override
    public void start(Stage primaryStage) throws IOException {

        FXMLLoader fxmlLoader = new FXMLLoader(
                getClass().getResource(
                        "/com/example/gestion_entreprises/MainMenu.fxml"
                )
        );
        Scene scene = new Scene(fxmlLoader.load(), 800, 600);
        primaryStage.setTitle("CashFly");
        primaryStage.setScene(scene);
        primaryStage.show();
    }

    public static void main(String[] args) {
        launch(args);
    }
}
