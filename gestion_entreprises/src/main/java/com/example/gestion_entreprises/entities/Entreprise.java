package com.example.gestion_entreprises.entities;
import java.math.BigDecimal;
import java.time.LocalDate;
import javafx.beans.property.*;

public class Entreprise {

    private final IntegerProperty idEntreprise = new SimpleIntegerProperty();
    private final StringProperty nom = new SimpleStringProperty();
    private final StringProperty secteur = new SimpleStringProperty();
    private final StringProperty formeJuridique = new SimpleStringProperty();
    private final ObjectProperty<LocalDate> dateCreation = new SimpleObjectProperty<>();
    private final ObjectProperty<BigDecimal> capital = new SimpleObjectProperty<>();
    private final IntegerProperty idProprietaire = new SimpleIntegerProperty();

    // Constructors
    public Entreprise() {}

    public Entreprise(int idEntreprise, String nom, String secteur,
                      String formeJuridique, LocalDate dateCreation,
                      BigDecimal capital, int idProprietaire) {
        this.idEntreprise.set(idEntreprise);
        this.nom.set(nom);
        this.secteur.set(secteur);
        this.formeJuridique.set(formeJuridique);
        this.dateCreation.set(dateCreation);
        this.capital.set(capital);
        this.idProprietaire.set(idProprietaire);
    }

    // Getters & Properties
    public int getIdEntreprise() { return idEntreprise.get(); }
    public IntegerProperty idEntrepriseProperty() { return idEntreprise; }

    public String getNom() { return nom.get(); }
    public StringProperty nomProperty() { return nom; }

    public String getSecteur() { return secteur.get(); }
    public StringProperty secteurProperty() { return secteur; }

    public String getFormeJuridique() { return formeJuridique.get(); }
    public StringProperty formeJuridiqueProperty() { return formeJuridique; }

    public LocalDate getDateCreation() { return dateCreation.get(); }
    public ObjectProperty<LocalDate> dateCreationProperty() { return dateCreation; }

    public BigDecimal getCapital() { return capital.get(); }
    public ObjectProperty<BigDecimal> capitalProperty() { return capital; }

    public int getIdProprietaire() { return idProprietaire.get(); }
    public IntegerProperty idProprietaireProperty() { return idProprietaire; }

    // ===================== SETTERS =====================

    public void setIdEntreprise(int idEntreprise) {
        this.idEntreprise.set(idEntreprise);
    }

    public void setNom(String nom) {
        this.nom.set(nom);
    }

    public void setSecteur(String secteur) {
        this.secteur.set(secteur);
    }

    public void setFormeJuridique(String formeJuridique) {
        this.formeJuridique.set(formeJuridique);
    }

    public void setDateCreation(LocalDate dateCreation) {
        this.dateCreation.set(dateCreation);
    }

    public void setCapital(BigDecimal capital) {
        this.capital.set(capital);
    }

    public void setIdProprietaire(int idProprietaire) {
        this.idProprietaire.set(idProprietaire);
    }


    @Override
    public String toString() {
        return nom.get();
    }
}
