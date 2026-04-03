package tn.cashfly;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.StackPane;
import javafx.stage.Stage;
import javafx.animation.FadeTransition;
import javafx.util.Duration;
import tn.cashfly.session.UserSession;
import tn.cashfly.utils.MyDataBase;
import tn.cashfly.entities.Entreprise;
import tn.cashfly.controllers.EntrepriseController;

import tn.cashfly.entities.Document;
import tn.cashfly.controllers.DocumentController;
import tn.cashfly.controllers.NotificationsStage;
import tn.cashfly.controllers.NotificationType;
import tn.cashfly.controllers.ListProprietaireController;
import javafx.scene.shape.Circle;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class DashboardController {

    private static DashboardController instance;

    public static DashboardController getInstance() {
        return instance;
    }

    @FXML
    private StackPane contentRoot;

    @FXML
    private StackPane mainStackPane;

    @FXML
    private Circle notifDot;

    private NotificationsStage notificationsStage;

    @FXML
    private Button btnHome;

    @FXML
    private Button btnEntreprises;

    @FXML
    private Button btnDocuments;

    @FXML
    private Button btnTresorerie;

    @FXML
    private Button btnOperations;

    @FXML
    private Button btnStatistiques;

    @FXML
    private Button btnProprietaires;

    @FXML
    private Button btnJPO;

    @FXML
    private Button btnProfile;

    @FXML
    private Label pageTitle;

    @FXML
    private Label pageSubtitle;

    @FXML
    private Label userNameLabel;

    @FXML
    private Label userEmailLabel;

    @FXML
    private Label totalEntreprisesLabel;

    @FXML
    private Label totalTresoreriesLabel;

    @FXML
    private Label totalOperationsLabel;

    @FXML
    private Label totalDocumentsLabel;

    @FXML
    private Label totalRevenusLabel;

    @FXML
    private Label totalDepensesLabel;

    @FXML
    private HBox headerBox;

    @FXML
    public void initialize() {
        instance = this;
        updateUserInfo();
        updateStats();
        // Vue par défaut : Accueil
        try {
            showHome();
        } catch (Exception e) {
            System.err.println("Erreur lors de l'initialisation de la vue Accueil: " + e.getMessage());
            e.printStackTrace();
        }

        // Initialize notification stage
        notificationsStage = new NotificationsStage();
        mainStackPane.getChildren().add(notificationsStage);
    }

    @FXML
    public void toggleNotifications() {
        if (notificationsStage != null) {
            notificationsStage.toggle();
            notifDot.setVisible(false);
        }
    }

    public void addNotification(String text, NotificationType type) {
        if (notificationsStage != null) {
            notificationsStage.addNote(text, type);
            if (!notifDot.isVisible()) {
                notifDot.setVisible(true);
            }
        }
    }

    @FXML
    public void showHome() {
        loadView("/dashboard_home.fxml");
        updateActiveButton(btnHome);
        if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
        pageTitle.setText("Tableau de Bord");
        pageSubtitle.setText("Aperçu général de vos performances financières.");
        updateStats();
    }

    @FXML
    public void showEntreprises() {
        loadView("/AfficherEntreprise.fxml");
        updateActiveButton(btnEntreprises);
        if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
        pageTitle.setText("Entreprises");
        pageSubtitle.setText("Gérez vos entreprises et leurs informations financières.");
        updateStats();
    }

    @FXML
    public void showAjouterEntreprise() {
        loadView("/AjouterEntreprise.fxml");
        updateActiveButton(btnEntreprises);
        if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
        pageTitle.setText("Ajouter Entreprise");
    }

    public void showModifierEntreprise(Entreprise e) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/AjouterEntreprise.fxml"));
            Node view = loader.load();
            
            EntrepriseController controller = loader.getController();
            controller.setEntrepriseToEdit(e);

            view.setOpacity(0);
            view.setScaleX(0.98);
            view.setScaleY(0.98);
            contentRoot.getChildren().setAll(view);
            
            FadeTransition ft = new FadeTransition(Duration.millis(300), view);
            ft.setFromValue(0);
            ft.setToValue(1);
            
            javafx.animation.ScaleTransition st = new javafx.animation.ScaleTransition(Duration.millis(300), view);
            st.setFromX(0.98);
            st.setFromY(0.98);
            st.setToX(1);
            st.setToY(1);
            
            new javafx.animation.ParallelTransition(ft, st).play();

            updateActiveButton(btnEntreprises);
            if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
            pageTitle.setText("Modifier Entreprise");
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    @FXML
    public void showDocuments() {
        loadView("/AfficherDocument.fxml");
        updateActiveButton(btnDocuments);
        if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
        pageTitle.setText("Documents");
        pageSubtitle.setText("Gérez vos documents d'entreprise et accédez à l'OCR.");
        updateStats();
    }

    @FXML
    public void showAjouterDocument() {
        loadView("/AjouterDocument.fxml");
        updateActiveButton(btnDocuments);
        if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
        pageTitle.setText("Ajouter Document");
    }

    public void showModifierDocument(Document d) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/AjouterDocument.fxml"));
            Node view = loader.load();
            
            DocumentController controller = loader.getController();
            controller.setDocumentToEdit(d);

            view.setOpacity(0);
            view.setScaleX(0.98);
            view.setScaleY(0.98);
            contentRoot.getChildren().setAll(view);
            
            FadeTransition ft = new FadeTransition(Duration.millis(300), view);
            ft.setFromValue(0);
            ft.setToValue(1);
            
            javafx.animation.ScaleTransition st = new javafx.animation.ScaleTransition(Duration.millis(300), view);
            st.setFromX(0.98);
            st.setFromY(0.98);
            st.setToX(1);
            st.setToY(1);
            
            new javafx.animation.ParallelTransition(ft, st).play();

            updateActiveButton(btnDocuments);
            if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
            pageTitle.setText("Modifier Document");
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    @FXML
    public void showTresorerie() {
        if (UserSession.getCurrentEntrepriseId() == null) {
            showInfo("Veuillez d'abord sélectionner une entreprise dans l'onglet Entreprises.");
            return;
        }
        loadView("/tresorerie.fxml");
        updateActiveButton(btnTresorerie);
        if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
        pageTitle.setText("Trésorerie");
        pageSubtitle.setText("Suivi de vos comptes et flux de trésorerie.");
        updateStats();
    }

    @FXML
    public void showOperations() {
        if (UserSession.getCurrentEntrepriseId() == null) {
            showInfo("Veuillez d'abord sélectionner une entreprise.");
            return;
        }
        loadView("/operations.fxml");
        updateActiveButton(btnOperations);
        if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
        pageTitle.setText("Opérations");
        pageSubtitle.setText("Enregistrement et analyse de vos transactions.");
        updateStats();
    }

    @FXML
    public void showStatistiques() {
        loadView("/statistiques.fxml");
        updateActiveButton(btnStatistiques);
        if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
        pageTitle.setText("Statistiques & Analyse");
        pageSubtitle.setText("Gérez vos données, suivez vos revenus et vos entreprises.");
    }

    @FXML
    public void showProprietaires() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/ListProprietaire.fxml"));
            Node view = loader.load();
            ListProprietaireController controller = loader.getController();
            controller.setRoleToDisplay("investisseur"); // Show Investors for Propriétaire
            
            contentRoot.getChildren().setAll(view);
            updateActiveButton(btnProprietaires);
            if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
            pageTitle.setText("Investisseurs Partenaires");
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    @FXML
    public void showProfile() {
        loadView("/profile_settings.fxml");
        updateActiveButton(btnProfile);
        if (headerBox != null) { headerBox.setVisible(true); headerBox.setManaged(true); }
        pageTitle.setText("Paramètres du Profil");
        pageSubtitle.setText("Gérez vos informations personnelles et votre sécurité.");
    }

    @FXML
    public void handleLogout(javafx.event.ActionEvent event) {
        UserSession.clear();
        try {
            Parent root = FXMLLoader.load(getClass().getResource("/authentification.fxml"));
            Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
            stage.setScene(new Scene(root));
            stage.setTitle("Cashfly - Connexion");
            stage.centerOnScreen();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void updateActiveButton(Button activeBtn) {
        if (activeBtn == null) return;
        
        if (btnHome != null) btnHome.getStyleClass().remove("active-nav-btn");
        if (btnEntreprises != null) btnEntreprises.getStyleClass().remove("active-nav-btn");
        if (btnDocuments != null) btnDocuments.getStyleClass().remove("active-nav-btn");
        if (btnTresorerie != null) btnTresorerie.getStyleClass().remove("active-nav-btn");
        if (btnOperations != null) btnOperations.getStyleClass().remove("active-nav-btn");
        if (btnStatistiques != null) btnStatistiques.getStyleClass().remove("active-nav-btn");
        if (btnProprietaires != null) btnProprietaires.getStyleClass().remove("active-nav-btn");
        if (btnJPO != null) btnJPO.getStyleClass().remove("active-nav-btn");
        if (btnProfile != null) btnProfile.getStyleClass().remove("active-nav-btn");

        activeBtn.getStyleClass().add("active-nav-btn");
    }

    private void loadView(String fxmlPath) {
        try {
            Node view = FXMLLoader.load(getClass().getResource(fxmlPath));
            view.setOpacity(0);
            view.setScaleX(0.98);
            view.setScaleY(0.98);
            contentRoot.getChildren().setAll(view);
            
            FadeTransition ft = new FadeTransition(Duration.millis(300), view);
            ft.setFromValue(0);
            ft.setToValue(1);
            
            javafx.animation.ScaleTransition st = new javafx.animation.ScaleTransition(Duration.millis(300), view);
            st.setFromX(0.98);
            st.setFromY(0.98);
            st.setToX(1);
            st.setToY(1);
            
            new javafx.animation.ParallelTransition(ft, st).play();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void updateUserInfo() {
        if (UserSession.getUserId() != null) {
            userNameLabel.setText(UserSession.getFullName());
            userEmailLabel.setText(UserSession.getEmail());
        } else {
            userNameLabel.setText("Invite");
            userEmailLabel.setText("");
        }
    }

    public void updateStats() {
        if (UserSession.getUserId() == null) {
            if (totalEntreprisesLabel != null) totalEntreprisesLabel.setText("0");
            if (totalTresoreriesLabel != null) totalTresoreriesLabel.setText("0");
            if (totalOperationsLabel != null) totalOperationsLabel.setText("0");
            if (totalRevenusLabel != null) totalRevenusLabel.setText("0 TND");
            if (totalDepensesLabel != null) totalDepensesLabel.setText("0 TND");
            if (totalDocumentsLabel != null) totalDocumentsLabel.setText("0");
            return;
        }

        int userId = UserSession.getUserId();

        try {
            Connection cnx = MyDataBase.getInstance().getCnx();
            if (cnx == null || cnx.isClosed()) {
                return;
            }

            ensureDocumentsTable(cnx);

            // Total entreprises du propriétaire
            try (PreparedStatement ps = cnx.prepareStatement(
                    "SELECT COUNT(*) FROM entreprises WHERE id_proprietaire = ?")) {
                ps.setInt(1, userId);
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next() && totalEntreprisesLabel != null) {
                        totalEntreprisesLabel.setText(String.valueOf(rs.getInt(1)));
                    }
                }
            }

            // Total trésoreries liées aux entreprises du propriétaire
            try (PreparedStatement ps = cnx.prepareStatement(
                    "SELECT COUNT(*) FROM TRÉSORERIE t " +
                            "JOIN entreprises e ON t.id_entreprise = e.id_entreprise " +
                            "WHERE e.id_proprietaire = ?")) {
                ps.setInt(1, userId);
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next() && totalTresoreriesLabel != null) {
                        totalTresoreriesLabel.setText(String.valueOf(rs.getInt(1)));
                    }
                }
            }

            // Total opérations + revenus / dépenses
            try (PreparedStatement ps = cnx.prepareStatement(
                    "SELECT COUNT(*) AS total_ops, " +
                            "SUM(CASE WHEN o.type = 'revenu' THEN o.montant ELSE 0 END) AS total_revenus, " +
                            "SUM(CASE WHEN o.type = 'depense' THEN o.montant ELSE 0 END) AS total_depenses " +
                            "FROM OPÉRATIONS o " +
                            "JOIN TRÉSORERIE t ON o.id_tresorerie = t.id_tresorerie " +
                            "JOIN entreprises e ON t.id_entreprise = e.id_entreprise " +
                            "WHERE e.id_proprietaire = ?")) {
                ps.setInt(1, userId);
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next()) {
                        int totalOps = rs.getInt("total_ops");
                        double totalRevenus = rs.getDouble("total_revenus");
                        double totalDepenses = rs.getDouble("total_depenses");

                        if (totalOperationsLabel != null) totalOperationsLabel.setText(String.valueOf(totalOps));
                        if (totalRevenusLabel != null) totalRevenusLabel.setText(String.format("%.2f TND", totalRevenus));
                        if (totalDepensesLabel != null) totalDepensesLabel.setText(String.format("%.2f TND", totalDepenses));
                    }
                }
            }
            // Total documents du propriétaire (via ses entreprises)
            try (PreparedStatement ps = cnx.prepareStatement(
                    "SELECT COUNT(*) FROM documents d " +
                            "JOIN entreprises e ON d.id_entreprise = e.id_entreprise " +
                            "WHERE e.id_proprietaire = ?")) {
                ps.setInt(1, userId);
                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next() && totalDocumentsLabel != null) {
                        totalDocumentsLabel.setText(String.valueOf(rs.getInt(1)));
                    }
                }
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private void ensureDocumentsTable(Connection cnx) {
        String ddl = """
                CREATE TABLE IF NOT EXISTS documents (
                    id_document INT AUTO_INCREMENT PRIMARY KEY,
                    id_entreprise INT NOT NULL,
                    nom_document VARCHAR(255) NOT NULL,
                    type_document VARCHAR(50),
                    statut VARCHAR(50),
                    chemin_fichier VARCHAR(255),
                    date_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    CONSTRAINT fk_documents_entreprise
                        FOREIGN KEY (id_entreprise) REFERENCES entreprises(id_entreprise)
                        ON DELETE CASCADE
                )
                """;
        try (java.sql.Statement st = cnx.createStatement()) {
            st.executeUpdate(ddl);
        } catch (SQLException ignored) {
        }
    }

    private void showInfo(String message) {
        javafx.scene.control.Alert alert = new javafx.scene.control.Alert(javafx.scene.control.Alert.AlertType.INFORMATION);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }

    @FXML
    public void showJPO() {
        updateActiveButton(btnJPO);
        if (UserSession.getUserId() == null) {
            showInfo("Veuillez vous reconnecter pour accéder à la gestion JPO.");
            return;
        }
        try {
            tn.cashfly.jpo.entities.Utilisateur u = new tn.cashfly.jpo.entities.Utilisateur();
            u.setIdUtilisateur(UserSession.getUserId());
            String fullNameValue = UserSession.getFullName() != null ? UserSession.getFullName() : "";
            u.setNomComplet(fullNameValue);
            u.setEmail(UserSession.getEmail());
            u.setRole("proprietaire");
            tn.cashfly.jpo.utils.SessionManager.setCurrentUser(u);

            FXMLLoader loader = new FXMLLoader(getClass().getResource("/tn/cashfly/MainJPO.fxml"));
            Node view = loader.load();
            view.setOpacity(0);
            view.setScaleX(0.98);
            view.setScaleY(0.98);
            contentRoot.getChildren().setAll(view);

            FadeTransition ft = new FadeTransition(Duration.millis(300), view);
            ft.setFromValue(0);
            ft.setToValue(1);

            javafx.animation.ScaleTransition st = new javafx.animation.ScaleTransition(Duration.millis(300), view);
            st.setFromX(0.98);
            st.setFromY(0.98);
            st.setToX(1);
            st.setToY(1);

            new javafx.animation.ParallelTransition(ft, st).play();

            if (headerBox != null) { headerBox.setVisible(false); headerBox.setManaged(false); }
            pageTitle.setText("");
            pageSubtitle.setText("");
        } catch (IOException e) {
            e.printStackTrace();
            showInfo("Impossible d'ouvrir la gestion JPO.");
        }
    }
}
