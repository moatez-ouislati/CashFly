package tn.cashfly.entities;

import java.time.LocalDateTime;

public class OperationNote {
    private int idNote;
    private int idOperation;
    private String content; // Rich text content (HTML)
    private LocalDateTime createdAt;
    private LocalDateTime updatedAt;

    public OperationNote() {}

    public OperationNote(int idOperation, String content) {
        this.idOperation = idOperation;
        this.content = content;
        this.createdAt = LocalDateTime.now();
        this.updatedAt = LocalDateTime.now();
    }

    public OperationNote(int idNote, int idOperation, String content, LocalDateTime createdAt, LocalDateTime updatedAt) {
        this.idNote = idNote;
        this.idOperation = idOperation;
        this.content = content;
        this.createdAt = createdAt;
        this.updatedAt = updatedAt;
    }

    public int getIdNote() {
        return idNote;
    }

    public void setIdNote(int idNote) {
        this.idNote = idNote;
    }

    public int getIdOperation() {
        return idOperation;
    }

    public void setIdOperation(int idOperation) {
        this.idOperation = idOperation;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public LocalDateTime getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(LocalDateTime createdAt) {
        this.createdAt = createdAt;
    }

    public LocalDateTime getUpdatedAt() {
        return updatedAt;
    }

    public void setUpdatedAt(LocalDateTime updatedAt) {
        this.updatedAt = updatedAt;
    }

    @Override
    public String toString() {
        return "OperationNote{" +
                "idNote=" + idNote +
                ", idOperation=" + idOperation +
                ", createdAt=" + createdAt +
                ", updatedAt=" + updatedAt +
                '}';
    }
}

