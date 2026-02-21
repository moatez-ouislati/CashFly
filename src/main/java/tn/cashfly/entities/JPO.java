package tn.cashfly.entities;

import java.util.Date;

public class JPO {
    private int id_evenement;
    private String titre;
    private String lieu;
    private String description;
    private Date date_evenement;
    private String imagePath;
    private int maxParticipants;      // NEW
    private int currentParticipants;  // NEW

    public JPO() {}

    // Updated constructors
    public JPO(String titre, String lieu, String description, Date date_evenement,
               String imagePath, int maxParticipants) {
        this.titre = titre;
        this.lieu = lieu;
        this.description = description;
        this.date_evenement = date_evenement;
        this.imagePath = imagePath;
        this.maxParticipants = maxParticipants;
        this.currentParticipants = 0;
    }

    public JPO(int id, String titre, String lieu, String description, Date date_evenement,
               String imagePath, int maxParticipants, int currentParticipants) {
        this.id_evenement = id;
        this.titre = titre;
        this.lieu = lieu;
        this.description = description;
        this.date_evenement = date_evenement;
        this.imagePath = imagePath;
        this.maxParticipants = maxParticipants;
        this.currentParticipants = currentParticipants;
    }

    // Getters & Setters
    public int getId_evenement() { return id_evenement; }
    public void setId_evenement(int id_evenement) { this.id_evenement = id_evenement; }

    public String getTitre() { return titre; }
    public void setTitre(String titre) { this.titre = titre; }

    public String getLieu() { return lieu; }
    public void setLieu(String lieu) { this.lieu = lieu; }

    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }

    public Date getDate_evenement() { return date_evenement; }
    public void setDate_evenement(Date date_evenement) { this.date_evenement = date_evenement; }

    public String getImagePath() { return imagePath; }
    public void setImagePath(String imagePath) { this.imagePath = imagePath; }

    public int getMaxParticipants() { return maxParticipants; }
    public void setMaxParticipants(int maxParticipants) { this.maxParticipants = maxParticipants; }

    public int getCurrentParticipants() { return currentParticipants; }
    public void setCurrentParticipants(int currentParticipants) { this.currentParticipants = currentParticipants; }

    public int getSpotsLeft() { return maxParticipants - currentParticipants; }

    public boolean isFull() { return currentParticipants >= maxParticipants; }

    @Override
    public String toString() {
        return "JPO{id=" + id_evenement + ", titre='" + titre + "', spots=" + getSpotsLeft() + "/" + maxParticipants + "}";
    }
}