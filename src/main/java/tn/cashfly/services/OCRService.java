package tn.cashfly.services;

import net.sourceforge.tess4j.ITesseract;
import net.sourceforge.tess4j.Tesseract;

import java.io.File;

public class OCRService {

    public String extractText(String path) throws Exception {

        ITesseract tesseract = new Tesseract();

        // 1. Try environment variable TESSDATA_PREFIX
        String tessDataPath = System.getenv("TESSDATA_PREFIX");
        
        // 2. If not found, try a default project relative path or the hardcoded one
        if (tessDataPath == null || tessDataPath.isEmpty()) {
            File projectTessData = new File("tessdata");
            if (projectTessData.exists()) {
                tessDataPath = projectTessData.getAbsolutePath();
            } else {
                // Fallback to a common default, but we'll check if it exists
                tessDataPath = "C:/Program Files/Tesseract-OCR/tessdata";
            }
        }

        File testFile = new File(tessDataPath);
        if (!testFile.exists()) {
            throw new java.io.IOException("Tesseract tessdata path not found at: " + tessDataPath);
        }

        // Check for specific language file to avoid native crash
        File langFile = new File(testFile, "eng.traineddata");
        if (!langFile.exists()) {
            throw new java.io.IOException("Tesseract language file 'eng.traineddata' not found in: " + tessDataPath);
        }

        tesseract.setDatapath(tessDataPath);
        tesseract.setLanguage("eng"); 

        return tesseract.doOCR(new File(path));
    }
}
