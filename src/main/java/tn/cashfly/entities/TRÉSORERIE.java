package tn.cashfly.entities;

import java.time.LocalDateTime;

public class TRÉSORERIE {

    private int idTresorerie;
    private int idEntreprise;
    private String nomCompte;
    private TypeCompte typeCompte;
    private double solde;
    private String devise;
    private LocalDateTime derniereMaj;
    private String rib;
    private String numeroCompte;

    public enum TypeCompte {
        CAISSE, BANQUE, CARTE, WALLET
    }

    // Constructor for new Tresorerie
    public TRÉSORERIE(int idEntreprise, String nomCompte, TypeCompte typeCompte, double solde, String devise, String rib, String numeroCompte) {
        this.idEntreprise = idEntreprise;
        this.nomCompte = nomCompte;
        this.typeCompte = typeCompte;
        this.solde = solde;
        this.devise = devise;
        this.rib = rib;
        this.numeroCompte = numeroCompte;
        this.derniereMaj = LocalDateTime.now();
    }

    // Constructor for Tresorerie loaded from DB
    public TRÉSORERIE(int idTresorerie, int idEntreprise, String nomCompte, TypeCompte typeCompte, double solde, String devise, LocalDateTime derniereMaj, String rib, String numeroCompte) {
        this.idTresorerie = idTresorerie;
        this.idEntreprise = idEntreprise;
        this.nomCompte = nomCompte;
        this.typeCompte = typeCompte;
        this.solde = solde;
        this.devise = devise;
        this.derniereMaj = derniereMaj;
        this.rib = rib;
        this.numeroCompte = numeroCompte;
    }

    // Getters & setters
    public int getIdTresorerie() { return idTresorerie; }
    public void setIdTresorerie(int idTresorerie) { this.idTresorerie = idTresorerie; }
    public int getIdEntreprise() { return idEntreprise; }
    
    public String getNomCompte() { return nomCompte; }
    public void setNomCompte(String nomCompte) { this.nomCompte = nomCompte; }

    public TypeCompte getTypeCompte() { return typeCompte; }
    public void setTypeCompte(TypeCompte typeCompte) { this.typeCompte = typeCompte; }

    public double getSolde() { return solde; }
    public void setSolde(double solde) { this.solde = solde; }
    public String getDevise() { return devise; }
    public void setDevise(String devise) { this.devise = devise; }
    public LocalDateTime getDerniereMaj() { return derniereMaj; }
    public void setDerniereMaj(LocalDateTime derniereMaj) { this.derniereMaj = derniereMaj; }

    public String getRib() { return rib; }
    public void setRib(String rib) { this.rib = rib; }

    public String getNumeroCompte() { return numeroCompte; }
    public void setNumeroCompte(String numeroCompte) { this.numeroCompte = numeroCompte; }

    @Override
    public String toString() {
        return "Tresorerie{" +
                "id=" + idTresorerie +
                ", nom='" + nomCompte + '\'' +
                ", type=" + typeCompte +
                ", solde=" + solde + " " + devise +
                '}';
    }
}


