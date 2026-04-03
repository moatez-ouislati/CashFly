package tn.cashfly.controllers;

import tn.cashfly.entities.SmsSender;
import tn.cashfly.entities.Utilisateur;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.stage.Stage;
import tn.cashfly.services.UtilisateurCrud;
import tn.cashfly.tools.TranslationService;
import tn.cashfly.tools.LanguageService;

import tn.cashfly.services.KycService;
import tn.cashfly.tools.SceneManager;
import tn.cashfly.tools.Session;
import javafx.stage.Modality;
import javafx.scene.Parent;
import java.net.URL;

public class InscriptionController {

    @FXML private TextField tfcin, tfnum_tel, tfnom, tfprenom, tfemail;
    @FXML private PasswordField tfmdp;
    @FXML private TextField tfshowpassword;
    @FXML private CheckBox show;

    @FXML private RadioButton rbInvestisseur, rbProprietaire;
    @FXML private Button btn_inscri;
    @FXML private Hyperlink hyperlink;

    @FXML private Label titleLabel, subtitleLabel;
    @FXML private MenuButton btnLang;

    private final UtilisateurCrud utilisateurCrud = new UtilisateurCrud();
    private KycService kycService;
    private static final String ADMIN_PHONE = "+21626887560";

    @FXML
    private void initialize() {
        try {
            kycService = new KycService();
        } catch (Exception e) {
            System.err.println("Avertissement: Impossible d'initialiser le service KYC: " + e.getMessage());
        }

        // 🔹 password toggle
        tfshowpassword.setVisible(false);
        show.setOnAction(e -> togglePassword());

        // 🔹 roles
        ToggleGroup group = new ToggleGroup();
        rbInvestisseur.setToggleGroup(group);
        rbProprietaire.setToggleGroup(group);
        rbInvestisseur.setSelected(true);

        // 🔹 apply saved language
        translatePage(LanguageService.getLang());
    }

    // ===== 🌍 LANG =====
    @FXML
    private void setFrench() {
        LanguageService.setLang("fr");
        btnLang.setText("🇫🇷 Français");
        translatePage("fr");
    }

    @FXML
    private void setEnglish() {
        LanguageService.setLang("en");
        btnLang.setText("🇬🇧 English");
        translatePage("en");
    }

    @FXML
    private void setArabic() {
        LanguageService.setLang("ar");
        btnLang.setText("🇹🇳 العربية");
        translatePage("ar");
    }

    private void translatePage(String lang) {

        titleLabel.setText(TranslationService.translate("Inscription", lang));
        subtitleLabel.setText(TranslationService.translate("Créez votre compte", lang));

        tfcin.setPromptText(TranslationService.translate("CIN", lang));
        tfnum_tel.setPromptText(TranslationService.translate("Numéro téléphone", lang));
        tfnom.setPromptText(TranslationService.translate("Nom", lang));
        tfprenom.setPromptText(TranslationService.translate("Prénom", lang));
        tfemail.setPromptText(TranslationService.translate("Adresse email", lang));
        tfmdp.setPromptText(TranslationService.translate("Mot de passe", lang));

        show.setText(TranslationService.translate("Voir mot de passe", lang));
        rbInvestisseur.setText(TranslationService.translate("Investisseur", lang));
        rbProprietaire.setText(TranslationService.translate("Propriétaire", lang));
        btn_inscri.setText(TranslationService.translate("S'inscrire", lang));
        hyperlink.setText(TranslationService.translate("Vous avez déjà un compte ?", lang));
    }

    // ===== SAVE =====
    @FXML
    private void savePerson() {

        String cinStr = tfcin.getText().trim();
        String tel = tfnum_tel.getText().trim();
        String nom = tfnom.getText().trim();
        String prenom = tfprenom.getText().trim();
        String email = tfemail.getText().trim();
        String password = tfmdp.getText();

        if (cinStr.isEmpty() || tel.isEmpty() || nom.isEmpty()
                || prenom.isEmpty() || email.isEmpty() || password.isEmpty()) {
            alert("Veuillez remplir tous les champs");
            return;
        }

        if (!tn.cashfly.tools.Validation.isCIN(cinStr)) {
            alert("CIN invalide: commence par 0 ou 1 et contient 8 chiffres");
            return;
        }

        if (!tn.cashfly.tools.Validation.isPhone8(tel)) {
            alert("Numéro téléphone invalide, contient 8 chiffres");
            return;
        }

        if (!tn.cashfly.tools.Validation.isEmail(email)) {
            alert("Email invalide");
            return;
        }

        if (!tn.cashfly.tools.Validation.strongPassword(password)) {
            alert("Mot de passe faible: 8+ caractères, majuscule, minuscule, chiffre, symbole");
            return;
        }

        int cin = Integer.parseInt(cinStr);

        if (utilisateurCrud.utilisateurExiste(cin, email)) {
            alert("Utilisateur existe déjà");
            return;
        }

        String roleLabel;
        String role;

        if (rbProprietaire.isSelected()) {
            role = "proprietaire";
            roleLabel = "Propriétaire";
        } else {
            role = "investisseur";
            roleLabel = "Investisseur";
        }

        Utilisateur u = new Utilisateur();

        u.setCin(cin);
        u.setTel(tel);
        u.setNom(nom);
        u.setPrenom(prenom);
        u.setEmail(email);
        u.setPassword(password);
        u.setRoles(role);
        utilisateurCrud.ajouter(u);

        String smsContent =
                "CASHFLY - Nouveau compte créé\n"
                        + "CIN: " + cin + "\n"
                        + "Nom: " + nom + " " + prenom + "\n"
                        + "Email: " + email + "\n"
                        + "Tel: " + tel + "\n"
                        + "Role: " + roleLabel;

        SmsSender.sendSms(ADMIN_PHONE, smsContent);

        alert("Inscription réussie. Bienvenue sur CASHFLY !");
        
        // AUTO-LOGIN: Find the created user to get the full object (including ID)
        Utilisateur user = utilisateurCrud.findByEmail(email);
        if (user != null) {
            showCaptchaPopup(user);
        } else {
            redirect("/authentification.fxml");
        }
    }

