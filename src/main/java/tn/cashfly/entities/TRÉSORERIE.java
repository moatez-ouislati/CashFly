package tn.cashfly.entities;

import java.time.LocalDateTime;

public class TRÉSORERIE {

    private int idTresorerie;
    private int idEntreprise;
    private double solde;
    private String devise;
    private LocalDateTime derniereMaj;

    // Constructor for new Tresorerie
    public TRÉSORERIE(int idEntreprise, double solde, String devise) {
        this.idEntreprise = idEntreprise;
        this.solde = solde;
        this.devise = devise;
        this.derniereMaj = LocalDateTime.now();
    }

    // Constructor for Tresorerie loaded from DB
    public TRÉSORERIE(int idTresorerie, int idEntreprise, double solde, String devise, LocalDateTime derniereMaj) {
        this.idTresorerie = idTresorerie;
        this.idEntreprise = idEntreprise;
        this.solde = solde;
        this.devise = devise;
        this.derniereMaj = derniereMaj;
    }

    // Getters & setters
    public int getIdTresorerie() { return idTresorerie; }
    public void setIdTresorerie(int idTresorerie) { this.idTresorerie = idTresorerie; }
    public int getIdEntreprise() { return idEntreprise; }
    public double getSolde() { return solde; }
    public void setSolde(double solde) { this.solde = solde; }
    public String getDevise() { return devise; }
    public void setDevise(String devise) { this.devise = devise; }
    public LocalDateTime getDerniereMaj() { return derniereMaj; }
    public void setDerniereMaj(LocalDateTime derniereMaj) { this.derniereMaj = derniereMaj; }

    @Override
    public String toString() {
        return "Tresorerie{" +
                "idTresorerie=" + idTresorerie +
                ", idEntreprise=" + idEntreprise +
                ", solde=" + solde +
                ", devise='" + devise + '\'' +
                ", derniereMaj=" + derniereMaj +
                '}';
    }
}

