package tn.cashfly.session;

/**
 * Stocke les informations de l'utilisateur connecté
 * et l'entreprise actuellement sélectionnée.
 */
public class UserSession {

    private static Integer userId;
    private static String fullName;
    private static String email;
    private static String role;

    private static Integer currentEntrepriseId;
    private static String currentEntrepriseName;

    private static Integer currentTresorerieId;

    public static void setUser(int id, String fullNameValue, String emailValue, String roleValue) {
        userId = id;
        fullName = fullNameValue;
        email = emailValue;
        role = roleValue;
    }

    public static Integer getUserId() {
        return userId;
    }

    public static String getFullName() {
        return fullName;
    }

    public static String getEmail() {
        return email;
    }

    public static String getRole() {
        return role;
    }

    public static void setCurrentEntreprise(Integer entrepriseId, String entrepriseName) {
        currentEntrepriseId = entrepriseId;
        currentEntrepriseName = entrepriseName;
    }

    public static Integer getCurrentEntrepriseId() {
        return currentEntrepriseId;
    }

    public static String getCurrentEntrepriseName() {
        return currentEntrepriseName;
    }

    public static void setCurrentTresorerie(Integer tresorerieId) {
        currentTresorerieId = tresorerieId;
    }

    public static Integer getCurrentTresorerieId() {
        return currentTresorerieId;
    }

    public static void clear() {
        userId = null;
        fullName = null;
        email = null;
        role = null;
        currentEntrepriseId = null;
        currentEntrepriseName = null;
        currentTresorerieId = null;
    }
}

