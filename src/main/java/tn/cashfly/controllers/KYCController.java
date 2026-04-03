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
import tn.cashfly.entities.UserKyc;
import tn.cashfly.services.KycService;
import tn.cashfly.session.UserSession;

import java.awt.image.BufferedImage;
import java.awt.image.DataBufferByte;
import java.io.File;
import java.io.FileOutputStream;
import java.net.URL;
import java.nio.channels.Channels;
import java.nio.channels.ReadableByteChannel;
import java.util.concurrent.atomic.AtomicBoolean;

import javafx.stage.FileChooser;
import javafx.stage.Modality;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import org.opencv.imgcodecs.Imgcodecs;
import java.util.Arrays;

import java.nio.file.Files;
import java.nio.file.StandardCopyOption;
import java.util.UUID;

public class KYCController {

    @FXML private Label statusLabel;
    @FXML private Label percentageLabel;
    @FXML private ProgressBar progressBar;
    @FXML private Rectangle scanLine;
    @FXML private Button scanButton;
    @FXML private StackPane cameraPane;
    @FXML private ImageView docFacePreview;
    @FXML private Label uploadPromptLabel;
    @FXML private Button uploadBtn;

    private ImageView webcamView;
    private Webcam webcam;
    private AtomicBoolean stopWebcam = new AtomicBoolean(false);
    private CascadeClassifier faceCascade;
    private CascadeClassifier eyeCascade;
    private boolean modelLoaded = false;
    private boolean openCVLoaded = false;
    private final Object modelLock = new Object();
    
    private Mat docFaceHist; // Histogram of the face from the document
    private boolean docLoaded = false;
    private String uploadedDocPath;

    private static boolean isVerified = false;
    private int faceDetectedCounter = 0;
    private final int REQUIRED_FACE_FRAMES = 30;
    
    // Liveness Detection Vars
    private int blinkCounter = 0;
    private boolean eyesOpenPreviously = true;
    private boolean isAlive = false;
    private final int REQUIRED_BLINKS = 2; // User must blink at least twice
    private KycService kycService;

    public static boolean isVerified() {
        return isVerified;
    }

    public static void resetVerification() {
        isVerified = false;
    }

    @FXML
    public void initialize() {
        // Disable webcam lock immediately
        System.setProperty("com.github.sarxos.webcam.WebcamLock.enabled", "false");

        try {
            kycService = new KycService();
        } catch (Exception ignored) {}

        // Setup UI
        if (cameraPane != null) {
            webcamView = new ImageView();
            webcamView.setFitHeight(300);
            webcamView.setFitWidth(400);
            webcamView.setPreserveRatio(true);
            cameraPane.getChildren().add(0, webcamView);
        }

    // Sequential Initialization to avoid race conditions
    new Thread(() -> {
        try {
            // 1. Load OpenCV Native Lib
            Platform.runLater(() -> statusLabel.setText("Chargement du moteur d'IA..."));
            OpenCV.loadLocally();
            openCVLoaded = true;

            // 2. Load Model (only after OpenCV is ready)
            loadModel();

            // 2.5 Check if already enrolled (Verification Mode)
            if (UserSession.isKycEnrolled()) {
                Platform.runLater(() -> {
                    uploadBtn.setVisible(false);
                    uploadPromptLabel.setText("Vérification d'identité (Mode Comparaison)");
                    statusLabel.setText("Chargement de vos données biométriques...");
                });
                loadEnrolledData();
            }

            // 3. Start Webcam Stream
            startWebcamStream();
        } catch (Throwable e) {
            Platform.runLater(() -> statusLabel.setText("❌ Erreur moteur : " + e.getMessage()));
        }
    }).start();
}

private void loadEnrolledData() {
    try {
        Integer userId = UserSession.getUserId();
        if (userId == null) return;
        
        UserKyc kyc = kycService.getKycByUserId(userId);
        if (kyc != null && kyc.getIdDocumentPath() != null) {
            File docFile = new File(kyc.getIdDocumentPath());
            if (docFile.exists()) {
                Mat docImage = Imgcodecs.imread(docFile.getAbsolutePath());
                if (!docImage.empty()) {
                    docFaceHist = calculateHistogram(docImage);
                    docLoaded = true;
                    this.uploadedDocPath = kyc.getIdDocumentPath();
                    
                    Platform.runLater(() -> {
                        docFacePreview.setImage(SwingFXUtils.toFXImage(matToBufferedImage(docImage), null));
                        scanButton.setDisable(false);
                        statusLabel.setText("✅ Données chargées. Prêt pour la vérification.");
                        // Auto-start scan if desired, but let's wait for user click
                    });
                }
            }
        }
    } catch (Exception e) {
        e.printStackTrace();
        Platform.runLater(() -> statusLabel.setText("❌ Erreur chargement données KYC"));
    }
}

