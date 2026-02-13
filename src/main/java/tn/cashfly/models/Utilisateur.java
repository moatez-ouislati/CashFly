package tn.cashfly.models;

import java.sql.Timestamp;

public class Utilisateur {

    private int idUtilisateur;
    private String nomComplet;
    private String email;
    private String motDePasse;
    private String role;
    private Timestamp dateCreation;

    // constructeur sans ID (INSERT)
    public Utilisateur(String nomComplet, String email, String motDePasse, String role) {
        this.nomComplet = nomComplet;
        this.email = email;
        this.motDePasse = motDePasse;
        this.role = role;
    }

    // constructeur complet (SELECT)
    public Utilisateur(int idUtilisateur, String nomComplet, String email,
                       String motDePasse, String role, Timestamp dateCreation) {
        this.idUtilisateur = idUtilisateur;
        this.nomComplet = nomComplet;
        this.email = email;
        this.motDePasse = motDePasse;
        this.role = role;
        this.dateCreation = dateCreation;
    }

    // getters
    public int getIdUtilisateur() { return idUtilisateur; }
    public String getNomComplet() { return nomComplet; }
    public String getEmail() { return email; }
    public String getMotDePasse() { return motDePasse; }
    public String getRole() { return role; }
    public Timestamp getDateCreation() { return dateCreation; }

    @Override
    public String toString() {
        return "Utilisateur{" +
                "id=" + idUtilisateur +
                ", nom='" + nomComplet + '\'' +
                ", role='" + role + '\'' +
                '}';
    }
}