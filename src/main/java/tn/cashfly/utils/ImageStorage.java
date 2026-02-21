package tn.cashfly.utils;

import javafx.scene.image.Image;

import java.io.*;
import java.nio.channels.FileChannel;

public class ImageStorage {
    // Use absolute path for runtime, relative for FXML
    private static final String STORAGE_DIR = System.getProperty("user.dir") + "/src/main/resources/tn/cashfly/Images/Events/";
    private static final String RESOURCE_PATH = "/tn/cashfly/Images/Events/";

    public static String saveImage(File sourceFile) throws IOException {
        File dir = new File(STORAGE_DIR);
        if (!dir.exists()) dir.mkdirs();

        String ext = sourceFile.getName().substring(sourceFile.getName().lastIndexOf("."));
        String filename = System.currentTimeMillis() + ext;
        File target = new File(dir, filename);

        try (FileChannel sourceChannel = new FileInputStream(sourceFile).getChannel();
             FileChannel targetChannel = new FileOutputStream(target).getChannel()) {
            targetChannel.transferFrom(sourceChannel, 0, sourceChannel.size());
        }

        // Return path for FXML loading
        return RESOURCE_PATH + filename;
    }

    public static void deleteImage(String imagePath) {
        if (imagePath == null || imagePath.isEmpty()) return;
        try {
            String filename = imagePath.substring(imagePath.lastIndexOf("/") + 1);
            new File(STORAGE_DIR + filename).delete();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public static Image loadImage(String imagePath) {
        if (imagePath == null || imagePath.isEmpty()) {
            return loadDefaultImage();
        }
        try {
            // Try loading from resources first
            String resourcePath = imagePath.startsWith("/") ? imagePath : "/" + imagePath;
            java.io.InputStream is = ImageStorage.class.getResourceAsStream(resourcePath);

            if (is != null) {
                return new Image(is);
            }

            // Fallback to file system
            String filename = imagePath.substring(imagePath.lastIndexOf("/") + 1);
            File file = new File(STORAGE_DIR + filename);
            if (file.exists()) {
                return new Image(new FileInputStream(file));
            }

            return loadDefaultImage();
        } catch (Exception e) {
            System.out.println("Failed to load image: " + imagePath + " - " + e.getMessage());
            return loadDefaultImage();
        }
    }

    private static Image loadDefaultImage() {
        try {
            java.io.InputStream is = ImageStorage.class.getResourceAsStream("/tn/cashfly/Images/default-event.png");
            if (is != null) return new Image(is);

            // Create blank image as last resort
            return new Image(new ByteArrayInputStream(new byte[0]));
        } catch (Exception e) {
            return null;
        }
    }
}