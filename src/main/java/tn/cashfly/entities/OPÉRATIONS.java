package tn.cashfly.entities;

import java.time.LocalDateTime;

public class OPÉRATIONS {

    private int idOperation;
    private TRÉSORERIE tresorerie;  // reference to the treasury object
    private String type;            // "revenu" or "depense"
    private double montant;
    private String categorie;
    private String description;
    private LocalDateTime dateOperation;

    // Constructor for new operation
    public OPÉRATIONS(TRÉSORERIE tresorerie, String type, double montant, String categorie, String description) {
        this.tresorerie = tresorerie;
        this.type = type;
        this.montant = montant;
        this.categorie = categorie;
        this.description = description;
        this.dateOperation = LocalDateTime.now();
    }

    // Constructor for operation loaded from DB
    public OPÉRATIONS(int idOperation, TRÉSORERIE tresorerie, String type, double montant,
                      String categorie, String description, LocalDateTime dateOperation) {
        this.idOperation = idOperation;
        this.tresorerie = tresorerie;
        this.type = type;
        this.montant = montant;
        this.categorie = categorie;
        this.description = description;
        this.dateOperation = dateOperation;
    }

    // Getters & setters
    public int getIdOperation() { return idOperation; }
    public void setIdOperation(int idOperation) { this.idOperation = idOperation; }
    public TRÉSORERIE getTresorerie() { return tresorerie; }
    public void setTresorerie(TRÉSORERIE tresorerie) { this.tresorerie = tresorerie; }
    public String getType() { return type; }
    public void setType(String type) { this.type = type; }
    public double getMontant() { return montant; }
    public void setMontant(double montant) { this.montant = montant; }
    public String getCategorie() { return categorie; }
    public void setCategorie(String categorie) { this.categorie = categorie; }
    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }
    public LocalDateTime getDateOperation() { return dateOperation; }
    public void setDateOperation(LocalDateTime dateOperation) { this.dateOperation = dateOperation; }

    @Override
    public String toString() {
        return "Operation{" +
                "idOperation=" + idOperation +
                ", tresorerieId=" + tresorerie.getIdTresorerie() +
                ", type='" + type + '\'' +
                ", montant=" + montant +
                ", categorie='" + categorie + '\'' +
                ", description='" + description + '\'' +
                ", dateOperation=" + dateOperation +
                '}';
    }
}

