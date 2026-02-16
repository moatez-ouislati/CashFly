package com.example.gestion_entreprises.entities;

import java.time.LocalDateTime;
import javafx.beans.property.*;

public class Document {

    public enum TypeDocument {
        BUSINESS_PLAN, PITCH_DECK, ETAT_FINANCIER, RAPPORT_ACTIVITE, AUTRE
    }

    public enum Statut {
        EN_ATTENTE, VALIDE, REJETE
    }

    private final IntegerProperty idDocument = new SimpleIntegerProperty();
    private final IntegerProperty idEntreprise = new SimpleIntegerProperty();
    private final ObjectProperty<TypeDocument> typeDocument = new SimpleObjectProperty<>();
    private final StringProperty nomFichier = new SimpleStringProperty();
    private final StringProperty cheminFichier = new SimpleStringProperty();
    private final StringProperty description = new SimpleStringProperty();
    private final ObjectProperty<LocalDateTime> dateUpload = new SimpleObjectProperty<>();
    private final ObjectProperty<Statut> statut = new SimpleObjectProperty<>();

    // ✅ Constructors MUST match class name
    public Document() {}

    public Document(int idDocument, int idEntreprise,
                    TypeDocument typeDocument, String nomFichier,
                    String cheminFichier, String description,
                    LocalDateTime dateUpload, Statut statut) {

        this.idDocument.set(idDocument);
        this.idEntreprise.set(idEntreprise);
        this.typeDocument.set(typeDocument);
        this.nomFichier.set(nomFichier);
        this.cheminFichier.set(cheminFichier);
        this.description.set(description);
        this.dateUpload.set(dateUpload);
        this.statut.set(statut);
    }

    public int getIdDocument() { return idDocument.get(); }
    public IntegerProperty idDocumentProperty() { return idDocument; }

    public int getIdEntreprise() { return idEntreprise.get(); }
    public IntegerProperty idEntrepriseProperty() { return idEntreprise; }

    public TypeDocument getTypeDocument() { return typeDocument.get(); }
    public ObjectProperty<TypeDocument> typeDocumentProperty() { return typeDocument; }

    public String getNomFichier() { return nomFichier.get(); }
    public StringProperty nomFichierProperty() { return nomFichier; }

    public String getCheminFichier() { return cheminFichier.get(); }
    public StringProperty cheminFichierProperty() { return cheminFichier; }

    public String getDescription() { return description.get(); }
    public StringProperty descriptionProperty() { return description; }

    public LocalDateTime getDateUpload() { return dateUpload.get(); }
    public ObjectProperty<LocalDateTime> dateUploadProperty() { return dateUpload; }

    public Statut getStatut() { return statut.get(); }
    public ObjectProperty<Statut> statutProperty() { return statut; }
    public void setIdDocument(int id) {
        this.idDocument.set(id);
    }

    public void setIdEntreprise(int idEntreprise) {
        this.idEntreprise.set(idEntreprise);
    }

    public void setTypeDocument(TypeDocument typeDocument) {
        this.typeDocument.set(typeDocument);
    }

    public void setNomFichier(String nomFichier) {
        this.nomFichier.set(nomFichier);
    }

    public void setCheminFichier(String cheminFichier) {
        this.cheminFichier.set(cheminFichier);
    }

    public void setDescription(String description) {
        this.description.set(description);
    }

    public void setDateUpload(LocalDateTime dateUpload) {
        this.dateUpload.set(dateUpload);
    }

    public void setStatut(Statut statut) {
        this.statut.set(statut);
    }

}
