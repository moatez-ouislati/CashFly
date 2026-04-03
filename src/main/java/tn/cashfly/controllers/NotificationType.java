package tn.cashfly.controllers;

public enum NotificationType {

    INFO("Info", "info"),
    ERROR("Erreur", "error"),
    WARNING("Warning", "warning");

    private final String text;
    private final String style;

    NotificationType(String text, String style) {
        this.text = text;
        this.style = style;
    }

    public String text() {
        return text;
    }

    public String style() {
        return style;
    }
}
