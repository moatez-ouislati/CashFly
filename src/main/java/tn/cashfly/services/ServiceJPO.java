package tn.cashfly.services;

import tn.cashfly.entities.JPO;
import tn.cashfly.interfaces.Service;
import tn.cashfly.utils.MyDataBase;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ServiceJPO implements Service<JPO> {

    private final Connection connection;

    public ServiceJPO() {
        connection = MyDataBase.getInstance().getConnection();
    }

    @Override
    public void add(JPO jpo) throws SQLException {
        String query = "INSERT INTO journées_portes_ouvertes(titre, date_evenement, lieu, description, image_path, max_participants, current_participants, id_createur) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        PreparedStatement ps = connection.prepareStatement(query, Statement.RETURN_GENERATED_KEYS);
        ps.setString(1, jpo.getTitre());
        ps.setDate(2, new java.sql.Date(jpo.getDate_evenement().getTime()));
        ps.setString(3, jpo.getLieu());
        ps.setString(4, jpo.getDescription());
        ps.setString(5, jpo.getImagePath());
        ps.setInt(6, jpo.getMaxParticipants() > 0 ? jpo.getMaxParticipants() : 100);
        ps.setInt(7, 0);
        ps.setInt(8, jpo.getIdCreateur());
        ps.executeUpdate();

        ResultSet rs = ps.getGeneratedKeys();
        if (rs.next()) {
            jpo.setId_evenement(rs.getInt(1));
        }
        rs.close();
        ps.close();
    }

    @Override
    public void delete(JPO jpo) throws SQLException {
        // First delete all participations (Cascade)
        String deleteParticipationsQuery = "DELETE FROM participation_jpo WHERE id_evenement = ?";
        PreparedStatement psPart = connection.prepareStatement(deleteParticipationsQuery);
        psPart.setInt(1, jpo.getId_evenement());
        psPart.executeUpdate();
        psPart.close();

        // Then delete the event
        String query = "DELETE FROM journées_portes_ouvertes WHERE id_evenement = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setInt(1, jpo.getId_evenement());
        ps.executeUpdate();
        ps.close();
    }

    @Override
    public void update(JPO jpo) throws SQLException {
        String query = "UPDATE journées_portes_ouvertes SET titre = ?, date_evenement = ?, lieu = ?, description = ?, image_path = ?, max_participants = ? WHERE id_evenement = ?";
        PreparedStatement ps = connection.prepareStatement(query);
        ps.setString(1, jpo.getTitre());
        ps.setDate(2, new java.sql.Date(jpo.getDate_evenement().getTime()));
        ps.setString(3, jpo.getLieu());
        ps.setString(4, jpo.getDescription());
        ps.setString(5, jpo.getImagePath());
        ps.setInt(6, jpo.getMaxParticipants());
        ps.setInt(7, jpo.getId_evenement());
        ps.executeUpdate();
        ps.close();
    }

    @Override
    public List<JPO> getAll() throws SQLException {
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
            jpo.setImagePath(rs.getString("image_path"));
            jpo.setMaxParticipants(rs.getInt("max_participants"));
            jpo.setCurrentParticipants(rs.getInt("current_participants"));
            jpo.setIdCreateur(rs.getInt("id_createur"));
            jpoList.add(jpo);
        }
        rs.close();
        ps.close();
        return jpoList;
    }
}