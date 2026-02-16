package controllers;

import entities.Utilisateur;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.canvas.Canvas;
import javafx.scene.canvas.GraphicsContext;
import javafx.scene.control.*;
import javafx.scene.layout.VBox;
import javafx.scene.paint.Color;
import javafx.scene.shape.ArcType;
import javafx.stage.Stage;
import services.UtilisateurCrud;
import tools.Session;

public class AdminDashboard {

    // ===== LIST =====
    @FXML private ListView<Utilisateur> lvUsers;

    // ===== FIELDS =====
    @FXML private TextField tfSearch, tfCin, tfTel, tfNom, tfPrenom, tfEmail;
    @FXML private PasswordField tfPassword;
    @FXML private ComboBox<String> cbRole, cbFilterRole;

    // ===== BUTTONS =====
    @FXML private Button btnAdd, btnUpdate, btnDelete, btnRefresh, btnChangePass, btnLogout;

    // ===== STATS =====
    @FXML private Label lblAdmins, lblInvestisseurs, lblProprietaires;
    @FXML private Canvas donutCanvas;
    @FXML private Label donutLabel;

    private final UtilisateurCrud crud = new UtilisateurCrud();
    private ObservableList<Utilisateur> allUsers;
    private Utilisateur selectedUser;

    /* ================= INITIALIZE ================= */
    @FXML
    public void initialize() {

        cbRole.setItems(FXCollections.observableArrayList(
                "ROLE_ADMIN", "ROLE_INVESTISSEUR", "ROLE_PROPRIETAIRE"
        ));

        cbFilterRole.setItems(FXCollections.observableArrayList(
                "ALL", "ROLE_ADMIN", "ROLE_INVESTISSEUR", "ROLE_PROPRIETAIRE"
        ));
        cbFilterRole.setValue("ALL");

        loadUsers();
        loadStats();

        // ===== CARD VIEW =====
        lvUsers.setCellFactory(lv -> new ListCell<>() {
            @Override
            protected void updateItem(Utilisateur u, boolean empty) {
                super.updateItem(u, empty);
                if (empty || u == null) {
                    setGraphic(null);
                } else {
                    VBox box = new VBox(4,
                            new Label("👤 " + u.getNom().toUpperCase() + " " + u.getPrenom()),
                            new Label("🪪 CIN : " + u.getCin()),
                            new Label("📧 " + u.getEmail()),
                            new Label("🎭 " + u.getRoles())
                    );
                    box.setStyle("""
                        -fx-background-color:white;
                        -fx-padding:10;
                        -fx-background-radius:10;
                        -fx-border-radius:10;
                        -fx-border-color:#e5e7eb;
                    """);
                    setGraphic(box);
                }
            }
        });

        lvUsers.getSelectionModel().selectedItemProperty().addListener((o,a,n)->{
            if(n!=null){
                selectedUser=n;
                fillFields(n);
            }
        });

        tfSearch.textProperty().addListener((o,a,n)->applyFilters());
        cbFilterRole.setOnAction(e->applyFilters());

        // ===== BUTTON ACTIONS =====
        btnAdd.setOnAction(e->ajouter());
        btnUpdate.setOnAction(e->modifier());
        btnDelete.setOnAction(e->supprimer());
        btnRefresh.setOnAction(e->refreshAll());
        btnChangePass.setOnAction(e->changerMotDePasse());
        btnLogout.setOnAction(e->logout());
    }

    /* ================= CRUD ================= */

    private void ajouter() {
        try {
            Utilisateur u = new Utilisateur(
                    Integer.parseInt(tfCin.getText()),
                    tfTel.getText(),
                    tfNom.getText(),
                    tfPrenom.getText(),
                    tfEmail.getText(),
                    tfPassword.getText(),
                    "[\"" + cbRole.getValue() + "\"]"
            );
            crud.ajouter(u);
            show("Succès","Utilisateur ajouté");
            refreshAll();
        } catch (Exception e) {
            show("Erreur","Champs invalides");
        }
    }

    private void modifier() {
        if(selectedUser==null) return;

        selectedUser.setCin(Integer.parseInt(tfCin.getText()));
        selectedUser.setTel(tfTel.getText());
        selectedUser.setNom(tfNom.getText());
        selectedUser.setPrenom(tfPrenom.getText());
        selectedUser.setEmail(tfEmail.getText());
        selectedUser.setRoles("[\"" + cbRole.getValue() + "\"]");

        crud.modifier(selectedUser);
        show("Succès","Utilisateur modifié");
        refreshAll();
    }

    private void supprimer() {
        if(selectedUser==null) return;
        crud.supprimer(selectedUser.getId());
        show("Supprimé","Utilisateur supprimé");
        refreshAll();
    }

