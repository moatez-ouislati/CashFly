package tn.cashfly.entities;

import java.time.LocalDateTime;

public class UserKyc {
    private int userId;
    private String faceEmbedding; // Store as JSON or Base64 string
    private boolean isVerified;
    private LocalDateTime verifiedAt;
    private String idDocumentPath;
    private LocalDateTime createdAt;
    private LocalDateTime updatedAt;

    public UserKyc() {}

    public UserKyc(int userId, String faceEmbedding, boolean isVerified, String idDocumentPath) {
        this.userId = userId;
        this.faceEmbedding = faceEmbedding;
        this.isVerified = isVerified;
        this.idDocumentPath = idDocumentPath;
        this.createdAt = LocalDateTime.now();
        this.updatedAt = LocalDateTime.now();
        if (isVerified) {
            this.verifiedAt = LocalDateTime.now();
        }
    }

    // Getters and Setters
    public int getUserId() { return userId; }
    public void setUserId(int userId) { this.userId = userId; }

    public String getFaceEmbedding() { return faceEmbedding; }
    public void setFaceEmbedding(String faceEmbedding) { this.faceEmbedding = faceEmbedding; }

    public boolean isVerified() { return isVerified; }
    public void setVerified(boolean verified) { isVerified = verified; }

    public LocalDateTime getVerifiedAt() { return verifiedAt; }
    public void setVerifiedAt(LocalDateTime verifiedAt) { this.verifiedAt = verifiedAt; }

    public String getIdDocumentPath() { return idDocumentPath; }
    public void setIdDocumentPath(String idDocumentPath) { this.idDocumentPath = idDocumentPath; }

    public LocalDateTime getCreatedAt() { return createdAt; }
    public void setCreatedAt(LocalDateTime createdAt) { this.createdAt = createdAt; }

    public LocalDateTime getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(LocalDateTime updatedAt) { this.updatedAt = updatedAt; }
}

