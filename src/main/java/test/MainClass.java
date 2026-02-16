package test;

import entities.Utilisateur;
import services.UtilisateurCrud;
import tools.MyConnection;

public class MainClass {

    public static void main(String[] args) {

        // Test Singleton
        MyConnection mc1 = MyConnection.getInstance();
        MyConnection mc2 = MyConnection.getInstance();
        System.out.println(mc1.hashCode() + " - " + mc2.hashCode());

        // Test ajout utilisateur
        Utilisateur u = new Utilisateur(
                14505878,
                "52971719",
                "talbi",
                "salma",
                "salmatalbi@gmail.com",
                "123456",
                "[\"ROLE_USER\"]"
        );

        UtilisateurCrud crud = new UtilisateurCrud();

        crud.ajouter(u);

        // Test affichage
        crud.afficher().forEach(System.out::println);
    }
}
