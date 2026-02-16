package com.example.gestion_entreprises.controllers;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.Node;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;

import java.io.IOException;

public class MainController {

    // ===================== NAVIGATION =====================

    @FXML
    private void goToEntreprises(ActionEvent event) {
        changeScreen(event,
                "/com/example/gestion_entreprises/AjouterEntreprise.fxml",
                "Gestion des Entreprises");
    }

    @FXML
    private void goToDocuments(ActionEvent event) {
        changeScreen(event,
                "/com/example/gestion_entreprises/AjouterDocument.fxml",
                "Gestion des Documents");
    }

    @FXML
    private void afficherEntreprises(ActionEvent event) {
        changeScreen(event,
                "/com/example/gestion_entreprises/AfficherEntreprise.fxml",
                "Liste des Entreprises");
    }

    @FXML
    private void afficherDocuments(ActionEvent event) {
        changeScreen(event,
                "/com/example/gestion_entreprises/AfficherDocument.fxml",
                "Liste des Documents");
    }

    @FXML
    private void exitApp(ActionEvent event) {
        System.exit(0);
    }

    // ===================== SCREEN CHANGER =====================



    private void changeScreen(ActionEvent event, String fxmlPath, String title) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource(fxmlPath));
            Parent root = loader.load();

            Stage stage = (Stage) ((Node) event.getSource())
                    .getScene()
                    .getWindow();

            stage.setTitle(title);
            stage.setScene(new Scene(root));
            stage.show();

        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
