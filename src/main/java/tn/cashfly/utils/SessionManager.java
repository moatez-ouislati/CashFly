package tn.cashfly.utils;

import tn.cashfly.entities.Utilisateur;

public class SessionManager {
    private static volatile Utilisateur currentUser;

    public static void setCurrentUser(Utilisateur user) {
        currentUser = user;
    }

    public static Utilisateur getCurrentUser() {
        return currentUser;
    }

    public static void clearSession() {
        currentUser = null;
    }

    public static boolean isLoggedIn() {
        return currentUser != null;
    }

    public static String getCurrentUserRole() {
        return currentUser != null ? currentUser.getRole() : null;
    }
}