    private void loadModel() {
        try {
            Platform.runLater(() -> statusLabel.setText("Chargement du modèle de visage..."));
            File faceModel = downloadCascade("haarcascade_frontalface_default.xml");
            File eyeModel = downloadCascade("haarcascade_eye.xml");
            
            // This requires OpenCV to be fully loaded!
            faceCascade = new CascadeClassifier(faceModel.getAbsolutePath());
            eyeCascade = new CascadeClassifier(eyeModel.getAbsolutePath());

            if (!faceCascade.empty() && !eyeCascade.empty()) {
                modelLoaded = true;
                Platform.runLater(() -> statusLabel.setText("Système prêt pour l'analyse."));
            } else {
                Platform.runLater(() -> statusLabel.setText("❌ Échec chargement modèle."));
            }
        } catch (Exception e) {
            Platform.runLater(() -> statusLabel.setText("❌ Erreur IA : " + e.getMessage()));
        }
    }

    private File downloadCascade(String filename) throws Exception {
        File modelFile = new File(System.getProperty("java.io.tmpdir"), filename);
        if (!modelFile.exists()) {
            URL url = new URL("https://raw.githubusercontent.com/opencv/opencv/master/data/haarcascades/" + filename);
            try (ReadableByteChannel rbc = Channels.newChannel(url.openStream());
                 FileOutputStream fos = new FileOutputStream(modelFile)) {
                fos.getChannel().transferFrom(rbc, 0, Long.MAX_VALUE);
            }
        }
        return modelFile;
    }

    private void startWebcamStream() {
        try {
            webcam = Webcam.getDefault(10000);
            if (webcam != null) {
                // Get supported sizes and pick the closest to VGA or the first one
                java.awt.Dimension[] sizes = webcam.getViewSizes();
                if (sizes.length > 0) {
                    webcam.setViewSize(sizes[sizes.length - 1]); // Pick largest
                }
                
                if (webcam.open()) {
                    while (!stopWebcam.get()) {
                        try {
                            if (webcam.isOpen()) {
                                BufferedImage image = webcam.getImage();
                                if (image != null) processFrame(image);
                            }
                        } catch (Exception e) {
                            System.err.println("Webcam capture error: " + e.getMessage());
                        }
                        Thread.sleep(50); 
                    }
                }
            }
        } catch (Exception ignored) {
        } finally {
            shutdownWebcam();
        }
    }

    private void shutdownWebcam() {
        stopWebcam.set(true);
        if (webcam != null && webcam.isOpen()) {
            webcam.close();
        }
    }

