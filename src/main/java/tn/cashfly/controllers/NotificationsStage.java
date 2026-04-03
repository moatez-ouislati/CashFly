package tn.cashfly.controllers;

import javafx.animation.TranslateTransition;
import javafx.geometry.Pos;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.*;
import javafx.util.Duration;

import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

public class NotificationsStage extends StackPane {

    private final VBox notesBox = new VBox(10);
    private boolean open = false;
    private static final double WIDTH = 320;

    public NotificationsStage() {

        setPrefWidth(WIDTH);
        setMinWidth(WIDTH);
        setMaxWidth(WIDTH);

        getStyleClass().add("notification-panel");

        Label title = new Label("Notifications");
        title.getStyleClass().add("notification-title");
        title.setStyle("-fx-font-size: 18px;");

        Button closeBtn = new Button("✖");
        closeBtn.setStyle("-fx-background-color: transparent; -fx-text-fill: white; -fx-font-size: 14px; -fx-cursor: hand;");

        closeBtn.setOnAction(e -> toggle());

        HBox header = new HBox(title, closeBtn);
        header.setAlignment(Pos.CENTER);
        HBox.setHgrow(title, Priority.ALWAYS);

        notesBox.setAlignment(Pos.TOP_CENTER);

        VBox container = new VBox(20, header, notesBox);
        container.setAlignment(Pos.TOP_CENTER);

        getChildren().add(container);

        setTranslateX(WIDTH);
        StackPane.setAlignment(this, Pos.CENTER_RIGHT);
    }

    public void addNote(String text, NotificationType type) {

        DateTimeFormatter formatter =
                DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm");

        String date = LocalDateTime.now().format(formatter);

        Label message = new Label(text);
        message.setWrapText(true);
        message.setMaxWidth(260);
        message.getStyleClass().add("notification-title");
        message.setStyle("-fx-font-weight: normal;");

        Label dateLabel = new Label(date);
        dateLabel.getStyleClass().add("notification-date");

        VBox noteBox = new VBox(5, message, dateLabel);
        noteBox.getStyleClass().add("notification-item");

        notesBox.getChildren().add(0, noteBox);
    }

    public void toggle() {
        if (open) close();
        else open();
    }

    private void open() {
        TranslateTransition tt = new TranslateTransition(Duration.millis(250), this);
        tt.setToX(0);
        tt.play();
        open = true;
    }

    private void close() {
        TranslateTransition tt = new TranslateTransition(Duration.millis(250), this);
        tt.setToX(WIDTH);
        tt.play();
        open = false;
    }
}