    private void showCaptchaPopup(Utilisateur user) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/captcha_popup.fxml"));
            Parent root = loader.load();
            CaptchaPopupController controller = loader.getController();
            controller.setOnSuccess(() -> finishLogin(user));

            Stage stage = new Stage();
            stage.setTitle("Vérification CAPTCHA");
            stage.initModality(Modality.APPLICATION_MODAL);
            stage.setScene(new Scene(root));
            stage.showAndWait();
        } catch (Exception e) {
            e.printStackTrace();
            alert("Erreur lors de la vérification CAPTCHA.");
            redirect("/authentification.fxml");
        }
    }

    private void finishLogin(Utilisateur user) {
        Session.setCurrentUser(user);
        
        // Check KYC status
        boolean kycEnrolled = false;
        if (kycService != null) {
            try {
                kycEnrolled = kycService.getKycByUserId(user.getId()) != null;
            } catch (Exception e) {
                System.err.println("Erreur vérification KYC DB: " + e.getMessage());
            }
        }

        // ✅ Synchronize with UserSession used by PME Dashboard
        tn.cashfly.session.UserSession.setUser(
            user.getId(), 
            user.getNom() + " " + user.getPrenom(), 
            user.getEmail(), 
            user.getRoles(), 
            kycEnrolled
        );

        if (user.isAdmin()) {
            SceneManager.switchScene("AdminDashboard.fxml");
            return;
        }

        // Check if KYC is required for non-admins
        if (!kycEnrolled) {
            openKYCEnrollment(user);
            return;
        }

        if (user.isProprietaire()) {
            SceneManager.switchScene("dashboard.fxml");
        } else {
            SceneManager.switchScene("InvestisseurDashboard.fxml");
        }
    }

    private void openKYCEnrollment(Utilisateur user) {
        try {
            URL fxmlUrl = getClass().getResource("/kyc_modal.fxml");
            if (fxmlUrl == null) {
                alert("Fichier FXML introuvable : /kyc_modal.fxml");
                return;
            }
            FXMLLoader loader = new FXMLLoader(fxmlUrl);
            Parent root = loader.load();
            
            KYCController kycController = loader.getController();
            
            // Get stage from UI element
            Stage stage = (Stage) btn_inscri.getScene().getWindow();
            stage.setTitle("Enrôlement KYC Obligatoire - Cashfly");
            stage.setScene(new Scene(root));
            
            // Ensure webcam closure
            stage.setOnCloseRequest(e -> {
                if (kycController != null) {
                    kycController.cleanup();
                }
            });
            
            // On successful KYC enrollment
            stage.setOnHidden(e -> {
                if (KYCController.isVerified()) {
                    tn.cashfly.session.UserSession.setKycEnrolled(true);
                    if (user.isProprietaire()) {
                        SceneManager.switchScene("dashboard.fxml");
                    } else {
                        SceneManager.switchScene("InvestisseurDashboard.fxml");
                    }
                } else {
                    if (kycController != null) kycController.cleanup();
                    alert("Vous devez compléter le KYC pour accéder à votre compte.");
                    SceneManager.switchScene("authentification.fxml");
                }
            });
            
            stage.show();
        } catch (Exception e) {
            e.printStackTrace();
            alert("Erreur lors du chargement du KYC.");
        }
    }

    @FXML
    private void redirectToAuthPage() {
        redirect("/authentification.fxml");
    }

    private void redirect(String fxml) {
        try {
            Stage stage = (Stage) btn_inscri.getScene().getWindow();
            stage.setScene(new Scene(
                    FXMLLoader.load(getClass().getResource(fxml))
            ));
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    // ✅ THIS WAS MISSING
    private void togglePassword() {
        if (show.isSelected()) {
            tfshowpassword.setText(tfmdp.getText());
            tfshowpassword.setVisible(true);
            tfmdp.setVisible(false);
        } else {
            tfmdp.setText(tfshowpassword.getText());
            tfmdp.setVisible(true);
            tfshowpassword.setVisible(false);
        }
    }

    private void alert(String msg) {
        new Alert(Alert.AlertType.INFORMATION, msg, ButtonType.OK).show();
    }
}
