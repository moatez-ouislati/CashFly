package tn.cashfly.entities;

import java.util.Date;

public class Utilisateur {
    private int idUtilisateur;
    private String nomComplet;
    private String email;
    private String motDePasse;
    private String role; // 'proprietaire', 'investisseur', 'administrateur'
    private Date dateCreation;

    public Utilisateur() {}

    public Utilisateur(int idUtilisateur, String nomComplet, String email, String role) {
        this.idUtilisateur = idUtilisateur;
        this.nomComplet = nomComplet;
        this.email = email;
        this.role = role;
    }

    // Getters & Setters
    public int getIdUtilisateur() { return idUtilisateur; }
    public void setIdUtilisateur(int idUtilisateur) { this.idUtilisateur = idUtilisateur; }

    public String getNomComplet() { return nomComplet; }
    public void setNomComplet(String nomComplet) { this.nomComplet = nomComplet; }

    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }

    public String getMotDePasse() { return motDePasse; }
    public void setMotDePasse(String motDePasse) { this.motDePasse = motDePasse; }

    public String getRole() { return role; }
    public void setRole(String role) { this.role = role; }

    public Date getDateCreation() { return dateCreation; }
    public void setDateCreation(Date dateCreation) { this.dateCreation = dateCreation; }
}