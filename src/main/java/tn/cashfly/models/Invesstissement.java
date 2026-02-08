package tn.cashfly.models;

public class Invesstissement {
    private int idInvestissement;
    private int idInvestisseur;
    private int idEntreprise;
    private double montant;
    private String status;
    private double tauxRendementPrevu;
    private int dureeMois;
    private String description;

    public Invesstissement(int idInvestissement, int idInvestisseur, int idEntreprise, double montant, String status, double tauxRendementPrevu, int dureeMois, String description) {
        this.idInvestissement = idInvestissement;
        this.idInvestisseur = idInvestisseur;
        this.idEntreprise = idEntreprise;
        this.montant = montant;
        this.status = status;
        this.tauxRendementPrevu = tauxRendementPrevu;
        this.dureeMois = dureeMois;
        this.description=description;
    }

    public int getIdInvestissement() {
        return idInvestissement;
    }

    public void setIdInvestissement(int idInvestissement) {
        this.idInvestissement = idInvestissement;
    }

    public int getIdInvestisseur() {
        return idInvestisseur;
    }

    public void setIdInvestisseur(int idInvestisseur) {
        this.idInvestisseur = idInvestisseur;
    }

    public int getIdEntreprise() {
        return idEntreprise;
    }

    public void setIdEntreprise(int idEntreprise) {
        this.idEntreprise = idEntreprise;
    }

    public double getMontant() {
        return montant;
    }

    public void setMontant(double montant) {
        this.montant = montant;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public double getTauxRendementPrevu() {
        return tauxRendementPrevu;
    }

    public void setTauxRendementPrevu(double tauxRendementPrevu) {
        this.tauxRendementPrevu = tauxRendementPrevu;
    }

    public int getDureeMois() {
        return dureeMois;
    }

    public void setDureeMois(int dureeMois) {
        this.dureeMois = dureeMois;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    @Override
    public String toString() {
        return "Invesstissement{" +
                "idInvestissement=" + idInvestissement +
                ", idInvestisseur=" + idInvestisseur +
                ", idEntreprise=" + idEntreprise +
                ", montant=" + montant +
                ", status='" + status + '\'' +
                ", tauxRendementPrevu=" + tauxRendementPrevu +
                ", dureeMois=" + dureeMois +
                ", description='" + description + '\'' +
                '}';
    }
}
