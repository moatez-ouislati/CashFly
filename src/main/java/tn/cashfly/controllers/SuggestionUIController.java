package tn.cashfly.controllers;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.control.Alert;
import javafx.scene.control.ListView;
import javafx.scene.control.TextArea;
import javafx.scene.control.TextField;

import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

public class SuggestionUIController {

    @FXML
    private TextField titleField;
    @FXML
    private TextArea messageField;
    @FXML
    private ListView<String> suggestionsList;

    private final ObservableList<String> suggestions = FXCollections.observableArrayList();

    @FXML
    public void initialize() {
        suggestionsList.setItems(suggestions);
    }

    @FXML
    private void onSend() {
        String title = titleField.getText() == null ? "" : titleField.getText().trim();
        String msg = messageField.getText() == null ? "" : messageField.getText().trim();
        if (title.isEmpty() || msg.isEmpty()) {
            Alert a = new Alert(Alert.AlertType.INFORMATION);
            a.setHeaderText(null);
            a.setContentText("Veuillez compléter le titre et le message.");
            a.showAndWait();
            return;
        }
        String time = LocalDateTime.now().format(DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm"));
        String item = time + " • " + title + " — " + (msg.length() > 60 ? msg.substring(0, 60) + "…" : msg);
        suggestions.add(0, item);
        titleField.clear();
        messageField.clear();
        Alert a = new Alert(Alert.AlertType.INFORMATION);
        a.setHeaderText(null);
        a.setContentText("Merci pour votre suggestion.");
        a.showAndWait();
    }
}

