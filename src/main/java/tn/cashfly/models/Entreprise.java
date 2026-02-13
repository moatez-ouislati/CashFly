package tn.cashfly.models;

import java.sql.Date;

public class Entreprise {

    private int idEntreprise;
    private String nom;
    private String secteur;
    private String formeJuridique;
    private Date dateCreation;
    private double capital;
    private int idProprietaire;

    // constructeur sans ID (INSERT)
    public Entreprise(String nom, String secteur, String formeJuridique,
                      Date dateCreation, double capital, int idProprietaire) {
        this.nom = nom;
        this.secteur = secteur;
        this.formeJuridique = formeJuridique;
        this.dateCreation = dateCreation;
        this.capital = capital;
        this.idProprietaire = idProprietaire;
    }

    // constructeur complet (SELECT)
    public Entreprise(int idEntreprise, String nom, String secteur, String formeJuridique,
                      Date dateCreation, double capital, int idProprietaire) {
        this.idEntreprise = idEntreprise;
        this.nom = nom;
        this.secteur = secteur;
        this.formeJuridique = formeJuridique;
        this.dateCreation = dateCreation;
        this.capital = capital;
        this.idProprietaire = idProprietaire;
    }

    // getters
    public int getIdEntreprise() { return idEntreprise; }
    public String getNom() { return nom; }
    public String getSecteur() { return secteur; }
    public String getFormeJuridique() { return formeJuridique; }
    public Date getDateCreation() { return dateCreation; }
    public double getCapital() { return capital; }
    public int getIdProprietaire() { return idProprietaire; }

    @Override
    public String toString() {
        return "Entreprise{" +
                "id=" + idEntreprise +
                ", nom='" + nom + '\'' +
                ", capital=" + capital +
                '}';
    }
}