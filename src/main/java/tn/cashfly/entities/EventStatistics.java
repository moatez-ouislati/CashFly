package tn.cashfly.entities;

/**
 * Statistics for an event
 */
public class EventStatistics {
    private int totalParticipants;
    private int confirmed;
    private int waiting;
    private int badgesGenerated;

    public int getTotalParticipants() { return totalParticipants; }
    public void setTotalParticipants(int totalParticipants) { this.totalParticipants = totalParticipants; }

    public int getConfirmed() { return confirmed; }
    public void setConfirmed(int confirmed) { this.confirmed = confirmed; }

    public int getWaiting() { return waiting; }
    public void setWaiting(int waiting) { this.waiting = waiting; }

    public int getBadgesGenerated() { return badgesGenerated; }
    public void setBadgesGenerated(int badgesGenerated) { this.badgesGenerated = badgesGenerated; }

    public int getAttendanceRate() {
        if (totalParticipants == 0) return 0;
        return (confirmed * 100) / totalParticipants;
    }
}