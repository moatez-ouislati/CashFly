package com.example.gestion_entreprises.services;

import net.sourceforge.tess4j.ITesseract;
import net.sourceforge.tess4j.Tesseract;

import java.io.File;

public class OCRService {

    public String extractText(String path) throws Exception {

        ITesseract tesseract = new Tesseract();

        // ⚠️ Mets ici le chemin réel vers ton dossier tessdata
        tesseract.setDatapath("C:/Program Files/Tesseract-OCR/tessdata");

        tesseract.setLanguage("eng"); // ou "eng"

        return tesseract.doOCR(new File(path));
    }
}