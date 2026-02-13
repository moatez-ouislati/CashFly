package tn.cashfly.entities;

import java.time.LocalDate;

public class ENTREPRISE {

    private int idEntreprise;
    private String nom;
    private String secteur;
    private String formeJuridique;
    private LocalDate dateCreation;
    private double capital;
    private int idProprietaire; // FK to Utilisateur

    // Constructor for new Entreprise
    public ENTREPRISE(String nom, String secteur, String formeJuridique,
                      LocalDate dateCreation, double capital, int idProprietaire) {
        this.nom = nom;
        this.secteur = secteur;
        this.formeJuridique = formeJuridique;
        this.dateCreation = dateCreation;
        this.capital = capital;
        this.idProprietaire = idProprietaire;
    }

    // Constructor for Entreprise loaded from DB
    public ENTREPRISE(int idEntreprise, String nom, String secteur,
                      String formeJuridique, LocalDate dateCreation,
                      double capital, int idProprietaire) {
        this.idEntreprise = idEntreprise;
        this.nom = nom;
        this.secteur = secteur;
        this.formeJuridique = formeJuridique;
        this.dateCreation = dateCreation;
        this.capital = capital;
        this.idProprietaire = idProprietaire;
    }

    // Getters & Setters

    public int getIdEntreprise() {
        return idEntreprise;
    }

    public void setIdEntreprise(int idEntreprise) {
        this.idEntreprise = idEntreprise;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getSecteur() {
        return secteur;
    }

    public void setSecteur(String secteur) {
        this.secteur = secteur;
    }

    public String getFormeJuridique() {
        return formeJuridique;
    }

    public void setFormeJuridique(String formeJuridique) {
        this.formeJuridique = formeJuridique;
    }

    public LocalDate getDateCreation() {
        return dateCreation;
    }

    public void setDateCreation(LocalDate dateCreation) {
        this.dateCreation = dateCreation;
    }

    public double getCapital() {
        return capital;
    }

    public void setCapital(double capital) {
        this.capital = capital;
    }

    public int getIdProprietaire() {
        return idProprietaire;
    }

    public void setIdProprietaire(int idProprietaire) {
        this.idProprietaire = idProprietaire;
    }

    @Override
    public String toString() {
        return "Entreprise{" +
                "idEntreprise=" + idEntreprise +
                ", nom='" + nom + '\'' +
                ", secteur='" + secteur + '\'' +
                ", formeJuridique='" + formeJuridique + '\'' +
                ", dateCreation=" + dateCreation +
                ", capital=" + capital +
                ", idProprietaire=" + idProprietaire +
                '}';
    }
}

