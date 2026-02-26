package tn.cashfly.services;

import com.itextpdf.text.Document;
import com.itextpdf.text.DocumentException;
import com.itextpdf.text.Font;
import com.itextpdf.text.Paragraph;
import com.itextpdf.text.pdf.PdfWriter;
import org.jsoup.Jsoup;
import tn.cashfly.entities.OPÉRATIONS;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.time.format.DateTimeFormatter;

public class ExportService {

    public void exportNoteToPDF(OPÉRATIONS operation, String htmlContent, String filePath) throws DocumentException, IOException {
        Document document = new Document();
        PdfWriter.getInstance(document, new FileOutputStream(filePath));
        document.open();

        // Styles
        Font titleFont = new Font(Font.FontFamily.HELVETICA, 18, Font.BOLD);
        Font subtitleFont = new Font(Font.FontFamily.HELVETICA, 12, Font.ITALIC);
        Font bodyFont = new Font(Font.FontFamily.HELVETICA, 11, Font.NORMAL);

        // Header
        document.add(new Paragraph("RAPPORT DE NOTE FINANCIÈRE - CASHFLY", titleFont));
        document.add(new Paragraph("Généré le : " + java.time.LocalDateTime.now().format(DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm")), subtitleFont));
        document.add(new Paragraph(" "));
        document.add(new Paragraph("----------------------------------------------------------------------------------------------------------------------------------"));
        document.add(new Paragraph(" "));

        // Operation Details
        document.add(new Paragraph("DÉTAILS DE L'OPÉRATION", titleFont));
        document.add(new Paragraph("ID Opération : #" + operation.getIdOperation(), bodyFont));
        document.add(new Paragraph("Type : " + operation.getType().toUpperCase(), bodyFont));
        document.add(new Paragraph("Montant : " + operation.getMontant() + " TND", bodyFont));
        document.add(new Paragraph("Catégorie : " + operation.getCategorie(), bodyFont));
        document.add(new Paragraph("Description : " + operation.getDescription(), bodyFont));
        document.add(new Paragraph(" "));

        // Note Content
        document.add(new Paragraph("NOTES ET COMMENTAIRES", titleFont));
        document.add(new Paragraph(" "));
        
        // Clean HTML to plain text for basic PDF export
        String plainText = Jsoup.parse(htmlContent).text();
        document.add(new Paragraph(plainText, bodyFont));

        document.add(new Paragraph(" "));
        document.add(new Paragraph("----------------------------------------------------------------------------------------------------------------------------------"));
        document.add(new Paragraph("Document certifié par le système de gestion Cashfly.", subtitleFont));

        document.close();
    }

    public String generateOperationInvoice(OPÉRATIONS operation) throws DocumentException, IOException {
        String fileName = "facture_" + operation.getReference() + "_" + System.currentTimeMillis() + ".pdf";
        String uploadsDir = "uploads/factures/";
        File dir = new File(uploadsDir);
        if (!dir.exists()) dir.mkdirs();
        
        String filePath = uploadsDir + fileName;
        Document document = new Document();
        PdfWriter.getInstance(document, new FileOutputStream(filePath));
        document.open();

        // Styles
        Font titleFont = new Font(Font.FontFamily.HELVETICA, 20, Font.BOLD);
        Font labelFont = new Font(Font.FontFamily.HELVETICA, 12, Font.BOLD);
        Font valueFont = new Font(Font.FontFamily.HELVETICA, 12, Font.NORMAL);
        Font footerFont = new Font(Font.FontFamily.HELVETICA, 10, Font.ITALIC);

        // Header
        document.add(new Paragraph("FACTURE D'OPÉRATION - CASHFLY", titleFont));
        document.add(new Paragraph(" "));
        document.add(new Paragraph("Référence : " + operation.getReference(), labelFont));
        document.add(new Paragraph("Date : " + operation.getDateOperation().format(DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm")), valueFont));
        document.add(new Paragraph(" "));
        document.add(new Paragraph("----------------------------------------------------------------------------------------------------------------------------------"));
        document.add(new Paragraph(" "));

        // Details
        document.add(new Paragraph("Détails du Client/Entreprise :", labelFont));
        if (operation.getTresorerie() != null) {
            document.add(new Paragraph("Trésorerie : " + operation.getTresorerie().getNomCompte(), valueFont));
            document.add(new Paragraph("Compte N° : " + (operation.getTresorerie().getNumeroCompte() != null ? operation.getTresorerie().getNumeroCompte() : operation.getTresorerie().getRib()), valueFont));
        }
        document.add(new Paragraph(" "));

        document.add(new Paragraph("Informations de la Transaction :", labelFont));
        document.add(new Paragraph("Type : " + operation.getType().name().toUpperCase(), valueFont));
        document.add(new Paragraph("Catégorie : " + operation.getCategorie(), valueFont));
        document.add(new Paragraph("Description : " + operation.getDescription(), valueFont));
        document.add(new Paragraph("Facture N° : " + operation.getFacture(), valueFont));
        document.add(new Paragraph(" "));

        document.add(new Paragraph("Montant Total :", labelFont));
        document.add(new Paragraph(String.format("%.2f TND", operation.getMontant()), titleFont));
        document.add(new Paragraph(" "));

        document.add(new Paragraph("----------------------------------------------------------------------------------------------------------------------------------"));
        document.add(new Paragraph("Document généré automatiquement par Cashfly. Certifié par KYC.", footerFont));

        document.close();
        return filePath;
    }
}
