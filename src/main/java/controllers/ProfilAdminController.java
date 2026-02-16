package controllers;

import entities.Utilisateur;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.stage.Stage;
import services.UtilisateurCrud;

public class ProfilAdminController {

    @FXML private Label nomUtilisateur;

    @FXML private TextField tfid, tfcin, tfnum_tel, tfnom, tfprenom, tfemail;
    @FXML private PasswordField tfmdp;

    @FXML private RadioButton rbAdmin, rbInvestisseur, rbProprietaire;
    @FXML private Button btn_enregismodif, btn_annul;

    private Utilisateur utilisateur;
    private final UtilisateurCrud crud = new UtilisateurCrud();
    private ToggleGroup rolesGroup;

    // 🔥 تُستدعى من ListeUtilisateursController
    public void initData(Utilisateur u) {

        System.out.println("OPEN PROFIL ADMIN");

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
        rolesGroup = new ToggleGroup();
        rbAdmin.setToggleGroup(rolesGroup);
        rbInvestisseur.setToggleGroup(rolesGroup);
        rbProprietaire.setToggleGroup(rolesGroup);

        if (u.getRoles().contains("ROLE_ADMIN"))
            rbAdmin.setSelected(true);
        else if (u.getRoles().contains("ROLE_PROPRIETAIRE"))
            rbProprietaire.setSelected(true);
        else
            rbInvestisseur.setSelected(true);
    }

    @FXML
    private void modifierProfil() {

        utilisateur.setCin(Integer.parseInt(tfcin.getText()));
        utilisateur.setTel(tfnum_tel.getText());
        utilisateur.setNom(tfnom.getText());
        utilisateur.setPrenom(tfprenom.getText());
        utilisateur.setEmail(tfemail.getText());

        if (rbAdmin.isSelected())
            utilisateur.setRoles("[\"ROLE_ADMIN\"]");
        else if (rbProprietaire.isSelected())
            utilisateur.setRoles("[\"ROLE_PROPRIETAIRE\"]");
        else
            utilisateur.setRoles("[\"ROLE_INVESTISSEUR\"]");

        if (!tfmdp.getText().isEmpty()) {
            crud.changerMotDePasse(utilisateur.getId(), tfmdp.getText());
        }

        crud.modifier(utilisateur);

        new Alert(Alert.AlertType.INFORMATION,
                "Profil modifié avec succès",
                ButtonType.OK).showAndWait();

        ((Stage) btn_enregismodif.getScene().getWindow()).close();
    }

    @FXML
    private void annuler() {
        ((Stage) btn_annul.getScene().getWindow()).close();
    }
}
