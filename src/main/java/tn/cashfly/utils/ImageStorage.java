package tn.cashfly.utils;

import javafx.scene.image.Image;

import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.StandardCopyOption;

/**
 * ImageStorage - Saves/loads images to XAMPP htdocs for web access
 */
public class ImageStorage {

    // ============================================
    // CONFIGURATION - MODIFY FOR YOUR XAMPP SETUP
    // ============================================

    /** XAMPP htdocs physical path */
    private static final String HTDOCS_PATH = "C:/xampp/htdocs/";

    /** Web base URL */
    private static final String WEB_BASE_URL = "http://localhost/";

    /** Subdirectory for event images */
    private static final String IMAGES_SUBDIR = "cashfly/images/events/";

    // ============================================
    // DERIVED PATHS
    // ============================================

    private static final String STORAGE_DIR = HTDOCS_PATH + IMAGES_SUBDIR;
    private static final String WEB_PATH_PREFIX = "/" + IMAGES_SUBDIR;

    // ============================================
    // PUBLIC API
    // ============================================

    /**
     * Saves image to XAMPP htdocs, returns web path for database
     */
    public static String saveImage(File sourceFile) throws IOException {
        if (sourceFile == null || !sourceFile.exists()) {
            throw new IOException("Source file is null or does not exist");
        }

        // Create directories
        File dir = new File(STORAGE_DIR);
        if (!dir.exists()) {
            if (!dir.mkdirs()) {
                throw new IOException("Failed to create directory: " + STORAGE_DIR);
            }
        }

        // Generate unique filename
        String originalName = sourceFile.getName();
        String extension = getFileExtension(originalName);

        if (!isValidImageExtension(extension)) {
            throw new IOException("Invalid image format. Allowed: png, jpg, jpeg, gif, bmp, webp");
        }

        String filename = System.currentTimeMillis() + "_" + sanitizeFilename(originalName);
        File targetFile = new File(dir, filename);

        // Copy file
        Files.copy(sourceFile.toPath(), targetFile.toPath(), StandardCopyOption.REPLACE_EXISTING);
        System.out.println("[ImageStorage] Saved to: " + targetFile.getAbsolutePath());

        // Return web path (stored in database)
        return WEB_PATH_PREFIX + filename;
    }

    /**
     * Deletes image from XAMPP htdocs
     */
    public static void deleteImage(String imagePath) {
        if (imagePath == null || imagePath.isEmpty()) return;

        try {
            String physicalPath = convertToPhysicalPath(imagePath);
            if (physicalPath == null) return;

            File file = new File(physicalPath);
            if (file.exists()) {
                if (file.delete()) {
                    System.out.println("[ImageStorage] Deleted: " + file.getAbsolutePath());
                }
            }
        } catch (Exception e) {
            System.err.println("[ImageStorage] Error deleting: " + e.getMessage());
        }
    }

    /**
     * Loads image from XAMPP htdocs (HTTP or local fallback)
     */
    public static Image loadImage(String imagePath) {
        if (imagePath == null || imagePath.isEmpty()) {
            return loadDefaultImage();
        }

        try {
            // Try HTTP first
            String fullUrl = buildFullUrl(imagePath);
            System.out.println("[ImageStorage] Loading: " + fullUrl);

            if (fullUrl.startsWith("http://")) {
                try {
                    URL url = new URL(fullUrl);
                    HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                    conn.setConnectTimeout(3000);
                    conn.setReadTimeout(3000);

                    if (conn.getResponseCode() == 200) {
                        try (InputStream is = conn.getInputStream()) {
                            Image img = new Image(is);
                            if (!img.isError()) return img;
                        }
                    }
                } catch (Exception e) {
                    System.out.println("[ImageStorage] HTTP failed, trying local file");
                }
            }

            // Fallback to local file
            String localPath = convertToPhysicalPath(imagePath);
            if (localPath != null) {
                File file = new File(localPath);
                if (file.exists()) {
                    try (FileInputStream fis = new FileInputStream(file)) {
                        return new Image(fis);
                    }
                }
            }

            return loadDefaultImage();

        } catch (Exception e) {
            System.err.println("[ImageStorage] Load failed: " + e.getMessage());
            return loadDefaultImage();
        }
    }

    /**
     * Gets full web URL for an image path
     */
    public static String getImageUrl(String imagePath) {
        return buildFullUrl(imagePath);
    }

    /**
     * Prints configuration for debugging
     */
    public static void printConfig() {
        System.out.println("╔════════════════════════════════════════════════════════╗");
        System.out.println("║  ImageStorage Configuration                            ║");
        System.out.println("╠════════════════════════════════════════════════════════╣");
        System.out.println("║ HTDOCS_PATH:  " + HTDOCS_PATH);
        System.out.println("║ WEB_URL:      " + WEB_BASE_URL);
        System.out.println("║ STORAGE:     " + STORAGE_DIR);
        System.out.println("╚════════════════════════════════════════════════════════╝");
    }

    // ============================================
    // PRIVATE HELPERS
    // ============================================

    private static String buildFullUrl(String imagePath) {
        if (imagePath == null) return null;
        if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
            return imagePath;
        }
        String path = imagePath.startsWith("/") ? imagePath.substring(1) : imagePath;
        return WEB_BASE_URL + path;
    }

    private static String convertToPhysicalPath(String imagePath) {
        if (imagePath == null) return null;

        String relative = imagePath;
        if (relative.startsWith(WEB_BASE_URL)) {
            relative = relative.substring(WEB_BASE_URL.length());
        }
        if (relative.startsWith("/")) {
            relative = relative.substring(1);
        }
        return HTDOCS_PATH + relative;
    }

    private static String getFileExtension(String filename) {
        int lastDot = filename.lastIndexOf(".");
        if (lastDot > 0) return filename.substring(lastDot).toLowerCase();
        return "";
    }

    private static boolean isValidImageExtension(String ext) {
        return ext.matches("\\.(png|jpg|jpeg|gif|bmp|webp)$");
    }

    private static String sanitizeFilename(String name) {
        return new File(name).getName().replaceAll("[^a-zA-Z0-9.-]", "_");
    }

    private static Image loadDefaultImage() {
        try {
            InputStream is = ImageStorage.class.getResourceAsStream("/tn/cashfly/Images/default-event.png");
            if (is != null) return new Image(is);
            return new Image(new ByteArrayInputStream(new byte[0]));
        } catch (Exception e) {
            return null;
        }
    }
}