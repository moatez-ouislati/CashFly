package controllers;

import entities.Utilisateur;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.stage.Stage;
import services.UtilisateurCrud;

import java.util.Optional;

public class ProfilMembreController {

    @FXML private Label nomUtilisateur;

    @FXML private TextField tfid;
    @FXML private TextField tfcin;
    @FXML private TextField tfnum_tel;
    @FXML private TextField tfnom;
    @FXML private TextField tfprenom;
    @FXML private TextField tfemail;

    @FXML private RadioButton rbAdmin;
    @FXML private RadioButton rbInvestisseur;
    @FXML private RadioButton rbProprietaire;

    @FXML private Button btn_enregismodif;
    @FXML private Button btn_deconn;

    private Utilisateur membre;
    private final UtilisateurCrud crud = new UtilisateurCrud();

    // ================= INIT =================
    public void initData(Utilisateur u) {
        this.membre = u;

        tfid.setText(String.valueOf(u.getId()));
        tfcin.setText(String.valueOf(u.getCin()));
        tfnum_tel.setText(u.getTel());
        tfnom.setText(u.getNom());
        tfprenom.setText(u.getPrenom());
        tfemail.setText(u.getEmail());
        nomUtilisateur.setText(u.getNom());

        ToggleGroup tg = new ToggleGroup();
        rbAdmin.setToggleGroup(tg);
        rbInvestisseur.setToggleGroup(tg);
        rbProprietaire.setToggleGroup(tg);

        if (u.getRoles().contains("ROLE_ADMIN")) {
            rbAdmin.setSelected(true);
        } else if (u.getRoles().contains("ROLE_PROPRIETAIRE")) {
            rbProprietaire.setSelected(true);
        } else {
            rbInvestisseur.setSelected(true);
        }
    }

    // ================= UPDATE =================
    @FXML
    private void modifierProfil() {

        Alert alert = new Alert(Alert.AlertType.CONFIRMATION,
                "Modifier ce profil ?", ButtonType.OK, ButtonType.CANCEL);

        Optional<ButtonType> res = alert.showAndWait();
        if (res.isEmpty() || res.get() != ButtonType.OK) return;

        membre.setCin(Integer.parseInt(tfcin.getText()));
        membre.setTel(tfnum_tel.getText());
        membre.setNom(tfnom.getText());
        membre.setPrenom(tfprenom.getText());
        membre.setEmail(tfemail.getText());

        // ROLE
        if (rbAdmin.isSelected()) {
            membre.setRoles("[\"ROLE_ADMIN\"]");
        } else if (rbProprietaire.isSelected()) {
            membre.setRoles("[\"ROLE_PROPRIETAIRE\"]");
        } else {
            membre.setRoles("[\"ROLE_INVESTISSEUR\"]");
        }

        crud.modifier(membre);

        new Alert(Alert.AlertType.INFORMATION,
                "Profil membre mis à jour", ButtonType.OK).showAndWait();

        close();
    }

    // ================= CLOSE =================
    @FXML
    public void close() {
        ((Stage) btn_deconn.getScene().getWindow()).close();
    }
}
