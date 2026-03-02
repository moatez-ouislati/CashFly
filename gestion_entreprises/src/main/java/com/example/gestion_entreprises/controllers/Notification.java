package com.example.gestion_entreprises.controllers;

import javafx.css.PseudoClass;
import javafx.geometry.Pos;
import javafx.scene.image.ImageView;
import javafx.scene.layout.BorderPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.StackPane;
import javafx.scene.text.Text;
import javafx.scene.text.TextAlignment;

public class Notification extends StackPane {

    private final ImageView cross = new ImageView();
    private Runnable onRemove = () -> {};

    public Notification(String text, NotificationType type) {

        getStyleClass().add("notification");

        ImageView typeImage = new ImageView();
        typeImage.getStyleClass().add("note-type-icon");
        typeImage.pseudoClassStateChanged(
                PseudoClass.getPseudoClass(type.style()), true
        );

        cross.getStyleClass().add("notification-cross");
        cross.setFitWidth(10);
        cross.setFitHeight(10);

        BorderPane borderPane = new BorderPane();
        Text note = new Text(text);

        note.wrappingWidthProperty().bind(maxWidthProperty().subtract(42));
        note.setTextAlignment(TextAlignment.JUSTIFY);
        note.getStyleClass().add("note-text");

        borderPane.setCenter(note);
        BorderPane.setAlignment(cross, Pos.TOP_RIGHT);
        borderPane.setTop(cross);

        HBox hBox = new HBox(typeImage, borderPane);
        hBox.setAlignment(Pos.CENTER);
        hBox.setSpacing(5.0);

        getChildren().add(hBox);

        initialize();
    }

    private void initialize("") {
        cross.getStyleClass().add("small-hyperlink-label");
        cross.setOnMouseClicked(e -> onRemove.run());
    }

    public void setOnRemove(Runnable onRemove) {
        this.onRemove = onRemove;
        //commenttesteeee
    }
}