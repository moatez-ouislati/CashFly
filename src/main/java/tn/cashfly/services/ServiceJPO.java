package tn.cashfly.services;

import tn.cashfly.entities.JPO;
import tn.cashfly.interfaces.Service;
import tn.cashfly.utils.CashFlyDB;
import java.sql.*;
import java.sql.Connection;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class ServiceJPO implements Service<JPO> {

    private Connection connection;

    public ServiceJPO() {
        connection = CashFlyDB.getInstance().getConnection();
    }

    @Override
    public void add(JPO jpo) throws SQLException {
//        String query = "INSERT INTO `journées_portes_ouvertes`(`titre`, `date_evenement`, `lieu`, `description`) VALUES (?, ?, ?, ?)";
//        PreparedStatement ps = connection.prepareStatement(query);
//        ps.setString(1, jpo.getTitre());
//        ps.setDate(2, new java.sql.Date(jpo.getDate_evenement().getTime()));
//        ps.setString(3, jpo.getLieu());
//        ps.setString(4, jpo.getDescription());
//        ps.executeUpdate();
        String query = "INSERT INTO `journées_portes_ouvertes`(`titre`, `date_evenement`, `lieu`, `description`) VALUES (?, ?, ?, ?)";

        // Add Statement.RETURN_GENERATED_KEYS to get the auto-generated ID
        PreparedStatement ps = connection.prepareStatement(query, Statement.RETURN_GENERATED_KEYS);

        ps.setString(1, jpo.getTitre());
        ps.setDate(2, new java.sql.Date(jpo.getDate_evenement().getTime()));
        ps.setString(3, jpo.getLieu());
        ps.setString(4, jpo.getDescription());

        ps.executeUpdate();

        // Retrieve the auto-generated ID
        ResultSet rs = ps.getGeneratedKeys();
        if (rs.next()) {
            int generatedId = rs.getInt(1);
            jpo.setId_evenement(generatedId);  // Update the JPO object with the new ID
        }

        rs.close();
        ps.close();
    }

    @Override
    public void delete(JPO jpo) throws SQLException {
        String query = "DELETE FROM journées_portes_ouvertes WHERE id_evenement =?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, jpo.getId_evenement());
        ps.executeUpdate();
    }

    @Override
    public void update(JPO jpo) throws SQLException {
        String query = "UPDATE journées_portes_ouvertes SET titre = ?, date_evenement = ?, lieu = ?, description = ? WHERE id_evenement = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setString(1, jpo.getTitre());
        ps.setDate(2, new java.sql.Date(jpo.getDate_evenement().getTime()));
        ps.setString(3, jpo.getLieu());
        ps.setString(4, jpo.getDescription());
        ps.setInt(5, jpo.getId_evenement());
        ps.executeUpdate();
    }

    @Override
    public List<JPO> getAll() throws SQLException{
        List<JPO> jpoList = new ArrayList<>();
        String query = "SELECT * FROM journées_portes_ouvertes";
        PreparedStatement ps = connection.prepareStatement(query);
        ResultSet rs = ps.executeQuery();
        while (rs.next()) {
            JPO jpo = new JPO();
            jpo.setId_evenement(rs.getInt("id_evenement"));
            jpo.setTitre(rs.getString("titre"));
            jpo.setDate_evenement(rs.getDate("date_evenement"));
            jpo.setLieu(rs.getString("lieu"));
            jpo.setDescription(rs.getString("description"));
            jpoList.add(jpo);
        }
        return jpoList;
    }
}