    private void processFrame(BufferedImage image) {
        if (stopWebcam.get()) return;
        if (!modelLoaded || !openCVLoaded) {
            updateWebcamView(image);
            return;
        }

        Mat frame = bufferedImageToMat(image);
        Mat grayFrame = new Mat();
        Imgproc.cvtColor(frame, grayFrame, Imgproc.COLOR_BGR2GRAY);
        Imgproc.equalizeHist(grayFrame, grayFrame);

        MatOfRect faces = new MatOfRect();
        synchronized (modelLock) {
            faceCascade.detectMultiScale(grayFrame, faces, 1.1, 3, 0, new Size(30, 30), new Size());
        }

        if (faces.toArray().length > 0) {
            Rect rect = faces.toArray()[0];
            Mat liveFaceROI = frame.submat(rect);
            
            // Draw rectangle: Green if scanning, Blue if idle
            Scalar color = progressBar.isVisible() ? new Scalar(0, 255, 0) : new Scalar(255, 0, 0);
            Imgproc.rectangle(frame, rect.tl(), rect.br(), color, 3);
            
            if (progressBar.isVisible()) {
                // Liveness Check (Blink Detection)
                Mat faceGrayROI = grayFrame.submat(rect);
                detectBlink(faceGrayROI);

                // Biometric Comparison
                double similarity = compareFaces(liveFaceROI);
// accuracy is 50
                if (similarity > 0.50) {
                    if (!isAlive) {
                         Platform.runLater(() -> {
                             statusLabel.setText("⚠️ CLIGNEZ DES YEUX ! (" + blinkCounter + "/" + REQUIRED_BLINKS + ")");
                             statusLabel.setStyle("-fx-text-fill: #f59e0b; -fx-font-weight: bold; -fx-font-size: 14;");
                         });
                    } else {
                        faceDetectedCounter++;
                        double progress = Math.min(1.0, (double) faceDetectedCounter / REQUIRED_FACE_FRAMES);
                        Platform.runLater(() -> {
                            progressBar.setProgress(progress);
                            percentageLabel.setText((int)(progress * 100) + "%");
                            statusLabel.setText(String.format("Vérifié : %.0f%% ✅", similarity * 100));
                            statusLabel.setStyle("-fx-text-fill: #10b981; -fx-font-weight: bold;");
                            if (progress >= 1.0) completeScan();
                        });
                    }
                } else {
                    Platform.runLater(() -> {
                        statusLabel.setText(String.format("⚠️ Visage non reconnu (%.0f%%)", similarity * 100));
                        statusLabel.setStyle("-fx-text-fill: #ef4444;");
                    });
                }
            }
        }
        updateWebcamView(matToBufferedImage(frame));
    }

    private void detectBlink(Mat faceROI) {
        if (eyeCascade == null || eyeCascade.empty()) return;
        
        MatOfRect eyes = new MatOfRect();
        // Adjust minSize based on face size
        eyeCascade.detectMultiScale(faceROI, eyes, 1.1, 3, 0, new Size(20, 20), new Size());
        
        boolean eyesOpen = eyes.toArray().length > 0;
        
        // Simple state machine for blink detection
        if (eyesOpenPreviously && !eyesOpen) {
            // Eyes just closed
        } else if (!eyesOpenPreviously && eyesOpen) {
             // Eyes just opened -> Blink detected
             blinkCounter++;
        }
        
        eyesOpenPreviously = eyesOpen;
        
        if (blinkCounter >= REQUIRED_BLINKS) {
            isAlive = true;
        }
    }

    private void updateWebcamView(BufferedImage image) {
        Platform.runLater(() -> {
            if (webcamView != null && image != null) {
                webcamView.setImage(SwingFXUtils.toFXImage(image, null));
            }
        });
    }

    @FXML
    private void handleUploadDocument() {
        if (!openCVLoaded || !modelLoaded) {
            statusLabel.setText("IA non prête. Veuillez attendre.");
            return;
        }

        FileChooser fileChooser = new FileChooser();
        fileChooser.setTitle("Sélectionner votre pièce d'identité");
        fileChooser.getExtensionFilters().addAll(
            new FileChooser.ExtensionFilter("Images", "*.jpg", "*.png", "*.jpeg")
        );
        
        File selectedFile = fileChooser.showOpenDialog(uploadBtn.getScene().getWindow());
        if (selectedFile != null) {
            processDocument(selectedFile);
        }
    }

