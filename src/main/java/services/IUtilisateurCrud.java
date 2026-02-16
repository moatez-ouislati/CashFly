package services;

import entities.Utilisateur;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.List;

public interface IUtilisateurCrud {

    void ajouter(Utilisateur u);

    List<Utilisateur> afficher();

    void modifier(Utilisateur u);
    void modifierI(Utilisateur u);

    void supprimer(int id);

}
