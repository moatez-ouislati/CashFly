package tn.cashfly.utils;

import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;

import java.io.IOException;
import java.net.URL;

public class NavigationUtil {
    public static final int WIDTH = 1280;
    public static final int HEIGHT = 720;
    private static Stage currentStage;

    public static void setCurrentStage(Stage stage) {
        currentStage = stage;
    }

    public static Stage getCurrentStage() {
        return currentStage;
    }

    public static void navigateTo(Stage stage, String fxml) throws IOException {
        setCurrentStage(stage);

        URL resource = NavigationUtil.class.getResource("/tn/cashfly/" + fxml);
        if (resource == null) {
            throw new IOException("Cannot find resource: /tn/cashfly/" + fxml);
        }

        FXMLLoader loader = new FXMLLoader(resource);
        Parent root = loader.load();

        Scene scene = new Scene(root, WIDTH, HEIGHT);

        URL cssResource = NavigationUtil.class.getResource("/tn/cashfly/Styles/custom.css");
        if (cssResource != null) {
            scene.getStylesheets().add(cssResource.toExternalForm());
        }

        stage.setScene(scene);
        stage.setMinWidth(WIDTH);
        stage.setMinHeight(HEIGHT);
        stage.setMaxWidth(WIDTH);
        stage.setMaxHeight(HEIGHT);
        stage.setResizable(false);
    }
}