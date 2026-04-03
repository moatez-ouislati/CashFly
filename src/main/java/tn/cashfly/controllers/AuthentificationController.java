package tn.cashfly.controllers;

import tn.cashfly.entities.Utilisateur;
import javafx.application.Platform;
import javafx.concurrent.Task;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.stage.Modality;
import javafx.stage.Stage;
import tn.cashfly.services.GoogleAuthService;
import tn.cashfly.services.UtilisateurCrud;
import tn.cashfly.tools.LanguageService;
import tn.cashfly.tools.TranslationService;
import tn.cashfly.tools.SceneManager;
import tn.cashfly.tools.Session;

import tn.cashfly.services.KycService;
import java.net.URL;

public class AuthentificationController {

    @FXML private TextField tfemail;
    @FXML private PasswordField tfmdp;
    @FXML private Button btn_auth;
    @FXML private Button btnGoogle;          // ← NOUVEAU bouton Google
    @FXML private Label lblMessage;
    @FXML private Hyperlink linkForgot, linkRegister;
    @FXML private Label titleLabel, subtitleLabel;

    private final UtilisateurCrud utilisateurCrud = new UtilisateurCrud();
    private KycService kycService;
    private int attempts = 0;
    private long lockUntil = 0L;
    private static final int MAX_ATTEMPTS = 5;
    private static final long LOCK_WINDOW_MS = 10 * 60 * 1000;
    private static final long SESSION_TIMEOUT_MILLIS = 30 * 60 * 1000; // 30 min
    private static final long REMEMBER_ME_TTL = 14L * 24 * 60 * 60 * 1000; // 14 jours

    // ================= INIT =================
    @FXML
    private void initialize() {
        Platform.runLater(this::autoLoginIfRemembered);
        try {
            kycService = new KycService();
        } catch (Exception e) {
            System.err.println("Avertissement: Impossible d'initialiser le service KYC: " + e.getMessage());
        }
        String lang = LanguageService.getLang();

        titleLabel.setText(TranslationService.translate("Authentification", lang));
        subtitleLabel.setText(TranslationService.translate("Content de te revoir !", lang));
        tfemail.setPromptText(TranslationService.translate("Adresse email", lang));
        tfmdp.setPromptText(TranslationService.translate("Mot de passe", lang));
        btn_auth.setText(TranslationService.translate("Se connecter", lang));
        linkForgot.setText(TranslationService.translate("Mot de passe oublié ?", lang));
        linkRegister.setText(TranslationService.translate("Retour à l'inscription", lang));

        lblMessage.setText("");
        linkForgot.setVisible(false);
    }

    // ================= LOGIN CLASSIQUE =================
    @FXML
    private void login() {
        String email = tfemail.getText().trim();
        String password = tfmdp.getText().trim();

        if (System.currentTimeMillis() < lockUntil) {
            long remaining = (lockUntil - System.currentTimeMillis()) / 1000;
            lblMessage.setText("Tentatives bloquées. Réessayez dans " + remaining + "s");
            return;
        }

        if (email.isEmpty() || password.isEmpty()) {
            lblMessage.setText("Veuillez remplir tous les champs");
            return;
        }

        Utilisateur user = utilisateurCrud.login(email, password);

        if (user == null) {
            attempts++;
            if (attempts >= MAX_ATTEMPTS) {
                lockUntil = System.currentTimeMillis() + LOCK_WINDOW_MS;
                lblMessage.setText("Trop de tentatives. Réessayez plus tard ou réinitialisez votre mot de passe.");
                btn_auth.setDisable(true);
                linkForgot.setVisible(true);
            } else {
                lblMessage.setText("Email ou mot de passe incorrect (" + attempts + "/" + MAX_ATTEMPTS + ")");
            }
            return;
        }

        if (user.getActive() == 0) {
            lblMessage.setText("Compte bloqué");
            return;
        }

        attempts = 0;
        lockUntil = 0L;
        btn_auth.setDisable(false);
        linkForgot.setVisible(true);
        showCaptchaPopup(user);
    }

    // ================= GOOGLE SIGN-IN =================
    @FXML
    private void loginWithGoogle() {
        // Désactiver le bouton pendant l'auth
        btnGoogle.setDisable(true);
        lblMessage.setText("Ouverture de Google...");

        // Lancer dans un thread séparé pour ne pas bloquer l'UI JavaFX
        Task<String> googleTask = new Task<>() {
            @Override
            protected String call() {
                // Cette méthode ouvre le navigateur et attend le callback
                return GoogleAuthService.signInWithGoogle();
            }
        };

        googleTask.setOnSucceeded(event -> {
            // On est de retour sur le JavaFX thread
            String email = googleTask.getValue();
            Platform.runLater(() -> {
                btnGoogle.setDisable(false);

                if (email == null) {
                    lblMessage.setText("❌ Connexion Google annulée ou échouée.");
                    return;
                }

                // Chercher l'email dans la base de données
                Utilisateur user = utilisateurCrud.findByEmail(email);

                if (user == null) {
                    // Email Google non trouvé dans la DB
                    lblMessage.setText("❌ Compte Google non enregistré dans CASHFLY.\n" +
                            "Veuillez d'abord vous inscrire avec : " + email);
                    return;
                }

                if (user.getActive() == 0) {
                    lblMessage.setText("⛔ Compte bloqué. Contactez l'administrateur.");
                    return;
                }

                // ✅ Email trouvé → login direct (pas besoin de mot de passe)
                System.out.println("✅ Google Login OK pour : " + email + " | Rôle : " + user.getRoles());
                lblMessage.setText("");
                finishLogin(user);
            });
        });

        googleTask.setOnFailed(event -> {
            Platform.runLater(() -> {
                btnGoogle.setDisable(false);
                lblMessage.setText("❌ Erreur lors de la connexion Google.");
                googleTask.getException().printStackTrace();
            });
        });

        // Démarrer le thread
        Thread thread = new Thread(googleTask);
        thread.setDaemon(true);
        thread.start();
    }

