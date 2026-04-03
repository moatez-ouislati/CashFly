package tn.cashfly.controllers;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.stage.Stage;
import javafx.scene.Node;

import java.time.LocalDate;

public class NotificationController {

    @FXML private ComboBox<String> noteType;
    @FXML private TextField textField;
    @FXML private DatePicker datePicker;

    @FXML
    public void initialize() {

        noteType.getItems().addAll("INFO", "WARNING", "ERROR");
        datePicker.setValue(LocalDate.now());
    }

    @FXML
    private void addNotification(ActionEvent event) {

        if (noteType.getValue() == null ||
                textField.getText().isEmpty() ||
                datePicker.getValue() == null) {

            Alert alert = new Alert(Alert.AlertType.ERROR);
            alert.setContentText("Tous les champs sont obligatoires.");
            alert.show();
            return;
        }

        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setHeaderText("Notification ajoutée");
        alert.setContentText(
                "Type: " + noteType.getValue() +
                        "\nMessage: " + textField.getText() +
                        "\nDate: " + datePicker.getValue()
        );
        alert.show();

        textField.clear();
    }

    @FXML
    private void retourMenu(ActionEvent event) {

        Stage stage = (Stage) ((Node) event.getSource())
                .getScene()
                .getWindow();

        stage.close();
    }
}
