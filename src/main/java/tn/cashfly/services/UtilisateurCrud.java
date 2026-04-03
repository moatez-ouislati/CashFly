package tn.cashfly.services;

import tn.cashfly.entities.MdpHash;
import tn.cashfly.entities.Utilisateur;
import tn.cashfly.tools.MyConnection;

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

        String sql = "INSERT INTO utilisateurs (cin, tel, nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?, ?)";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setInt(1, u.getCin());
            pst.setString(2, u.getTel());
            pst.setString(3, u.getNom());
            pst.setString(4, u.getPrenom());
            pst.setString(5, u.getEmail());

            // HASH PASSWORD
            pst.setString(6, MdpHash.hashPassword(u.getPassword()));

            // ROLE
            String dbRole = "investisseur"; // default
            if (u.getRoles() != null) {
                if (u.getRoles().contains("ROLE_ADMIN") || u.getRoles().equals("administrateur")) dbRole = "administrateur";
                else if (u.getRoles().contains("ROLE_PROPRIETAIRE") || u.getRoles().equals("proprietaire")) dbRole = "proprietaire";
            }
            pst.setString(7, dbRole);

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
        String sql = "SELECT * FROM utilisateurs";

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

        String sql = """
        UPDATE utilisateurs
        SET cin = ?,
            tel = ?,
            nom = ?,
            prenom = ?,
            email = ?,
            role = ?
        WHERE id_utilisateur = ?
    """;

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setInt(1, u.getCin());
            pst.setString(2, u.getTel());
            pst.setString(3, u.getNom());
            pst.setString(4, u.getPrenom());
            pst.setString(5, u.getEmail());
            pst.setString(6, u.getRoles());
            pst.setInt(7, u.getId());

            pst.executeUpdate();
            System.out.println("✅ Utilisateur modifié (UPDATE)");

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
    //==============================Linvestisseur +++++++++++++++++++++++++++++
    @Override
    public void modifierI(Utilisateur u) {

        String sql = """
        UPDATE utilisateurs 
        SET cin=?,
            tel=?,
            nom=?,
            prenom=?,
            email=?,
            role=?,
            yearsExperience=?,
            highestProfit=?,
            budget=?
        WHERE id_utilisateur=?
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

        String sql = "UPDATE utilisateurs SET mot_de_passe=? WHERE id_utilisateur=?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setString(1, MdpHash.hashPassword(newPassword));
            pst.setInt(2, id);
            pst.executeUpdate();

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void ajouterReclamationCin(int userId, String nouveauCin, String motif) {
        String ddl = """
                CREATE TABLE IF NOT EXISTS user_reclamations_cin (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    ancien_cin VARCHAR(32),
                    nouveau_cin VARCHAR(32) NOT NULL,
                    motif TEXT NOT NULL,
                    statut VARCHAR(20) DEFAULT 'PENDING',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE
                )
                """;

        try (Statement st = cnx.createStatement()) {
            st.executeUpdate(ddl);
        } catch (SQLException e) {
            e.printStackTrace();
        }

        String sql = "INSERT INTO user_reclamations_cin (user_id, ancien_cin, nouveau_cin, motif) VALUES (?, ?, ?, ?)";
        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, userId);
            pst.setString(2, String.valueOf(getCinById(userId)));
            pst.setString(3, nouveauCin);
            pst.setString(4, motif);
            pst.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private int getCinById(int userId) {
        String sql = "SELECT cin FROM utilisateurs WHERE id_utilisateur = ?";
        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, userId);
            try (ResultSet rs = pst.executeQuery()) {
                if (rs.next()) {
                    return rs.getInt("cin");
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return 0;
    }

    // ==============================
    // SUPPRIMER UTILISATEUR
    // ==============================
    @Override
    public void supprimer(int id) {

        String sql = "DELETE FROM utilisateurs WHERE id_utilisateur=?";

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

        String sql = "SELECT * FROM utilisateurs WHERE email = ?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setString(1, email.trim());
            ResultSet rs = pst.executeQuery();

            if (rs.next()) {

                String storedHash = rs.getString("mot_de_passe");

                if (MdpHash.verifyPassword(password, storedHash)) {
                    if (MdpHash.isLegacyMd5(storedHash)) {
                        String newHash = MdpHash.hashPassword(password);
                        try (PreparedStatement up = cnx.prepareStatement(
                                "UPDATE utilisateurs SET mot_de_passe=? WHERE id_utilisateur=?")) {
                            up.setString(1, newHash);
                            up.setInt(2, rs.getInt("id_utilisateur"));
                            up.executeUpdate();
                        } catch (SQLException ignored) {}
                    }
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

        String sql = "SELECT * FROM utilisateurs WHERE email=?";

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

        String sql = "SELECT COUNT(*) FROM utilisateurs WHERE cin=? OR email=?";

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

        u.setId(rs.getInt("id_utilisateur"));
        u.setCin(rs.getInt("cin"));
        u.setTel(rs.getString("tel"));
        u.setNom(rs.getString("nom"));
        u.setPrenom(rs.getString("prenom"));
        u.setEmail(rs.getString("email"));
        u.setPassword(rs.getString("mot_de_passe"));
        u.setRoles(rs.getString("role"));

        // 🛠️ EL FIX HNA: Zid hatha bech ya9ra el path mel Base!
        u.setFaceImage(rs.getString("face_image"));

        u.setYearsExperience(rs.getString("yearsExperience"));
        u.setHighestProfit(rs.getString("highestProfit"));
        u.setBudget(rs.getString("budget"));
        u.setActive(rs.getInt("active"));

        return u;
    }

    public void resetPasswordByEmail(String email, String hashedPassword) {
        String sql = "UPDATE utilisateurs SET mot_de_passe=? WHERE email=?";
        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setString(1, hashedPassword);
            pst.setString(2, email);
            pst.executeUpdate();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    public boolean updatePasswordByEmail(String email, String hashedPassword) {

        String sql = "UPDATE utilisateurs SET mot_de_passe=? WHERE email=?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {

            pst.setString(1, hashedPassword);
            pst.setString(2, email);

            return pst.executeUpdate() > 0;

        } catch (Exception e) {
            e.printStackTrace();
            return false;
        }
    }
    
    public Utilisateur findById(int id) {
        String sql = "SELECT * FROM utilisateurs WHERE id_utilisateur = ?";
        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, id);
            try (ResultSet rs = pst.executeQuery()) {
                if (rs.next()) {
                    return mapUtilisateur(rs);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }
    // ==============================
// 🔒 BLOCK USER
// ==============================
    public void blockUser(int id) {

        String sql = "UPDATE utilisateurs SET active = 0 WHERE id_utilisateur = ?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, id);
            pst.executeUpdate();
            System.out.println("⛔ Utilisateur bloqué");
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
    // ==============================
// 🔓 UNBLOCK USER
// ==============================
    public void unblockUser(int id) {

        String sql = "UPDATE utilisateurs SET active = 1 WHERE id_utilisateur = ?";

        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setInt(1, id);
            pst.executeUpdate();
            System.out.println("✅ Utilisateur débloqué");
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
    public void updateFaceImage(int id, String imagePath) {
        String sql = "UPDATE utilisateurs SET face_image = ? WHERE id_utilisateur = ?";
        try (PreparedStatement pst = cnx.prepareStatement(sql)) {
            pst.setString(1, imagePath);
            pst.setInt(2, id);
            pst.executeUpdate();
            System.out.println("✅ Image faciale enregistrée dans la base");
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

}
