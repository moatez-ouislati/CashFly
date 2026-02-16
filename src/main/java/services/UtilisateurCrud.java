package services;

import entities.MdpHash;
import entities.Utilisateur;
import tools.MyConnection;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class UtilisateurCrud implements IUtilisateurCrud {

    private final Connection cnx;

    public UtilisateurCrud() {
        cnx = MyConnection.getInstance().getCnx();
    }

    // ==============================
    // AJOUTER UTILISATEUR
    // ==============================
    @Override
    public void ajouter(Utilisateur u) {

        String sql = "INSERT INTO user (cin, tel, nom, prenom, email, password, roles) VALUES (?, ?, ?, ?, ?, ?, ?)";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setInt(1, u.getCin());
            pst.setString(2, u.getTel());
            pst.setString(3, u.getNom());
            pst.setString(4, u.getPrenom());
            pst.setString(5, u.getEmail());

            // HASH PASSWORD
            pst.setString(6, MdpHash.hashPassword(u.getPassword()));

            // DEFAULT ROLE
            pst.setString(7, u.getRoles() != null ? u.getRoles() : "[\"ROLE_USER\"]");

            pst.executeUpdate();
            System.out.println("✅ Utilisateur ajouté");

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // ==============================
    // AFFICHER TOUS
    // ==============================
    @Override
    public List<Utilisateur> afficher() {

        List<Utilisateur> list = new ArrayList<>();
        String sql = "SELECT * FROM user";

        try (PreparedStatement pst = cnx.prepareStatement(sql);
             ResultSet rs = pst.executeQuery()) {

            while (rs.next()) {
                list.add(mapUtilisateur(rs));
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }

        return list;
    }

    // ==============================
    // MODIFIER UTILISATEUR
    // ==============================
    @Override
    public void modifier(Utilisateur u) {

        String sql = "UPDATE user SET cin=?, tel=?, nom=?, prenom=?, email=?, roles=? WHERE id=?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setInt(1, u.getCin());
            pst.setString(2, u.getTel());
            pst.setString(3, u.getNom());
            pst.setString(4, u.getPrenom());
            pst.setString(5, u.getEmail());
            pst.setString(6, u.getRoles());
            pst.setInt(7, u.getId());

            pst.executeUpdate();
            System.out.println("✅ Utilisateur modifié");

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
    //==============================Linvestisseur +++++++++++++++++++++++++++++
    @Override
    public void modifierI(Utilisateur u) {

        String sql = """
        UPDATE user 
        SET cin=?,
            tel=?,
            nom=?,
            prenom=?,
            email=?,
            roles=?,
            yearsExperience=?,
            highestProfit=?,
            budget=?
        WHERE id=?
        """;

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setInt(1, u.getCin());
            pst.setString(2, u.getTel());
            pst.setString(3, u.getNom());
            pst.setString(4, u.getPrenom());
            pst.setString(5, u.getEmail());
            pst.setString(6, u.getRoles());

            pst.setString(7, u.getYearsExperience());
            pst.setString(8, u.getHighestProfit());
            pst.setString(9, u.getBudget());

            pst.setInt(10, u.getId());

            pst.executeUpdate();
            System.out.println("Utilisateur modifié (Investisseur)");

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // ==============================
    // CHANGER MOT DE PASSE
    // ==============================
    public void changerMotDePasse(int id, String newPassword) {

        String sql = "UPDATE user SET password=? WHERE id=?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setString(1, MdpHash.hashPassword(newPassword));
            pst.setInt(2, id);
            pst.executeUpdate();

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // ==============================
    // SUPPRIMER UTILISATEUR
    // ==============================
    @Override
    public void supprimer(int id) {

        String sql = "DELETE FROM user WHERE id=?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setInt(1, id);
            pst.executeUpdate();
            System.out.println("✅ Utilisateur supprimé");

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // ==============================
    // LOGIN SECURISE
    // ==============================
    public Utilisateur login(String email, String password) {

        String sql = "SELECT * FROM user WHERE email = ?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setString(1, email.trim());
            ResultSet rs = pst.executeQuery();

            if (rs.next()) {

                String storedHash = rs.getString("password");
                String inputHash = MdpHash.hashPassword(password);

                if (storedHash.equals(inputHash)) {
                    return mapUtilisateur(rs);
                }
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }

        return null;
    }

    // ==============================
    // FIND BY EMAIL
    // ==============================
    public Utilisateur findByEmail(String email) {

        String sql = "SELECT * FROM user WHERE email=?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setString(1, email);
            ResultSet rs = pst.executeQuery();

            if (rs.next()) {
                return mapUtilisateur(rs);
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }

        return null;
    }

    // ==============================
    // VERIFIER EXISTENCE
    // ==============================
    public boolean utilisateurExiste(int cin, String email) {

        String sql = "SELECT COUNT(*) FROM user WHERE cin=? OR email=?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setInt(1, cin);
            pst.setString(2, email);

            ResultSet rs = pst.executeQuery();
            return rs.next() && rs.getInt(1) > 0;

        } catch (SQLException e) {
            e.printStackTrace();
        }

        return false;
    }

    // ==============================
    // MAPPER UTILISATEUR
    // ==============================
    private Utilisateur mapUtilisateur(ResultSet rs) throws SQLException {

        Utilisateur u = new Utilisateur();

        u.setId(rs.getInt("id"));
        u.setCin(rs.getInt("cin"));
        u.setTel(rs.getString("tel"));
        u.setNom(rs.getString("nom"));
        u.setPrenom(rs.getString("prenom"));
        u.setEmail(rs.getString("email"));
        u.setPassword(rs.getString("password"));
        u.setRoles(rs.getString("roles"));

        u.setYearsExperience(rs.getString("yearsExperience"));
        u.setHighestProfit(rs.getString("highestProfit"));
        u.setBudget(rs.getString("budget"));

        return u;
    }

}