    private void changerMotDePasse() {
        if(selectedUser==null || tfPassword.getText().isBlank()) return;
        crud.changerMotDePasse(selectedUser.getId(), tfPassword.getText());
        tfPassword.clear();
        show("Succès","Mot de passe changé");
    }

    /* ================= HELPERS ================= */

    private void loadUsers() {
        allUsers = FXCollections.observableArrayList(crud.afficher());
        lvUsers.setItems(allUsers);
    }

    private void refreshAll() {
        loadUsers();
        loadStats();
        tfSearch.clear();
        cbFilterRole.setValue("ALL");
    }

    private void applyFilters() {
        String k=tfSearch.getText().toLowerCase();
        String r=cbFilterRole.getValue();

        lvUsers.setItems(allUsers.filtered(u->
                (k.isEmpty()||u.getEmail().toLowerCase().contains(k)) &&
                        (r.equals("ALL")||u.hasRole(r))
        ));
    }

    private void fillFields(Utilisateur u){
        tfCin.setText(String.valueOf(u.getCin()));
        tfTel.setText(u.getTel());
        tfNom.setText(u.getNom());
        tfPrenom.setText(u.getPrenom());
        tfEmail.setText(u.getEmail());
        cbRole.setValue(
                u.isAdmin()?"ROLE_ADMIN":
                        u.isProprietaire()?"ROLE_PROPRIETAIRE":
                                "ROLE_INVESTISSEUR"
        );
    }

    @FXML
    public void sortByNom(){
        allUsers.sort((a,b)->a.getNom().compareToIgnoreCase(b.getNom()));
        show("Tri","Tri par nom effectué");
    }

    @FXML
    public void exportPdf() {
        try {
            // 📁 المسار اللي طلبتو بالضبط
            String path = "C:/Users/hp/Projet3AJava-CASHFLY/users.pdf";

            com.lowagie.text.Document document = new com.lowagie.text.Document();
            com.lowagie.text.pdf.PdfWriter.getInstance(
                    document,
                    new java.io.FileOutputStream(path)
            );

            document.open();

            document.add(new com.lowagie.text.Paragraph("CASHFLY - Users List\n\n"));

            for (Utilisateur u : lvUsers.getItems()) {

                document.add(new com.lowagie.text.Paragraph(
                        "ID        : " + u.getId()
                ));
                document.add(new com.lowagie.text.Paragraph(
                        "CIN       : " + u.getCin()
                ));
                document.add(new com.lowagie.text.Paragraph(
                        "Nom       : " + u.getNom()
                ));
                document.add(new com.lowagie.text.Paragraph(
                        "Prénom    : " + u.getPrenom()
                ));
                document.add(new com.lowagie.text.Paragraph(
                        "Email     : " + u.getEmail()
                ));
                document.add(new com.lowagie.text.Paragraph(
                        "Rôle      : " + u.getRoles()
                ));

                document.add(new com.lowagie.text.Paragraph(
                        "----------------------------------------"
                ));
            }


            document.close();

            show("Export PDF",
                    "PDF créé avec succès ✔\n\n" +
                            "Emplacement:\n" + path
            );

        } catch (Exception e) {
            e.printStackTrace();
            show("Erreur", "Erreur lors de la création du PDF");
        }
    }



    @FXML
    public void loadStats(){
        long a=crud.afficher().stream().filter(Utilisateur::isAdmin).count();
        long i=crud.afficher().stream().filter(Utilisateur::isInvestisseur).count();
        long p=crud.afficher().stream().filter(Utilisateur::isProprietaire).count();

        lblAdmins.setText(String.valueOf(a));
        lblInvestisseurs.setText(String.valueOf(i));
        lblProprietaires.setText(String.valueOf(p));

        double t=a+i+p;
        drawDonut(t==0?0:a/t);
    }

    private void drawDonut(double percent){
        GraphicsContext g=donutCanvas.getGraphicsContext2D();
        g.clearRect(0,0,180,180);
        g.setStroke(Color.LIGHTGRAY); g.setLineWidth(18);
        g.strokeOval(20,20,140,140);
        g.setStroke(Color.web("#ef4444"));
        g.strokeArc(20,20,140,140,90,-360*percent, ArcType.OPEN);
        donutLabel.setText((int)(percent*100)+"%");
    }

    private void logout(){
        try{
            Session.clear();
            Parent r=FXMLLoader.load(getClass().getResource("/authentification.fxml"));
            Stage s=(Stage)btnLogout.getScene().getWindow();
            s.setScene(new Scene(r));
        }catch(Exception e){e.printStackTrace();}
    }

    private void show(String t,String m){
        Alert a=new Alert(Alert.AlertType.INFORMATION,m,ButtonType.OK);
        a.setTitle(t); a.setHeaderText(null); a.showAndWait();
    }
}
