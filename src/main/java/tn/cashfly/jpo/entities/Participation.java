package tn.cashfly.jpo.entities;

import java.util.Date;

public class Participation {
    private int idParticipation;
    private int idEvenement;
    private int idUtilisateur;
    private String statut;
    private Date dateInscription;
    private boolean badgeGenere;

    public Participation() {}

    public Participation(int idEvenement, int idUtilisateur, String statut) {
        this.idEvenement = idEvenement;
        this.idUtilisateur = idUtilisateur;
        this.statut = statut;
    }

    public int getIdParticipation() { return idParticipation; }
    public void setIdParticipation(int idParticipation) { this.idParticipation = idParticipation; }

    public int getIdEvenement() { return idEvenement; }
    public void setIdEvenement(int idEvenement) { this.idEvenement = idEvenement; }

    public int getIdUtilisateur() { return idUtilisateur; }
    public void setIdUtilisateur(int idUtilisateur) { this.idUtilisateur = idUtilisateur; }

    public String getStatut() { return statut; }
    public void setStatut(String statut) { this.statut = statut; }

    public Date getDateInscription() { return dateInscription; }
    public void setDateInscription(Date dateInscription) { this.dateInscription = dateInscription; }

    public boolean isBadgeGenere() { return badgeGenere; }
    public void setBadgeGenere(boolean badgeGenere) { this.badgeGenere = badgeGenere; }
}
