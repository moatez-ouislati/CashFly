package controllers;

import entities.Utilisateur;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.stage.Stage;
import services.UtilisateurCrud;

public class ProfilPropController {

    @FXML private Label nomUtilisateur;

    @FXML private TextField tfid;
    @FXML private TextField tfcin;
    @FXML private TextField tfnum_tel;
    @FXML private TextField tfnom;
    @FXML private TextField tfprenom;
    @FXML private TextField tfemail;
    @FXML private PasswordField tfmdp;

    @FXML private RadioButton rbUser;
    @FXML private RadioButton rbInvestisseur;
    @FXML private RadioButton rbProprietaire;

    @FXML private Button btn_enregismodif;
    @FXML private Button btn_annul;

    private Utilisateur utilisateur;
    private final UtilisateurCrud crud = new UtilisateurCrud();

    // =========================
    // INIT DATA (OBLIGATOIRE)
    // =========================
    public void initData(Utilisateur u) {
        this.utilisateur = u;

        tfid.setText(String.valueOf(u.getId()));
        tfcin.setText(String.valueOf(u.getCin()));
        tfnum_tel.setText(u.getTel());
        tfnom.setText(u.getNom());
        tfprenom.setText(u.getPrenom());
        tfemail.setText(u.getEmail());
        tfmdp.clear();

        nomUtilisateur.setText(u.getNom());

        // ToggleGroup
        ToggleGroup tg = new ToggleGroup();
        rbUser.setToggleGroup(tg);
        rbInvestisseur.setToggleGroup(tg);
        rbProprietaire.setToggleGroup(tg);

        if (u.getRoles().contains("ROLE_ADMIN")) {
            rbUser.setSelected(true);
        } else if (u.getRoles().contains("ROLE_PROPRIETAIRE")) {
            rbProprietaire.setSelected(true);
        } else {
            rbInvestisseur.setSelected(true);
        }
    }

    @FXML
    private void modifierProfil() {

        if (utilisateur == null) return;

        utilisateur.setCin(Integer.parseInt(tfcin.getText()));
        utilisateur.setTel(tfnum_tel.getText());
        utilisateur.setNom(tfnom.getText());
        utilisateur.setPrenom(tfprenom.getText());
        utilisateur.setEmail(tfemail.getText());

        // ROLE
        if (rbUser.isSelected()) {
            utilisateur.setRoles("[\"ROLE_ADMIN\"]");
        } else if (rbProprietaire.isSelected()) {
            utilisateur.setRoles("[\"ROLE_PROPRIETAIRE\"]");
        } else {
            utilisateur.setRoles("[\"ROLE_INVESTISSEUR\"]");
        }

        if (!tfmdp.getText().isEmpty()) {
            crud.changerMotDePasse(utilisateur.getId(), tfmdp.getText());
        }

        crud.modifier(utilisateur);

        new Alert(Alert.AlertType.INFORMATION,
                "Profil modifié avec succès",
                ButtonType.OK).showAndWait();

        close();
    }

    @FXML
    private void annuler() {
        close();
    }

    private void close() {
        ((Stage) btn_annul.getScene().getWindow()).close();
    }
}
