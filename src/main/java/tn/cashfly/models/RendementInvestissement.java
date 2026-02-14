package tn.cashfly.models;

import java.math.BigDecimal;
import java.sql.Date;

public class RendementInvestissement {
    private int idRendement;
    private int idInvestissement;
    private Date dateCalcul;
    private BigDecimal gain;
    private BigDecimal perte;
    private BigDecimal valeurPortefeuille;

    public RendementInvestissement() {
    }

    public RendementInvestissement(int idInvestissement, Date dateCalcul, BigDecimal gain, BigDecimal perte,
                                   BigDecimal valeurPortefeuille) {
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

    public BigDecimal getGain() {
        return gain;
    }

    public void setGain(BigDecimal gain) {
        this.gain = gain;
    }

    public BigDecimal getPerte() {
        return perte;
    }

    public void setPerte(BigDecimal perte) {
        this.perte = perte;
    }

    public BigDecimal getValeurPortefeuille() {
        return valeurPortefeuille;
    }

    public void setValeurPortefeuille(BigDecimal valeurPortefeuille) {
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
