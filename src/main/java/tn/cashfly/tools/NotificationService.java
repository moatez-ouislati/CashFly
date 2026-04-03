package tn.cashfly.tools;

import tn.cashfly.DashboardController;
import tn.cashfly.controllers.InvestisseurDashboard;
import tn.cashfly.controllers.NotificationType;

public class NotificationService {

    /**
     * Sends a notification to the currently active dashboard.
     * @param message The message to display
     * @param type The type of notification (INFO, WARNING, ERROR)
     */
    public static void sendNotification(String message, NotificationType type) {
        // Try Proprietaire Dashboard
        if (DashboardController.getInstance() != null) {
            DashboardController.getInstance().addNotification(message, type);
        } 
        // Try Investisseur Dashboard
        else if (InvestisseurDashboard.getInstance() != null) {
            InvestisseurDashboard.getInstance().addNotification(message, type);
        }
        
        System.out.println("Notification sent: [" + type + "] " + message);
    }

    public static void info(String message) {
        sendNotification(message, NotificationType.INFO);
    }

    public static void warn(String message) {
        sendNotification(message, NotificationType.WARNING);
    }

    public static void error(String message) {
        sendNotification(message, NotificationType.ERROR);
    }
}
