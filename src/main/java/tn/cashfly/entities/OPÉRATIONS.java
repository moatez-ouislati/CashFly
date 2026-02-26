package tn.cashfly.entities;

import java.time.LocalDateTime;

public class OPÉRATIONS {

    private int idOperation;
    private String reference;
    private String facture; // Invoice number
    private String pdfUrl;  // Path to generated PDF
    private TRÉSORERIE tresorerie;
    private TypeOperation type;
    private double montant;
    private String categorie;
    private String description;
    private LocalDateTime dateOperation;

    public enum TypeOperation {
        revenu, depense
    }

    // Constructor for new operation
    public OPÉRATIONS(TRÉSORERIE tresorerie, String reference, String facture, String pdfUrl, TypeOperation type, double montant, String categorie, String description) {
        this.tresorerie = tresorerie;
        this.reference = reference;
        this.facture = facture;
        this.pdfUrl = pdfUrl;
        this.type = type;
        this.montant = montant;
        this.categorie = categorie;
        this.description = description;
        this.dateOperation = LocalDateTime.now();
    }

    // Constructor for operation loaded from DB
    public OPÉRATIONS(int idOperation, TRÉSORERIE tresorerie, String reference, String facture, String pdfUrl, TypeOperation type, double montant,
                      String categorie, String description, LocalDateTime dateOperation) {
        this.idOperation = idOperation;
        this.tresorerie = tresorerie;
        this.reference = reference;
        this.facture = facture;
        this.pdfUrl = pdfUrl;
        this.type = type;
        this.montant = montant;
        this.categorie = categorie;
        this.description = description;
        this.dateOperation = dateOperation;
    }

    // Getters & setters
    public int getIdOperation() { return idOperation; }
    public void setIdOperation(int idOperation) { this.idOperation = idOperation; }
    
    public String getReference() { return reference; }
    public void setReference(String reference) { this.reference = reference; }
    
    public String getFacture() { return facture; }
    public void setFacture(String facture) { this.facture = facture; }
    
    public String getPdfUrl() { return pdfUrl; }
    public void setPdfUrl(String pdfUrl) { this.pdfUrl = pdfUrl; }

    public TRÉSORERIE getTresorerie() { return tresorerie; }
    public void setTresorerie(TRÉSORERIE tresorerie) { this.tresorerie = tresorerie; }
    
    public TypeOperation getType() { return type; }
    public void setType(TypeOperation type) { this.type = type; }
    
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
                "id=" + idOperation +
                ", ref='" + reference + '\'' +
                ", facture='" + facture + '\'' +
                ", type=" + type +
                ", montant=" + montant +
                '}';
    }
}

