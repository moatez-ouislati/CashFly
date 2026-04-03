package tn.cashfly.entities;

public class Utilisateur {

    private int id;
    private int cin;
    private String tel;
    private String nom;
    private String prenom;
    private String email;
    private String password;
    private String roles;

    private String yearsExperience;
    private String highestProfit;
    private String budget;
    private String faceImage;
    private java.sql.Timestamp dateCreation;


    // 🔒 1 = actif | 0 = bloqué
    private int active;


    public Utilisateur() {}
    public String getFaceImage() { return faceImage; }
    public void setFaceImage(String faceImage) { this.faceImage = faceImage; }

    public java.sql.Timestamp getDateCreation() { return dateCreation; }
    public void setDateCreation(java.sql.Timestamp dateCreation) { this.dateCreation = dateCreation; }

    /* ================= GETTERS / SETTERS ================= */

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public int getCin() { return cin; }
    public void setCin(int cin) { this.cin = cin; }

    public String getTel() { return tel; }
    public void setTel(String tel) { this.tel = tel; }

    public String getNom() { return nom; }
    public void setNom(String nom) { this.nom = nom; }

    public String getPrenom() { return prenom; }
    public void setPrenom(String prenom) { this.prenom = prenom; }

    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }

    public String getPassword() { return password; }
    public void setPassword(String password) { this.password = password; }

    public String getRoles() { return roles; }
    public void setRoles(String roles) { this.roles = roles; }

    public String getYearsExperience() { return yearsExperience; }
    public void setYearsExperience(String yearsExperience) {
        this.yearsExperience = yearsExperience;
    }

    public String getHighestProfit() { return highestProfit; }
    public void setHighestProfit(String highestProfit) {
        this.highestProfit = highestProfit;
    }

    public String getBudget() { return budget; }
    public void setBudget(String budget) { this.budget = budget; }

    public int getActive() { return active; }
    public void setActive(int active) { this.active = active; }

    /* ================= ROLES ================= */

    public boolean hasRole(String role) {
        return roles != null && roles.contains(role);
    }

    public boolean isAdmin() {
        return "administrateur".equals(roles);
    }

    public boolean isInvestisseur() {
        return "investisseur".equals(roles);
    }

    public boolean isProprietaire() {
        return "proprietaire".equals(roles);
    }

    /* ================= DEBUG (OPTIONNEL) ================= */
    @Override
    public String toString() {
        return "Utilisateur{" +
                "email='" + email + '\'' +
                ", roles='" + roles + '\'' +
                ", active=" + active +
                '}';
    }
}
