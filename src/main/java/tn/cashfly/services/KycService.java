package tn.cashfly.services;

import tn.cashfly.entities.UserKyc;
import tn.cashfly.utils.MyDataBase;

import java.sql.*;
import java.time.LocalDateTime;

public class KycService {

    private final Connection cnx;

    public KycService() {
        this.cnx = MyDataBase.getInstance().getCnx();
        createTableIfNotExists();
    }

    private void createTableIfNotExists() {
        String sql = "CREATE TABLE IF NOT EXISTS user_kyc (" +
                "user_id INT PRIMARY KEY, " +
                "face_embedding BLOB, " +
                "is_verified TINYINT(1) DEFAULT 0, " +
                "verified_at TIMESTAMP NULL, " +
                "id_document_path VARCHAR(255), " +
                "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, " +
                "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, " +
                "FOREIGN KEY (user_id) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE" +
                ")";
        try (Statement stmt = cnx.createStatement()) {
            stmt.execute(sql);
        } catch (SQLException e) {
            System.err.println("Error creating user_kyc table: " + e.getMessage());
        }
    }

    public void saveKyc(UserKyc kyc) throws SQLException {
        String checkSql = "SELECT user_id FROM user_kyc WHERE user_id = ?";
        try (PreparedStatement checkPs = cnx.prepareStatement(checkSql)) {
            checkPs.setInt(1, kyc.getUserId());
            try (ResultSet rs = checkPs.executeQuery()) {
                if (rs.next()) {
                    updateKyc(kyc);
                } else {
                    insertKyc(kyc);
                }
            }
        }
    }

    private void insertKyc(UserKyc kyc) throws SQLException {
        String sql = "INSERT INTO user_kyc (user_id, face_embedding, is_verified, verified_at, id_document_path) VALUES (?, ?, ?, ?, ?)";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, kyc.getUserId());
            // Convert String embedding to bytes for BLOB
            ps.setBytes(2, kyc.getFaceEmbedding() != null ? kyc.getFaceEmbedding().getBytes() : null);
            ps.setBoolean(3, kyc.isVerified());
            ps.setTimestamp(4, kyc.getVerifiedAt() != null ? Timestamp.valueOf(kyc.getVerifiedAt()) : null);
            ps.setString(5, kyc.getIdDocumentPath());
            ps.executeUpdate();
        }
    }

    private void updateKyc(UserKyc kyc) throws SQLException {
        String sql = "UPDATE user_kyc SET face_embedding = ?, is_verified = ?, verified_at = ?, id_document_path = ?, updated_at = ? WHERE user_id = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setBytes(1, kyc.getFaceEmbedding() != null ? kyc.getFaceEmbedding().getBytes() : null);
            ps.setBoolean(2, kyc.isVerified());
            ps.setTimestamp(3, kyc.getVerifiedAt() != null ? Timestamp.valueOf(kyc.getVerifiedAt()) : Timestamp.valueOf(LocalDateTime.now()));
            ps.setString(4, kyc.getIdDocumentPath());
            ps.setTimestamp(5, Timestamp.valueOf(LocalDateTime.now()));
            ps.setInt(6, kyc.getUserId());
            ps.executeUpdate();
        }
    }

    public UserKyc getKycByUserId(int userId) throws SQLException {
        String sql = "SELECT * FROM user_kyc WHERE user_id = ?";
        try (PreparedStatement ps = cnx.prepareStatement(sql)) {
            ps.setInt(1, userId);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    UserKyc kyc = new UserKyc();
                    kyc.setUserId(rs.getInt("user_id"));
                    // Convert bytes back to String for the entity
                    byte[] bytes = rs.getBytes("face_embedding");
                    kyc.setFaceEmbedding(bytes != null ? new String(bytes) : null);
                    kyc.setVerified(rs.getBoolean("is_verified"));
                    Timestamp verifiedAt = rs.getTimestamp("verified_at");
                    if (verifiedAt != null) kyc.setVerifiedAt(verifiedAt.toLocalDateTime());
                    kyc.setIdDocumentPath(rs.getString("id_document_path"));
                    
                    Timestamp createdAt = rs.getTimestamp("created_at");
                    if (createdAt != null) kyc.setCreatedAt(createdAt.toLocalDateTime());
                    
                    Timestamp updatedAt = rs.getTimestamp("updated_at");
                    if (updatedAt != null) kyc.setUpdatedAt(updatedAt.toLocalDateTime());
                    
                    return kyc;
                }
            }
        }
        return null;
    }
}

