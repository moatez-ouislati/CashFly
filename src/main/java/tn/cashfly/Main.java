package tn.cashfly;

import atlantafx.base.theme.PrimerLight;
import javafx.application.Application;
import javafx.stage.Stage;
import tn.cashfly.utils.NavigationUtil;

public class Main extends Application {

    @Override
    public void start(Stage primaryStage) throws Exception {
        // Set AtlantaFX theme
        Application.setUserAgentStylesheet(new PrimerLight().getUserAgentStylesheet());

        // Initialize NavigationUtil with primary stage
        NavigationUtil.setCurrentStage(primaryStage);

        // Initialize SceneManager with primary stage
        tn.cashfly.tools.SceneManager.setStage(primaryStage);

        // Load new Authentication screen
        tn.cashfly.tools.SceneManager.switchScene("tn/cashfly/authentification.fxml");
    }

    public static void main(String[] args) {
        launch(args);
    }
}