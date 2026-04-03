package tn.cashfly;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;
import tn.cashfly.tools.SceneManager;

public class Main extends Application {

    @Override
    public void start(Stage primaryStage) throws Exception {
        // Initialize SceneManager
        SceneManager.setStage(primaryStage);

        // Load authentication page by default (from user project)
        Parent root = FXMLLoader.load(getClass().getResource("/authentification.fxml"));
        primaryStage.setTitle("Cashfly - Connexion");
        primaryStage.setScene(new Scene(root));
        primaryStage.setMinWidth(800);
        primaryStage.setMinHeight(520);
        primaryStage.show();
    }

    public static void main(String[] args) {
        launch(args);
    }
}

