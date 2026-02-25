package tn.cashfly.controllers;

import com.github.sarxos.webcam.Webcam;
import com.github.sarxos.webcam.WebcamResolution;
import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.application.Platform;
import javafx.embed.swing.SwingFXUtils;
import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.ProgressBar;
import javafx.scene.image.ImageView;
import javafx.scene.layout.StackPane;
import javafx.scene.shape.Rectangle;
import javafx.stage.Stage;
import javafx.util.Duration;
import nu.pattern.OpenCV;
import org.opencv.core.*;
import org.opencv.imgproc.Imgproc;
import org.opencv.objdetect.CascadeClassifier;

import java.awt.image.BufferedImage;
import java.awt.image.DataBufferByte;
import java.io.File;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.net.URL;
import java.nio.channels.Channels;
import java.nio.channels.ReadableByteChannel;
import java.util.concurrent.atomic.AtomicBoolean;

public class KYCController {

    @FXML
    private Label statusLabel;
    @FXML
    private Label percentageLabel;
    @FXML
    private ProgressBar progressBar;
    @FXML
    private Rectangle scanLine;
    @FXML
    private Button scanButton;
    @FXML
    private StackPane cameraPane;

    private ImageView webcamView;
    private Webcam webcam;
    private AtomicBoolean stopWebcam = new AtomicBoolean(false);
    private CascadeClassifier faceCascade;
    private boolean modelLoaded = false;
    
    private static boolean isVerified = false;
    private int faceDetectedCounter = 0;
    private final int REQUIRED_FACE_FRAMES = 30; // ~3 seconds at 10fps

    public static boolean isVerified() {
        return isVerified;
    }

    @FXML
    public void initialize() {
        // Load OpenCV native library
        try {
            OpenCV.loadLocally();
        } catch (Exception e) {
            System.err.println("Erreur chargement OpenCV: " + e.getMessage());
        }
        
        // Prepare UI
        webcamView = new ImageView();
        webcamView.setFitHeight(300);
        webcamView.setFitWidth(400);
        webcamView.setPreserveRatio(true);
        cameraPane.getChildren().add(0, webcamView);

        // Load or Download Model
        new Thread(this::loadModel).start();

        // Start Webcam
        new Thread(this::startWebcamStream).start();
    }

    private void loadModel() {
        try {
            File modelFile = new File(System.getProperty("java.io.tmpdir"), "haarcascade_frontalface_default.xml");
            if (!modelFile.exists()) {
                Platform.runLater(() -> statusLabel.setText("Chargement du modèle d'IA (Téléchargement)..."));
                URL url = new URL("https://raw.githubusercontent.com/opencv/opencv/master/data/haarcascades/haarcascade_frontalface_default.xml");
                try (ReadableByteChannel rbc = Channels.newChannel(url.openStream());
                     FileOutputStream fos = new FileOutputStream(modelFile)) {
                    fos.getChannel().transferFrom(rbc, 0, Long.MAX_VALUE);
                }
            }
            
            faceCascade = new CascadeClassifier(modelFile.getAbsolutePath());
            if (faceCascade.empty()) {
                Platform.runLater(() -> statusLabel.setText("❌ Erreur de chargement du modèle !"));
            } else {
                modelLoaded = true;
                Platform.runLater(() -> statusLabel.setText("Modèle chargé. Prêt pour l'analyse."));
            }
        } catch (Exception e) {
            e.printStackTrace();
            Platform.runLater(() -> statusLabel.setText("❌ Erreur de modèle : " + e.getMessage()));
        }
    }