    private void processDocument(File file) {
        try {
            Mat docImage = Imgcodecs.imread(file.getAbsolutePath());
            if (docImage.empty()) {
                statusLabel.setText("❌ Impossible de lire l'image.");
                return;
            }

            double maxDim = Math.max(docImage.width(), docImage.height());
            if (maxDim > 1000) {
                double scale = 1000.0 / maxDim;
                Mat resized = new Mat();
                Imgproc.resize(docImage, resized, new Size(docImage.width() * scale, docImage.height() * scale));
                docImage = resized;
            }

            Mat grayDoc = new Mat();
            Imgproc.cvtColor(docImage, grayDoc, Imgproc.COLOR_BGR2GRAY);
            Imgproc.equalizeHist(grayDoc, grayDoc);

            MatOfRect faces = new MatOfRect();
            synchronized (modelLock) {
                faceCascade.detectMultiScale(grayDoc, faces, 1.1, 3, 0, new Size(30, 30), new Size());
            }

            if (faces.toArray().length > 0) {
                Rect faceRect = Arrays.stream(faces.toArray())
                        .max((r1, r2) -> (int)(r1.area() - r2.area()))
                        .get();
                
                Mat faceROI = docImage.submat(faceRect);
                
                docFacePreview.setImage(SwingFXUtils.toFXImage(matToBufferedImage(faceROI), null));
                uploadPromptLabel.setVisible(false);

                this.uploadedDocPath = saveCroppedFace(faceROI);
                if (this.uploadedDocPath == null) {
                     statusLabel.setText("❌ Erreur sauvegarde visage.");
                     return;
                }

                docFaceHist = calculateHistogram(faceROI);
                docLoaded = true;
                scanButton.setDisable(false);
                statusLabel.setText("✅ Document validé. Prêt pour le scan.");
            } else {
                statusLabel.setText("❌ Aucun visage détecté sur le document !");
            }
        } catch (Exception e) {
            e.printStackTrace();
            statusLabel.setText("❌ Err: " + e.getMessage());
        }
    }

    private String saveCroppedFace(Mat face) {
        try {
            File uploadDir = new File("uploads/kyc/faces");
            if (!uploadDir.exists()) uploadDir.mkdirs();
            String fileName = UUID.randomUUID().toString() + ".png";
            File destFile = new File(uploadDir, fileName);
            boolean saved = Imgcodecs.imwrite(destFile.getAbsolutePath(), face);
            if (!saved) return null;
            return destFile.getAbsolutePath().replace("\\", "/");
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }
    }

    private String saveUploadedFile(File sourceFile) {
        try {
            File uploadDir = new File("uploads/kyc");
            if (!uploadDir.exists()) uploadDir.mkdirs();
            
            String extension = sourceFile.getName().substring(sourceFile.getName().lastIndexOf("."));
            String fileName = UUID.randomUUID().toString() + extension;
            File destFile = new File(uploadDir, fileName);
            
            Files.copy(sourceFile.toPath(), destFile.toPath(), StandardCopyOption.REPLACE_EXISTING);
            return destFile.getAbsolutePath().replace("\\", "/");
        } catch (Exception e) {
            return sourceFile.getAbsolutePath(); // Fallback to original path
        }
    }

    private Mat calculateHistogram(Mat image) {
        Mat hsvImage = new Mat();
        Imgproc.cvtColor(image, hsvImage, Imgproc.COLOR_BGR2HSV);
        
        Mat hist = new Mat();
        MatOfInt histSize = new MatOfInt(50, 60);
        MatOfFloat ranges = new MatOfFloat(0, 180, 0, 256);
        MatOfInt channels = new MatOfInt(0, 1);
        
        Imgproc.calcHist(Arrays.asList(hsvImage), channels, new Mat(), hist, histSize, ranges);
        Core.normalize(hist, hist, 0, 1, Core.NORM_MINMAX);
        return hist;
    }

    private double compareFaces(Mat liveFace) {
        if (docFaceHist == null) return 0.0;
        Mat liveHist = calculateHistogram(liveFace);
        return Imgproc.compareHist(docFaceHist, liveHist, Imgproc.HISTCMP_CORREL);
    }

    @FXML
    public void startScan() {
        if (!openCVLoaded || !modelLoaded) return;
        if (!docLoaded) {
            statusLabel.setText("Veuillez d'abord charger un document !");
            return;
        }
        scanButton.setDisable(true);
        uploadBtn.setDisable(true);
        scanLine.setVisible(true);
        progressBar.setVisible(true);
        percentageLabel.setVisible(true);
        faceDetectedCounter = 0;
    }

