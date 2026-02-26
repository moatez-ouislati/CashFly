package tn.cashfly.entities;

import java.util.Date;

/**
 * Data class containing comprehensive participant information for Excel export
 */
public class ParticipantInfo {

    // User information
    private int userId;
    private String nomComplet;
    private String email;
    private String role;
    private Date dateCreationCompte;

    // Participation information
    private int participationId;
    private String statut;
    private Date dateInscription;
    private boolean badgeGenere;
    private Date dateGenerationBadge;

    // Event information (for reference)
    private String eventTitre;
    private Date eventDate;

    // Additional computed fields
    private String statutBadge;
    private String delaiInscription; // Time between registration and event

    public ParticipantInfo() {}

    // Getters and Setters
    public int getUserId() { return userId; }
    public void setUserId(int userId) { this.userId = userId; }

    public String getNomComplet() { return nomComplet; }
    public void setNomComplet(String nomComplet) { this.nomComplet = nomComplet; }

    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }

    public String getRole() { return role; }
    public void setRole(String role) { this.role = role; }

    public Date getDateCreationCompte() { return dateCreationCompte; }
    public void setDateCreationCompte(Date dateCreationCompte) { this.dateCreationCompte = dateCreationCompte; }

    public int getParticipationId() { return participationId; }
    public void setParticipationId(int participationId) { this.participationId = participationId; }

    public String getStatut() { return statut; }
    public void setStatut(String statut) { this.statut = statut; }

    public Date getDateInscription() { return dateInscription; }
    public void setDateInscription(Date dateInscription) { this.dateInscription = dateInscription; }

    public boolean isBadgeGenere() { return badgeGenere; }
    public void setBadgeGenere(boolean badgeGenere) { this.badgeGenere = badgeGenere; }

    public Date getDateGenerationBadge() { return dateGenerationBadge; }
    public void setDateGenerationBadge(Date dateGenerationBadge) { this.dateGenerationBadge = dateGenerationBadge; }

    public String getEventTitre() { return eventTitre; }
    public void setEventTitre(String eventTitre) { this.eventTitre = eventTitre; }

    public Date getEventDate() { return eventDate; }
    public void setEventDate(Date eventDate) { this.eventDate = eventDate; }

    public String getStatutBadge() {
        return badgeGenere ? "Généré" : "Non généré";
    }

    public String getDelaiInscription() {
        if (dateInscription != null && eventDate != null) {
            long diffMillis = eventDate.getTime() - dateInscription.getTime();
            long diffDays = diffMillis / (1000 * 60 * 60 * 24);
            if (diffDays < 0) diffDays = 0;
            return diffDays + " jours avant l'événement";
        }
        return "N/A";
    }

    public String getConfirmationStatus() {
        return switch (statut.toLowerCase()) {
            case "confirmé" -> "✓ Confirmé";
            case "en_attente" -> "⏳ En attente";
            case "annulé" -> "✗ Annulé";
            case "présent" -> "✓ Présence confirmée";
            default -> statut;
        };
    }
}