    // ================= CAPTCHA / FACE =================
    private void showCaptchaPopup(Utilisateur user) {
        try {
            if (user.isAdmin()) {
                FXMLLoader loader = new FXMLLoader(getClass().getResource("/face_auth_popup.fxml"));
                Parent root = loader.load();
                FaceAuthController controller = loader.getController();
                controller.initData(user);

                Stage stage = new Stage();
                stage.setTitle("Vérification Faciale Admin");
                stage.initModality(Modality.APPLICATION_MODAL);
                stage.setScene(new Scene(root));
                
                // ✅ Add closure request for Face Auth
                stage.setOnCloseRequest(e -> {
                    if (controller != null) {
                        controller.closePopup();
                    }
                });

                stage.showAndWait();

                if (controller.isVerified()) {
                    finishLogin(user);
                } else {
                    lblMessage.setText("Échec de la reconnaissance faciale.");
                }
            } else {
                FXMLLoader loader = new FXMLLoader(getClass().getResource("/captcha_popup.fxml"));
                Parent root = loader.load();
                CaptchaPopupController controller = loader.getController();
                controller.setOnSuccess(() -> finishLogin(user));

                Stage stage = new Stage();
                stage.setTitle("Vérification CAPTCHA");
                stage.initModality(Modality.APPLICATION_MODAL);
                stage.setScene(new Scene(root));
                stage.showAndWait();
            }
        } catch (Exception e) {
            e.printStackTrace();
            lblMessage.setText("Erreur lors de la vérification.");
        }
    }

    // ================= FINAL LOGIN - REDIRECTION PAR RÔLE =================
    private void finishLogin(Utilisateur user) {
        Session.setCurrentUser(user);
        tn.cashfly.session.UserSession.setUser(
            user.getId(),
            user.getNom() + " " + user.getPrenom(),
            user.getEmail(),
            user.getRoles(),
            false
        );
        startSessionTimer();
        tn.cashfly.tools.RememberMeUtil.save(user.getId(), REMEMBER_ME_TTL);
        
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
            // ✅ Redirection vers le dashboard PME (Operation Financiere)
            SceneManager.switchScene("dashboard.fxml");
        } else {
            // ROLE_INVESTISSEUR
            SceneManager.switchScene("InvestisseurDashboard.fxml");
        }
    }

    private void openKYCEnrollment(Utilisateur user) {
        try {
            URL fxmlUrl = getClass().getResource("/kyc_modal.fxml");
            if (fxmlUrl == null) {
                lblMessage.setText("Fichier FXML introuvable : /kyc_modal.fxml");
                return;
            }
            FXMLLoader loader = new FXMLLoader(fxmlUrl);
            Parent root = loader.load();
            
            KYCController kycController = loader.getController();
            
            Stage stage;
            if (btn_auth != null && btn_auth.getScene() != null && btn_auth.getScene().getWindow() instanceof Stage s) {
                stage = s;
                stage.setScene(new Scene(root));
            } else {
                stage = new Stage();
                stage.initModality(Modality.APPLICATION_MODAL);
                stage.setScene(new Scene(root));
            }
            stage.setTitle("Enrôlement KYC Obligatoire - Cashfly");
            
            // Ensure webcam closure even if window is closed by the "X" button
            stage.setOnCloseRequest(e -> {
                if (kycController != null) {
                    kycController.cleanup();
                }
            });
            
            // On successful KYC enrollment, proceed to dashboard
            stage.setOnHidden(e -> {
                if (KYCController.isVerified()) {
                    // Update session with KYC status
                    tn.cashfly.session.UserSession.setKycEnrolled(true);
                    
                    if (user.isProprietaire()) {
                        SceneManager.switchScene("dashboard.fxml");
                    } else {
                        SceneManager.switchScene("InvestisseurDashboard.fxml");
                    }
                } else {
                    // Cleanup again just in case hidden without verification
                    if (kycController != null) kycController.cleanup();
                    lblMessage.setText("Vous devez compléter le KYC pour accéder à votre compte.");
                    // Return to login if not verified
                    SceneManager.switchScene("authentification.fxml");
                }
            });
            
            stage.show();
        } catch (Exception e) {
            e.printStackTrace();
            lblMessage.setText("Erreur lors du chargement du KYC.");
        }
    }

    private void startSessionTimer() {
        new Thread(() -> {
            try {
                Thread.sleep(SESSION_TIMEOUT_MILLIS);
                Platform.runLater(() -> {
                    Session.clear();
                    tn.cashfly.session.UserSession.clear();
                    lblMessage.setText("Session expirée. Veuillez vous reconnecter.");
                    SceneManager.switchScene("authentification.fxml");
                });
            } catch (InterruptedException ignored) { }
        }, "SessionTimeoutThread").start();
    }

    private void autoLoginIfRemembered() {
        tn.cashfly.tools.RememberMeUtil.TokenData data = tn.cashfly.tools.RememberMeUtil.loadIfValid();
        if (data == null) return;
        Utilisateur u = utilisateurCrud.findById(data.userId);
        if (u == null || u.getActive() == 0) return;
        finishLogin(u);
    }

    // ================= NAV =================
    @FXML
    private void forgotPassword() {
        SceneManager.switchScene("ResetMdp.fxml");
    }

    @FXML
    private void goToInscription() {
        SceneManager.switchScene("inscription.fxml");
    }
}
