package tn.cashfly;

import atlantafx.base.theme.PrimerLight;
import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;
import tn.cashfly.utils.NavigationUtil;

public class Main extends Application {

    @Override
    public void start(Stage primaryStage) throws Exception {
        // Set AtlantaFX theme
        Application.setUserAgentStylesheet(new PrimerLight().getUserAgentStylesheet());

        // Initialize NavigationUtil with primary stage
        NavigationUtil.setCurrentStage(primaryStage);

        // Load RoleSelector (which serves as login page)
        Parent root = FXMLLoader.load(getClass().getResource("/tn/cashfly/RoleSelector.fxml"));
        Scene scene = new Scene(root, 1280, 720);
        scene.getStylesheets().add(getClass().getResource("/tn/cashfly/Styles/custom.css").toExternalForm());

        primaryStage.setTitle("CashFly - Connexion");
        primaryStage.setScene(scene);
        primaryStage.setMinWidth(1280);
        primaryStage.setMinHeight(720);
        primaryStage.setMaxWidth(1280);
        primaryStage.setMaxHeight(720);
        primaryStage.setResizable(false);
        primaryStage.show();
    }

    public static void main(String[] args) {
        launch(args);
    }
}