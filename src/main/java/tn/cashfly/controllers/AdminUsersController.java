package tn.cashfly.controllers;

import tn.cashfly.entities.Utilisateur;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.scene.layout.VBox;
import tn.cashfly.services.UtilisateurCrud;
import tn.cashfly.tools.Session;
import tn.cashfly.services.FaceService;
import tn.cashfly.entities.MdpHash;

import com.lowagie.text.Document;
import com.lowagie.text.Paragraph;
import com.lowagie.text.pdf.PdfWriter;
import java.io.FileOutputStream;
import javafx.scene.control.Alert;

public class AdminUsersController {

    @FXML private ListView<Utilisateur> lvUsers;
    @FXML private TextField tfSearch, tfCin, tfTel, tfNom, tfPrenom, tfEmail;
    @FXML private PasswordField tfPassword;
    @FXML private ComboBox<String> cbRole, cbFilterRole;
    @FXML private Button btnAdd, btnUpdate, btnDelete, btnRefresh, btnChangePass, btnRegisterFace;

    private final UtilisateurCrud crud = new UtilisateurCrud();
    private final FaceService faceService = new FaceService();
    private ObservableList<Utilisateur> allUsers;
    private Utilisateur selectedUser;

    @FXML
    public void initialize() {
        cbRole.setItems(FXCollections.observableArrayList("investisseur", "proprietaire"));
        cbFilterRole.setItems(FXCollections.observableArrayList("ALL", "investisseur", "proprietaire"));
        cbFilterRole.setValue("ALL");

        loadUsers();

        lvUsers.setCellFactory(lv -> new ListCell<>() {
            @Override
            protected void updateItem(Utilisateur u, boolean empty) {
                super.updateItem(u, empty);
                if (empty || u == null) {
                    setGraphic(null);
                } else {
                    VBox box = new VBox(
                            4,
                            new Label("👤 " + u.getNom().toUpperCase() + " " + u.getPrenom()),
                            new Label("🪪 CIN : " + u.getCin()),
                            new Label("📧 " + u.getEmail()),
                            new Label("🎭 " + u.getRoles())
                    );
                    box.setStyle("-fx-padding: 10; -fx-background-color: #f8fafc; -fx-background-radius: 5;");
                    setGraphic(box);
                }
            }
        });

        lvUsers.getSelectionModel().selectedItemProperty().addListener((o, a, n) -> {
            if (n != null) {
                selectedUser = n;
                fillFields(n);
            }
        });

        tfSearch.textProperty().addListener((o, a, n) -> applyFilters());
        cbFilterRole.setOnAction(e -> applyFilters());

        btnAdd.setOnAction(e -> ajouter());
        btnUpdate.setOnAction(e -> modifier());
        btnDelete.setOnAction(e -> supprimer());
        btnRefresh.setOnAction(e -> refreshAll());
        btnChangePass.setOnAction(e -> changerMotDePasse());
    }

    private void loadUsers() {
        allUsers = FXCollections.observableArrayList(crud.afficher());
        lvUsers.setItems(allUsers);
    }

    private void refreshAll() {
        loadUsers();
        tfSearch.clear();
        cbFilterRole.setValue("ALL");
    }

    private void applyFilters() {
        String k = tfSearch.getText().toLowerCase();
        String r = cbFilterRole.getValue();

        lvUsers.setItems(allUsers.filtered(u ->
                (k.isEmpty() || u.getEmail().toLowerCase().contains(k)) &&
                        (r.equals("ALL") || u.hasRole(r))
        ));
    }

    private void fillFields(Utilisateur u) {
        tfCin.setText(String.valueOf(u.getCin()));
        tfTel.setText(u.getTel());
        tfNom.setText(u.getNom());
        tfPrenom.setText(u.getPrenom());
        tfEmail.setText(u.getEmail());
        cbRole.setValue(u.isProprietaire() ? "proprietaire" : "investisseur");
    }

    @FXML
    private void ajouter() {
        try {
            Utilisateur u = new Utilisateur();
            u.setCin(Integer.parseInt(tfCin.getText()));
            u.setTel(tfTel.getText());
            u.setNom(tfNom.getText());
            u.setPrenom(tfPrenom.getText());
            u.setEmail(tfEmail.getText());
            u.setPassword(tfPassword.getText());
            u.setRoles(cbRole.getValue());

            crud.ajouter(u);
            showAlert("Succès", "Utilisateur ajouté");
            refreshAll();
        } catch (Exception e) {
            showAlert("Erreur", "Champs invalides");
        }
    }

    @FXML
    private void modifier() {
        if (selectedUser == null) return;
        selectedUser.setCin(Integer.parseInt(tfCin.getText()));
        selectedUser.setTel(tfTel.getText());
        selectedUser.setNom(tfNom.getText());
        selectedUser.setPrenom(tfPrenom.getText());
        selectedUser.setEmail(tfEmail.getText());
        selectedUser.setRoles(cbRole.getValue());
        crud.modifier(selectedUser);
        showAlert("Succès", "Utilisateur modifié");
        refreshAll();
    }

    @FXML
    private void supprimer() {
        if (selectedUser == null) return;
        crud.supprimer(selectedUser.getId());
        showAlert("Supprimé", "Utilisateur supprimé");
        refreshAll();
    }

    @FXML
    private void changerMotDePasse() {
        if (selectedUser == null || tfPassword.getText().isBlank()) return;
        crud.updatePasswordByEmail(selectedUser.getEmail(), MdpHash.hashPassword(tfPassword.getText()));
        tfPassword.clear();
        showAlert("Succès", "Mot de passe changé");
    }

    @FXML
    private void blockUser() {
        if (selectedUser == null) return;
        crud.blockUser(selectedUser.getId());
        showAlert("Bloqué", "Utilisateur bloqué");
        refreshAll();
    }

    @FXML
    private void unblockUser() {
        if (selectedUser == null) return;
        crud.unblockUser(selectedUser.getId());
        showAlert("Débloqué", "Utilisateur débloqué");
        refreshAll();
    }

    @FXML
    private void handleRegisterFace() {
        int adminId = Session.getCurrentUser().getId();
        boolean success = faceService.detectAndSave(adminId);
        if (success) {
            String imagePath = "src/main/resources/faces/admin_" + adminId + ".jpg";
            crud.updateFaceImage(adminId, imagePath);
            Session.getCurrentUser().setFaceImage(imagePath);
            showAlert("Succès", "Visage enregistré !");
        } else {
            showAlert("Erreur", "Impossible de détecter un visage.");
        }
    }

    @FXML
    private void exportPdf() {
        try {
            String path = System.getProperty("user.home") + "/Desktop/cashfly_users.pdf";
            Document document = new Document();
            PdfWriter.getInstance(document, new FileOutputStream(path));
            document.open();
            document.add(new Paragraph("CASHFLY - Liste des Utilisateurs\n\n"));
            for (Utilisateur u : lvUsers.getItems()) {
                document.add(new Paragraph("Nom : " + u.getNom() + " " + u.getPrenom() + " | Email : " + u.getEmail() + " | Rôle : " + u.getRoles()));
            }
            document.close();
            showAlert("PDF", "Export effectué sur le Bureau ✔");
        } catch (Exception e) {
            showAlert("Erreur", "Erreur export PDF");
        }
    }

    private void showAlert(String title, String content) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(content);
        alert.showAndWait();
    }
}
