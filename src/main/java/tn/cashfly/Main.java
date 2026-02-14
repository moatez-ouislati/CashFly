package tn.cashfly;

import atlantafx.base.theme.PrimerLight;  // or PrimerDark
import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;

public class Main extends Application {

    @Override
    public void start(Stage primaryStage) throws Exception {
        // 1. Set AtlantaFX theme
        Application.setUserAgentStylesheet(new PrimerLight().getUserAgentStylesheet());

        // 2. Load FXML
        Parent root = FXMLLoader.load(getClass().getResource("/tn/cashfly/MainJPO.fxml"));
        Scene scene = new Scene(root);

        // 3. ADD THIS LINE - Load your custom CSS
        scene.getStylesheets().add(getClass().getResource("/tn/cashfly/Styles/custom.css").toExternalForm());

        primaryStage.setTitle("CashFly - JPO Management");
        primaryStage.setScene(scene);
        primaryStage.show();
    }

    public static void main(String[] args) {
        launch(args);
    }
}