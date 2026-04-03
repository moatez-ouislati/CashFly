package tn.cashfly.test;

import tn.cashfly.entities.Utilisateur;
import tn.cashfly.services.UtilisateurCrud;
import tn.cashfly.tools.MyConnection;

public class MainClass {

    public static void main(String[] args) {

        // Test Singleton
        MyConnection mc1 = MyConnection.getInstance();
        MyConnection mc2 = MyConnection.getInstance();
        System.out.println(mc1.hashCode() + " - " + mc2.hashCode());

        // Test ajout utilisateur
        Utilisateur u = new Utilisateur();

        u.setCin(1505878);
        u.setTel("5297719");
        u.setNom("talbi");
        u.setPrenom("salma");
        u.setEmail("salma.talbi@esprit.tn");
        u.setPassword("123456");
        u.setRoles("[\"ROLE_USER\"]");

        UtilisateurCrud crud = new UtilisateurCrud();


    }
}

