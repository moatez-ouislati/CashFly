package tn.cashfly;

import tn.cashfly.entities.JPO;
import tn.cashfly.services.ServiceJPO;

import java.sql.Date;
import java.sql.SQLException;
import java.util.List;
import java.util.Scanner;

public class Main {
    public static void main(String[] args) {
        ServiceJPO sJPO = new ServiceJPO();
        Scanner scanner = new Scanner(System.in);
        int choice;

        do {
            System.out.println("\n========== GESTION DES JPO ==========");
            System.out.println("1. Ajouter une JPO");
            System.out.println("2. Modifier une JPO");
            System.out.println("3. Supprimer une JPO");
            System.out.println("4. Afficher toutes les JPO");
            System.out.println("0. Quitter");
            System.out.print("\nVotre choix: ");

            choice = scanner.nextInt();
            scanner.nextLine(); // Consume newline

            try {
                switch (choice) {
                    case 1:
                        ajouterJPO(sJPO, scanner);
                        break;
                    case 2:
                        modifierJPO(sJPO, scanner);
                        break;
                    case 3:
                        supprimerJPO(sJPO, scanner);
                        break;
                    case 4:
                        afficherToutesJPO(sJPO);
                        break;
                    case 0:
                        System.out.println("Au revoir !");
                        break;
                    default:
                        System.out.println("Choix invalide !");
                }
            } catch (SQLException e) {
                System.out.println("Erreur SQL: " + e.getMessage());
            }

        } while (choice != 0);

        scanner.close();
    }
    //
    private static void ajouterJPO(ServiceJPO sJPO, Scanner scanner) throws SQLException {
        System.out.println("\n--- AJOUT D'UNE NOUVELLE JPO ---");

        System.out.print("Entrez le titre: ");
        String titre = scanner.nextLine();

        System.out.print("Entrez la date (YYYY-MM-DD): ");
        String dateStr = scanner.nextLine();
        Date date = Date.valueOf(dateStr);

        System.out.print("Entrez le lieu: ");
        String lieu = scanner.nextLine();

        System.out.print("Entrez la description: ");
        String description = scanner.nextLine();

        JPO newJPO = new JPO(titre, lieu, description, date);
        sJPO.add(newJPO);

        System.out.println("✓ JPO ajoutée avec succès ! (ID généré: " + newJPO.getId_evenement() + ")");
    }
    //
    private static void modifierJPO(ServiceJPO sJPO, Scanner scanner) throws SQLException {
        System.out.println("\n--- MODIFICATION D'UNE JPO ---");

        System.out.print("Entrez l'ID de la JPO à modifier: ");
        int id = scanner.nextInt();
        scanner.nextLine(); // Consume newline

        System.out.println("Entrez les nouvelles valeures: ↓ ");

        System.out.print("Nouveau titre: ");
        String titre = scanner.nextLine();

        System.out.print("Nouvelle date (YYYY-MM-DD): ");
        String dateStr = scanner.nextLine();

        System.out.print("Nouveau lieu: ");
        String lieu = scanner.nextLine();

        System.out.print("Nouvelle description: ");
        String description = scanner.nextLine();

        // Create JPO with the ID for update
        Date date = dateStr.isEmpty() ? null : Date.valueOf(dateStr);
        JPO updatedJPO = new JPO(id, titre, lieu, description, date);

        sJPO.update(updatedJPO);
        System.out.println("✓ JPO #" + id + " modifiée avec succès !");
    }
    //
    private static void supprimerJPO(ServiceJPO sJPO, Scanner scanner) throws SQLException {
        System.out.println("\n--- SUPPRESSION D'UNE JPO ---");

        System.out.print("Entrez l'ID de la JPO à supprimer: ");
        int id = scanner.nextInt();
        scanner.nextLine(); // Consume newline

        System.out.print("Confirmez la suppression (oui/non): ");
        String confirmation = scanner.nextLine();

        if (confirmation.equalsIgnoreCase("oui")) {
            JPO jpoToDelete = new JPO();
            jpoToDelete.setId_evenement(id);
            sJPO.delete(jpoToDelete);
            System.out.println("✓ JPO #" + id + " supprimée avec succès !");
        } else {
            System.out.println("× Suppression annulée.");
        }
    }
    //
    private static void afficherToutesJPO(ServiceJPO sJPO) throws SQLException {
        System.out.println("\n--- LISTE DE TOUTES LES JPO ---");

        List<JPO> jpoList = sJPO.getAll();

        if (jpoList.isEmpty()) {
            System.out.println("Aucune JPO trouvée.");
            return;
        }

        System.out.println("Nombre de JPO trouvées: " + jpoList.size());
        System.out.println("--------------------------------------------------");

        for (JPO jpo : jpoList) {
            System.out.println("ID: " + jpo.getId_evenement());
            System.out.println("Titre: " + jpo.getTitre());
            System.out.println("Date: " + jpo.getDate_evenement());
            System.out.println("Lieu: " + jpo.getLieu());
            System.out.println("Description: " + jpo.getDescription());
            System.out.println("--------------------------------------------------");
        }
    }
}