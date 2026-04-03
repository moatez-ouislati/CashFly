package tn.cashfly.jpo.utils;

import javafx.scene.image.Image;

import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.file.Files;
import java.nio.file.StandardCopyOption;

public class ImageStorage {

    private static final String HTDOCS_PATH = "C:/xampp/htdocs/";
    private static final String WEB_BASE_URL = "http://localhost/";
    private static final String IMAGES_SUBDIR = "cashfly/images/events/";

    private static final String STORAGE_DIR = HTDOCS_PATH + IMAGES_SUBDIR;
    private static final String WEB_PATH_PREFIX = "/" + IMAGES_SUBDIR;

    public static String saveImage(File sourceFile) throws IOException {
        if (sourceFile == null || !sourceFile.exists()) {
            throw new IOException("Source file is null or does not exist");
        }

        File dir = new File(STORAGE_DIR);

        try {
            String srcPath = sourceFile.getCanonicalPath().replace('\\', '/').toLowerCase();
            String dirPath = dir.getCanonicalPath().replace('\\', '/').toLowerCase();
            if (srcPath.startsWith(dirPath)) {
                String originalName = sourceFile.getName();
                String extension = getFileExtension(originalName);
                if (!isValidImageExtension(extension)) {
                    throw new IOException("Invalid image format. Allowed: png, jpg, jpeg, gif, bmp, webp");
                }
                String safeName = sanitizeFilename(originalName);
                return WEB_PATH_PREFIX + safeName;
            }
        } catch (IOException ignored) {
        }

        if (!dir.exists()) {
            if (!dir.mkdirs()) {
                throw new IOException("Failed to create directory: " + STORAGE_DIR);
            }
        }

        String originalName = sourceFile.getName();
        String extension = getFileExtension(originalName);
        if (!isValidImageExtension(extension)) {
            throw new IOException("Invalid image format. Allowed: png, jpg, jpeg, gif, bmp, webp");
        }
        String filename = System.currentTimeMillis() + "_" + sanitizeFilename(originalName);
        File targetFile = new File(dir, filename);
        Files.copy(sourceFile.toPath(), targetFile.toPath(), StandardCopyOption.REPLACE_EXISTING);
        return WEB_PATH_PREFIX + filename;
    }

    public static void deleteImage(String imagePath) {
        if (imagePath == null || imagePath.isEmpty()) return;
        try {
            String physicalPath = convertToPhysicalPath(imagePath);
            if (physicalPath == null) return;
            File file = new File(physicalPath);
            if (file.exists()) {
                file.delete();
            }
        } catch (Exception e) {
            System.err.println("[ImageStorage] Error deleting: " + e.getMessage());
        }
    }

    public static Image loadImage(String imagePath) {
        if (imagePath == null || imagePath.isEmpty()) {
            return loadDefaultImage();
        }
        try {
            String fullUrl = buildFullUrl(imagePath);
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
                }
            }
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

    public static String getImageUrl(String imagePath) {
        return buildFullUrl(imagePath);
    }

    public static void printConfig() {
    }

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
