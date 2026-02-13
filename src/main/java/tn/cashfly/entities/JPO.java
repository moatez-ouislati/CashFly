package tn.cashfly.entities;

import java.util.Date;

public class JPO {

    private int id_evenement;
    private String titre,lieu,description;
    private Date date_evenement;

    //constructors
    public JPO() {
    }
    public JPO(String titre, String lieu, String description, Date date_evenement) {
        this.titre = titre;
        this.lieu = lieu;
        this.description = description;
        this.date_evenement = date_evenement;
    }
    public JPO(int id,String titre, String lieu, String description, Date date_evenement){
        this.id_evenement = id;
        this.titre = titre;
        this.lieu = lieu;
        this.description = description;
        this.date_evenement = date_evenement;
    }


    //getters & setters
    public int getId_evenement() { return id_evenement; }
    public void setId_evenement(int id_evenement) {this.id_evenement = id_evenement;}
    public String getTitre() {return titre;}
    public void setTitre(String titre) {this.titre = titre;}
    public String getLieu() {return lieu;}
    public void setLieu(String lieu) {this.lieu = lieu;}
    public String getDescription() {return description;}
    public void setDescription(String description) {this.description = description;}
    public Date getDate_evenement() {return date_evenement;}
    public void setDate_evenement(Date date_evenement) {this.date_evenement = date_evenement;}

    @Override
    public String toString() {
        return "Evenement:{" +
                "id=" + id_evenement +
                ", titre='" + titre + '\'' +
                ", lieu='" + lieu + '\'' +
                ", date=" + date_evenement +
                ", description='" + description + '\'' +
                '}';
    }
}