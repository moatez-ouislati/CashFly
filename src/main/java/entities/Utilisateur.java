package entities;

public class Utilisateur {

    private int id;
    private int cin;
    private String tel;
    private String nom;
    private String prenom;
    private String email;
    private String password; // HASHED
    private String roles; // JSON ["ROLE_USER"]
    private String yearsExperience;
    private String highestProfit;
    private String budget;

    public String getYearsExperience() { return yearsExperience; }
    public void setYearsExperience(String y) { this.yearsExperience = y; }

    public String getHighestProfit() { return highestProfit; }
    public void setHighestProfit(String h) { this.highestProfit = h; }

    public String getBudget() { return budget; }
    public void setBudget(String b) { this.budget = b; }

    public Utilisateur() {}

    public Utilisateur(int id, int cin, String tel, String nom, String prenom,
                       String email, String password, String roles) {
        this.id = id;
        this.cin = cin;
        this.tel = tel;
        this.nom = nom;
        this.prenom = prenom;
        this.email = email;
        this.password = password;
        this.roles = roles;
    }

    public Utilisateur(int cin, String tel, String nom, String prenom,
                       String email, String password, String roles) {
        this.cin = cin;
        this.tel = tel;
        this.nom = nom;
        this.prenom = prenom;
        this.email = email;
        this.password = password;
        this.roles = roles;
    }

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

    public boolean hasRole(String role) {
        return roles != null && roles.contains(role);
    }

    public boolean isAdmin() {
        return hasRole("ROLE_ADMIN");
    }

    public boolean isInvestisseur() {
        return hasRole("ROLE_INVESTISSEUR");
    }

    public boolean isProprietaire() {
        return hasRole("ROLE_PROPRIETAIRE");
    }


    @Override
    public String toString() {
        return "Utilisateur{" +
                "id=" + id +
                ", cin=" + cin +
                ", tel='" + tel + '\'' +
                ", nom='" + nom + '\'' +
                ", prenom='" + prenom + '\'' +
                ", email='" + email + '\'' +
                ", roles='" + roles + '\'' +
                '}';
    }
}
