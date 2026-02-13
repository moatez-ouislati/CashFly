package tn.cashfly.models;


import java.sql.Date;

public class Investissement {
    private int idInvestissement;
    private int idInvestisseur;
    private int idEntreprise;
    private double montant;
    private String statut;
    private Date dateInvestissement;
    private double tauxRendementPrevu;
    private int duree_mois;
    private String description;

    public Investissement() {}

    public Investissement(int idInvestisseur, int idEntreprise, double montant,
                          Date dateInvestissement, String statut,
                          double tauxRendementPrevu, int dureeMois, String description) {

        this.idInvestisseur = idInvestisseur;
        this.idEntreprise = idEntreprise;
        this.montant = montant;
        this.dateInvestissement = dateInvestissement;
        this.statut = statut;
        this.tauxRendementPrevu = tauxRendementPrevu;
        this.duree_mois = dureeMois;
        this.description = description;
    }

    public int getIdInvestissement() { return idInvestissement; }
    public void setIdInvestissement(int idInvestissement) { this.idInvestissement = idInvestissement; }

    public int getIdInvestisseur() { return idInvestisseur; }
    public void setIdInvestisseur(int idInvestisseur) { this.idInvestisseur = idInvestisseur; }

    public int getIdEntreprise() { return idEntreprise; }
    public void setIdEntreprise(int idEntreprise) { this.idEntreprise = idEntreprise; }

    public double getMontant() { return montant; }
    public void setMontant(double montant) { this.montant = montant; }

    public String getStatut() { return statut; }
    public void setStatut(String statut) { this.statut = statut; }

    public Date getDateInvestissement() { return dateInvestissement; }
    public void setDateInvestissement(Date dateInvestissement) { this.dateInvestissement = dateInvestissement; }

    public double getTauxRendementPrevu() { return tauxRendementPrevu; }
    public void setTauxRendementPrevu(double tauxRendementPrevu) { this.tauxRendementPrevu = tauxRendementPrevu; }

    public int getDureeMois() { return duree_mois; }
    public void setDureeMois(int duree_mois) { this.duree_mois = duree_mois; }

    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }

    @Override
    public String toString() {
        return "Invesstissement{" +
                "idInvestissement=" + idInvestissement +
                ", idInvestisseur=" + idInvestisseur +
                ", idEntreprise=" + idEntreprise +
                ", montant=" + montant +
                ", data investissement=" + dateInvestissement +
                ", status='" + statut + '\'' +
                ", tauxRendementPrevu=" + tauxRendementPrevu +
                ", duree_mois=" + duree_mois +
                ", description='" + description + '\'' +
                '}';
    }
}