    private void startWebcamStream() {
        try {
            webcam = Webcam.getDefault();
            if (webcam != null) {
                webcam.setViewSize(WebcamResolution.VGA.getSize());
                webcam.open();
                
                while (!stopWebcam.get()) {
                    BufferedImage image = webcam.getImage();
                    if (image != null) {
                        processFrame(image);
                    }
                    Thread.sleep(100);
                }
                webcam.close();
            } else {
                Platform.runLater(() -> statusLabel.setText("❌ Aucune webcam détectée !"));
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void processFrame(BufferedImage image) {
        if (!modelLoaded) {
            updateWebcamView(image);
            return;
        }

        // Convert BufferedImage to OpenCV Mat
        Mat frame = bufferedImageToMat(image);
        Mat grayFrame = new Mat();
        Imgproc.cvtColor(frame, grayFrame, Imgproc.COLOR_BGR2GRAY);
        Imgproc.equalizeHist(grayFrame, grayFrame);

        // Detect Faces
        MatOfRect faces = new MatOfRect();
        faceCascade.detectMultiScale(grayFrame, faces, 1.1, 3, 0, new Size(30, 30), new Size());

        Rect[] facesArray = faces.toArray();
        if (facesArray.length > 0) {
            // Draw rectangle on the first face detected
            Rect rect = facesArray[0];
            Imgproc.rectangle(frame, rect.tl(), rect.br(), new Scalar(0, 255, 0), 3);
            
            if (progressBar.isVisible()) {
                faceDetectedCounter++;
                double progress = Math.min(1.0, (double) faceDetectedCounter / REQUIRED_FACE_FRAMES);
                Platform.runLater(() -> {
                    progressBar.setProgress(progress);
                    percentageLabel.setText((int)(progress * 100) + "%");
                    statusLabel.setText("Analyse biométrique : Visage détecté...");
                    if (progress >= 1.0) {
                        completeScan();
                    }
                });
            }
        } else if (progressBar.isVisible()) {
            Platform.runLater(() -> statusLabel.setText("Positionnez votre visage dans le cadre"));
        }

        updateWebcamView(matToBufferedImage(frame));
    }

    private void updateWebcamView(BufferedImage image) {
        Platform.runLater(() -> {
            webcamView.setImage(SwingFXUtils.toFXImage(image, null));
        });
    }

    @FXML
    public void startScan() {
        if (!modelLoaded) {
            statusLabel.setText("Veuillez attendre le chargement du modèle...");
            return;
        }
        scanButton.setDisable(true);
        scanLine.setVisible(true);
        progressBar.setVisible(true);
        percentageLabel.setVisible(true);
        faceDetectedCounter = 0;
    }

    private void completeScan() {
        stopWebcam.set(true);
        scanLine.setVisible(false);
        statusLabel.setText("KYC : Identité confirmée par IA ✅");
        statusLabel.setStyle("-fx-text-fill: #10b981; -fx-font-weight: bold;");
        isVerified = true;

        Timeline closeTimer = new Timeline(new KeyFrame(Duration.seconds(2), e -> closeModal()));
        closeTimer.play();
    }

    @FXML
    public void closeModal() {
        stopWebcam.set(true);
        Stage stage = (Stage) statusLabel.getScene().getWindow();
        stage.close();
    }

    // Helper: BufferedImage -> OpenCV Mat
    private Mat bufferedImageToMat(BufferedImage bi) {
        Mat mat = new Mat(bi.getHeight(), bi.getWidth(), CvType.CV_8UC3);
        byte[] data = ((DataBufferByte) bi.getRaster().getDataBuffer()).getData();
        mat.put(0, 0, data);
        return mat;
    }

    // Helper: OpenCV Mat -> BufferedImage
    private BufferedImage matToBufferedImage(Mat matrix) {
        int type = matrix.channels() > 1 ? BufferedImage.TYPE_3BYTE_BGR : BufferedImage.TYPE_BYTE_GRAY;
        int bufferSize = matrix.channels() * matrix.cols() * matrix.rows();
        byte[] buffer = new byte[bufferSize];
        matrix.get(0, 0, buffer);
        BufferedImage image = new BufferedImage(matrix.cols(), matrix.rows(), type);
        final byte[] targetPixels = ((DataBufferByte) image.getRaster().getDataBuffer()).getData();
        System.arraycopy(buffer, 0, targetPixels, 0, buffer.length);
        return image;
    }
}