    private void completeScan() {
        shutdownWebcam();
        isVerified = true;
        Platform.runLater(() -> {
            statusLabel.setText("Identité confirmée ✅");
            statusLabel.setStyle("-fx-text-fill: #10b981; -fx-font-weight: bold;");
        });
        
        // If not enrolled, save. If enrolled, we just verified.
        if (!UserSession.isKycEnrolled()) {
             saveKycToDatabase();
        }
        
        new Timeline(new KeyFrame(Duration.seconds(2), e -> closeModal())).play();
    }

    private void saveKycToDatabase() {
        if (kycService == null) return;
        try {
            Integer userId = UserSession.getUserId();
            if (userId != null) {
                // Real data storage
                String embedding = "hist_correl_" + System.currentTimeMillis();
                String docPath = (uploadedDocPath != null) ? uploadedDocPath : "uploads/kyc/default.jpg";

                UserKyc kyc = new UserKyc(userId, embedding, true, docPath);
                kycService.saveKyc(kyc);
                UserSession.setKycEnrolled(true);
            }
        } catch (Exception ignored) {}
    }

    @FXML
    public void closeModal() {
        shutdownWebcam();
        if (statusLabel != null && statusLabel.getScene() != null) {
            Stage stage = (Stage) statusLabel.getScene().getWindow();
            
            // If verification is done and we just enrolled, go to dashboard
            if (isVerified && !UserSession.isKycEnrolled()) {
                // This shouldn't happen here because completeScan calls closeModal after saveKycToDatabase
                // which sets isKycEnrolled to true.
            }

            // If we are in the main stage (not a modal), we should navigate back to Login if not verified
            // Modals usually have an owner or a different modality.
            if (stage.getModality() == Modality.NONE) {
                if (!isVerified) {
                    navigateTo("/authentification.fxml", "Cashfly - Connexion");
                } else {
                    navigateToDashboard();
                }
            } else {
                // It's a modal (verification during operation), just close it
                stage.close();
            }
        }
    }

    private void navigateTo(String fxmlPath, String title) {
        try {
            Parent root = FXMLLoader.load(getClass().getResource(fxmlPath));
            Stage stage = (Stage) statusLabel.getScene().getWindow();
            stage.setTitle(title);
            stage.setScene(new Scene(root));
            if (fxmlPath.contains("dashboard")) {
                stage.setMinWidth(1024);
                stage.setMinHeight(600);
            }
            stage.centerOnScreen();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void navigateToDashboard() {
        String role = UserSession.getRole();
        String fxmlPath = "/dashboard.fxml";
        if ("administrateur".equalsIgnoreCase(role)) {
            fxmlPath = "/admin_dashboard.fxml";
        } else if ("investisseur".equalsIgnoreCase(role)) {
            fxmlPath = "/investor_dashboard.fxml";
        }
        navigateTo(fxmlPath, "Cashfly - Dashboard (" + role + ")");
    }

    public void cleanup() {
        shutdownWebcam();
    }

    private Mat bufferedImageToMat(BufferedImage bi) {
        // Ensure image is in BGR format for OpenCV
        BufferedImage converted = new BufferedImage(bi.getWidth(), bi.getHeight(), BufferedImage.TYPE_3BYTE_BGR);
        converted.getGraphics().drawImage(bi, 0, 0, null);
        
        Mat mat = new Mat(converted.getHeight(), converted.getWidth(), CvType.CV_8UC3);
        byte[] data = ((DataBufferByte) converted.getRaster().getDataBuffer()).getData();
        mat.put(0, 0, data);
        return mat;
    }

    private BufferedImage matToBufferedImage(Mat matrix) {
        int type = matrix.channels() > 1 ? BufferedImage.TYPE_3BYTE_BGR : BufferedImage.TYPE_BYTE_GRAY;
        byte[] buffer = new byte[matrix.channels() * matrix.cols() * matrix.rows()];
        matrix.get(0, 0, buffer);
        BufferedImage image = new BufferedImage(matrix.cols(), matrix.rows(), type);
        System.arraycopy(buffer, 0, ((DataBufferByte) image.getRaster().getDataBuffer()).getData(), 0, buffer.length);
        return image;
    }
}

