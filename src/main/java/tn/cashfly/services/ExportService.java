package tn.cashfly.services;

import com.itextpdf.text.Document;
import com.itextpdf.text.DocumentException;
import com.itextpdf.text.Font;
import com.itextpdf.text.Paragraph;
import com.itextpdf.text.pdf.PdfWriter;
import org.jsoup.Jsoup;
import tn.cashfly.entities.OPÉRATIONS;

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
}
