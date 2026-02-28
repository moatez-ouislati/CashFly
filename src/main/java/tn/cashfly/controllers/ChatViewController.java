package tn.cashfly.controllers;

import javafx.application.Platform;
import javafx.concurrent.Worker;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.web.WebEngine;
import javafx.scene.web.WebView;
import tn.cashfly.entities.JPO;
import tn.cashfly.entities.Utilisateur;
import tn.cashfly.utils.SessionManager;

import java.net.URL;
import java.util.ResourceBundle;

public class ChatViewController implements Initializable {

    @FXML private WebView chatWebView;

    private WebEngine webEngine;
    private JPO event;
    private Utilisateur currentUser;
    private boolean isWebViewReady = false;
    private boolean isEventSet = false;

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        currentUser = SessionManager.getCurrentUser();

        if (chatWebView == null || currentUser == null) {
            return;
        }

        webEngine = chatWebView.getEngine();
        webEngine.setJavaScriptEnabled(true);

        webEngine.getLoadWorker().stateProperty().addListener((obs, old, newState) -> {
            if (newState == Worker.State.SUCCEEDED) {
                isWebViewReady = true;
                tryInitChat();
            }
        });

        loadChatHtml();
    }

    private void loadChatHtml() {
        URL chatUrl = getClass().getResource("/tn/cashfly/chat.html");
        if (chatUrl != null) {
            webEngine.load(chatUrl.toExternalForm());
        }
    }

    public void setEvent(JPO event) {
        this.event = event;
        this.isEventSet = true;
        tryInitChat();
    }

    private void tryInitChat() {
        if (!isWebViewReady || !isEventSet || event == null) {
            return;
        }

        String authData = String.format(
                "{userId: %d, userName: '%s', fullName: '%s', userRole: '%s', eventId: %d}",
                currentUser.getIdUtilisateur(),
                escapeJs(currentUser.getNomComplet()),
                escapeJs(currentUser.getNomComplet()),
                currentUser.getRole(),
                event.getId_evenement()
        );

        Platform.runLater(() -> {
            webEngine.executeScript("initChat(" + authData + ");");
        });
    }

    private String escapeJs(String s) {
        if (s == null) return "";
        return s.replace("\\", "\\\\").replace("'", "\\'").replace("\"", "\\\"");
    }

    public void cleanup() {
        if (webEngine != null) {
            try {
                webEngine.executeScript("if(window.socket) window.socket.disconnect();");
            } catch (Exception e) {
                // Ignore
            }
        }
    }
}