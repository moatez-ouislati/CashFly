package tn.cashfly.models;

import java.sql.Date;

public class RendementInvestissement {
    private int idRendement;
    private int idInvestissement;
    private Date dateCalcul;
    private double gain;
    private double perte;
    private double valeurPortefeuille;

    public RendementInvestissement() {}

    public RendementInvestissement(int idInvestissement, Date dateCalcul, double gain, double perte, double valeurPortefeuille) {
        this.idInvestissement = idInvestissement;
        this.dateCalcul = dateCalcul;
        this.gain = gain;
        this.perte = perte;
        this.valeurPortefeuille = valeurPortefeuille;
    }

    // Getters & Setters


    public int getIdRendement() {
        return idRendement;
    }

    public void setIdRendement(int idRendement) {
        this.idRendement = idRendement;
    }

    public int getIdInvestissement() {
        return idInvestissement;
    }

    public void setIdInvestissement(int idInvestissement) {
        this.idInvestissement = idInvestissement;
    }

    public Date getDateCalcul() {
        return dateCalcul;
    }

    public void setDateCalcul(Date dateCalcul) {
        this.dateCalcul = dateCalcul;
    }

    public double getGain() {
        return gain;
    }

    public void setGain(double gain) {
        this.gain = gain;
    }

    public double getPerte() {
        return perte;
    }

    public void setPerte(double perte) {
        this.perte = perte;
    }

    public double getValeurPortefeuille() {
        return valeurPortefeuille;
    }

    public void setValeurPortefeuille(double valeurPortefeuille) {
        this.valeurPortefeuille = valeurPortefeuille;
    }

    @Override
    public String toString() {
        return "RendementInvestissement{" +
                "idRendement=" + idRendement +
                ", idInvestissement=" + idInvestissement +
                ", dateCalcul=" + dateCalcul +
                ", gain=" + gain +
                ", perte=" + perte +
                ", valeurPortefeuille=" + valeurPortefeuille +
                '}';
    }
}