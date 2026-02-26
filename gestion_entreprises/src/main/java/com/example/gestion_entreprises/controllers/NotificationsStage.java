package com.example.gestion_entreprises.controllers;

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

        setStyle("""
                -fx-background-color: rgba(20,20,20,0.95);
                -fx-padding: 20;
                """);

        Label title = new Label("Notifications");
        title.setStyle("""
                -fx-text-fill: white;
                -fx-font-size: 18px;
                -fx-font-weight: bold;
                """);

        Button closeBtn = new Button("✖");
        closeBtn.setStyle("""
                -fx-background-color: transparent;
                -fx-text-fill: white;
                -fx-font-size: 14px;
                """);

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
        message.setStyle("-fx-text-fill: white;");

        Label dateLabel = new Label(date);
        dateLabel.setStyle("-fx-text-fill: #aaaaaa; -fx-font-size: 10px;");

        VBox noteBox = new VBox(5, message, dateLabel);
        noteBox.setStyle("""
                -fx-background-color: #2c2c2c;
                -fx-padding: 10;
                -fx-background-radius: 8;
                """);

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