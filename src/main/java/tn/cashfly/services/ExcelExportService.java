package tn.cashfly.services;

import org.apache.poi.ss.usermodel.*;
import org.apache.poi.ss.util.CellRangeAddress;
import org.apache.poi.xssf.usermodel.XSSFWorkbook;
import tn.cashfly.entities.EventStatistics;
import tn.cashfly.entities.JPO;
import tn.cashfly.entities.ParticipantInfo;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

/**
 * Service for exporting event participant data to Excel
 */
public class ExcelExportService {

    private static final String[] HEADERS = {
            "N°", "ID Participation", "Nom Complet", "Email", "Rôle",
            "Date Inscription", "Statut Inscription", "Badge Généré",
            "Délai Inscription", "ID Utilisateur"
    };

    private static final String[] STAT_HEADERS = {
            "Métrique", "Valeur"
    };

    /**
     * Export participants to Excel file with custom directory
     * @param event The JPO event
     * @param participants List of participants
     * @param statistics Event statistics
     * @param customDir Custom directory to save the file (null for default)
     * @return Path to the generated file
     */
    public String exportParticipantsToExcel(JPO event, List<ParticipantInfo> participants,
                                            EventStatistics statistics, File customDir) throws IOException {

        Workbook workbook = new XSSFWorkbook();

        // Create main sheet
        Sheet mainSheet = workbook.createSheet("Participants");

        // Create styles
        CellStyle headerStyle = createHeaderStyle(workbook);
        CellStyle titleStyle = createTitleStyle(workbook);
        CellStyle dataStyle = createDataStyle(workbook);
        CellStyle statsStyle = createStatsStyle(workbook);
        CellStyle dateStyle = createDateStyle(workbook);

        int rowNum = 0;

        // === TITLE SECTION ===
        Row titleRow = mainSheet.createRow(rowNum++);
        Cell titleCell = titleRow.createCell(0);
        titleCell.setCellValue("RAPPORT DES PARTICIPANTS - " + event.getTitre().toUpperCase());
        titleCell.setCellStyle(titleStyle);
        mainSheet.addMergedRegion(new CellRangeAddress(0, 0, 0, HEADERS.length - 1));

        // Event details
        Row eventRow1 = mainSheet.createRow(rowNum++);
        eventRow1.createCell(0).setCellValue("Date de l'événement:");
        eventRow1.createCell(1).setCellValue(formatDate(event.getDate_evenement()));
        eventRow1.createCell(2).setCellValue("Lieu:");
        eventRow1.createCell(3).setCellValue(event.getLieu());

        Row eventRow2 = mainSheet.createRow(rowNum++);
        eventRow2.createCell(0).setCellValue("Capacité maximale:");
        eventRow2.createCell(1).setCellValue(event.getMaxParticipants());
        eventRow2.createCell(2).setCellValue("Places disponibles:");
        eventRow2.createCell(3).setCellValue(event.getSpotsLeft());

        rowNum++; // Empty row

        // === STATISTICS SECTION ===
        Row statsTitleRow = mainSheet.createRow(rowNum++);
        Cell statsTitleCell = statsTitleRow.createCell(0);
        statsTitleCell.setCellValue("STATISTIQUES");
        statsTitleCell.setCellStyle(statsStyle);
        mainSheet.addMergedRegion(new CellRangeAddress(rowNum - 1, rowNum - 1, 0, 3));

        Row statHeaderRow = mainSheet.createRow(rowNum++);
        for (int i = 0; i < STAT_HEADERS.length; i++) {
            Cell cell = statHeaderRow.createCell(i);
            cell.setCellValue(STAT_HEADERS[i]);
            cell.setCellStyle(headerStyle);
        }

        // Statistics data
        addStatsRow(mainSheet, rowNum++, "Total Participants", statistics.getTotalParticipants(), dataStyle);
        addStatsRow(mainSheet, rowNum++, "Confirmés", statistics.getConfirmed(), dataStyle);
        addStatsRow(mainSheet, rowNum++, "En liste d'attente", statistics.getWaiting(), dataStyle);
        addStatsRow(mainSheet, rowNum++, "Badges générés", statistics.getBadgesGenerated(), dataStyle);
        addStatsRow(mainSheet, rowNum++, "Taux de confirmation", statistics.getAttendanceRate() + "%", dataStyle);

        rowNum++; // Empty row

        // === PARTICIPANTS TABLE ===
        Row tableTitleRow = mainSheet.createRow(rowNum++);
        Cell tableTitleCell = tableTitleRow.createCell(0);
        tableTitleCell.setCellValue("LISTE DES PARTICIPANTS");
        tableTitleCell.setCellStyle(statsStyle);
        mainSheet.addMergedRegion(new CellRangeAddress(rowNum - 1, rowNum - 1, 0, HEADERS.length - 1));

        // Headers
        Row headerRow = mainSheet.createRow(rowNum++);
        for (int i = 0; i < HEADERS.length; i++) {
            Cell cell = headerRow.createCell(i);
            cell.setCellValue(HEADERS[i]);
            cell.setCellStyle(headerStyle);
        }

        // Data rows
        SimpleDateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy HH:mm");
        int participantNum = 1;

        for (ParticipantInfo p : participants) {
            Row row = mainSheet.createRow(rowNum++);

            row.createCell(0).setCellValue(participantNum++);
            row.createCell(1).setCellValue(p.getParticipationId());
            row.createCell(2).setCellValue(p.getNomComplet());
            row.createCell(3).setCellValue(p.getEmail());
            row.createCell(4).setCellValue(p.getRole());

            Cell dateCell = row.createCell(5);
            if (p.getDateInscription() != null) {
                dateCell.setCellValue(dateFormat.format(p.getDateInscription()));
            }

            row.createCell(6).setCellValue(p.getConfirmationStatus());
            row.createCell(7).setCellValue(p.getStatutBadge());
            row.createCell(8).setCellValue(p.getDelaiInscription());
            row.createCell(9).setCellValue(p.getUserId());

            // Apply data style to all cells
            for (int i = 0; i < HEADERS.length; i++) {
                if (row.getCell(i) != null) {
                    row.getCell(i).setCellStyle(dataStyle);
                }
            }
        }

        // Auto-size columns
        for (int i = 0; i < HEADERS.length; i++) {
            mainSheet.autoSizeColumn(i);
        }

        // === DETAILS SHEET ===
        Sheet detailsSheet = workbook.createSheet("Détails par Statut");
        createDetailsByStatusSheet(detailsSheet, participants, headerStyle, dataStyle, dateFormat);

        // Save file to custom or default directory
        String filePath = saveWorkbook(workbook, event, customDir);
        workbook.close();

        return filePath;
    }

    /**
     * Legacy method - uses default directory
     */
    public String exportParticipantsToExcel(JPO event, List<ParticipantInfo> participants,
                                            EventStatistics statistics) throws IOException {
        return exportParticipantsToExcel(event, participants, statistics, null);
    }

    private void createDetailsByStatusSheet(Sheet sheet, List<ParticipantInfo> participants,
                                            CellStyle headerStyle, CellStyle dataStyle,
                                            SimpleDateFormat dateFormat) {
        int rowNum = 0;

        // Group by status
        List<ParticipantInfo> confirmed = participants.stream()
                .filter(p -> "confirmé".equalsIgnoreCase(p.getStatut()))
                .toList();
        List<ParticipantInfo> waiting = participants.stream()
                .filter(p -> "en_attente".equalsIgnoreCase(p.getStatut()))
                .toList();

        // Confirmed section
        Row confirmedTitle = sheet.createRow(rowNum++);
        confirmedTitle.createCell(0).setCellValue("PARTICIPANTS CONFIRMÉS (" + confirmed.size() + ")");
        confirmedTitle.getCell(0).setCellStyle(headerStyle);
        rowNum = addParticipantList(sheet, rowNum, confirmed, dateFormat, dataStyle);

        rowNum++; // Empty row

        // Waiting list section
        Row waitingTitle = sheet.createRow(rowNum++);
        waitingTitle.createCell(0).setCellValue("LISTE D'ATTENTE (" + waiting.size() + ")");
        waitingTitle.getCell(0).setCellStyle(headerStyle);
        rowNum = addParticipantList(sheet, rowNum, waiting, dateFormat, dataStyle);
    }

    private int addParticipantList(Sheet sheet, int startRow, List<ParticipantInfo> list,
                                   SimpleDateFormat dateFormat, CellStyle dataStyle) {
        int rowNum = startRow;

        // Sub-headers
        Row subHeader = sheet.createRow(rowNum++);
        String[] subHeaders = {"N°", "Nom", "Email", "Date d'inscription", "Badge"};
        for (int i = 0; i < subHeaders.length; i++) {
            Cell cell = subHeader.createCell(i);
            cell.setCellValue(subHeaders[i]);
            cell.setCellStyle(dataStyle);
        }

        // Data
        int num = 1;
        for (ParticipantInfo p : list) {
            Row row = sheet.createRow(rowNum++);
            row.createCell(0).setCellValue(num++);
            row.createCell(1).setCellValue(p.getNomComplet());
            row.createCell(2).setCellValue(p.getEmail());
            row.createCell(3).setCellValue(p.getDateInscription() != null ?
                    dateFormat.format(p.getDateInscription()) : "N/A");
            row.createCell(4).setCellValue(p.getStatutBadge());
        }

        return rowNum;
    }

    private void addStatsRow(Sheet sheet, int rowNum, String metric, Object value, CellStyle style) {
        Row row = sheet.createRow(rowNum);
        row.createCell(0).setCellValue(metric);
        row.createCell(1).setCellValue(value.toString());
        row.getCell(0).setCellStyle(style);
        row.getCell(1).setCellStyle(style);
    }

    private CellStyle createHeaderStyle(Workbook workbook) {
        CellStyle style = workbook.createCellStyle();
        Font font = workbook.createFont();
        font.setBold(true);
        font.setColor(IndexedColors.WHITE.getIndex());
        style.setFont(font);
        style.setFillForegroundColor(IndexedColors.DARK_BLUE.getIndex());
        style.setFillPattern(FillPatternType.SOLID_FOREGROUND);
        style.setBorderBottom(BorderStyle.THIN);
        style.setBorderTop(BorderStyle.THIN);
        style.setBorderLeft(BorderStyle.THIN);
        style.setBorderRight(BorderStyle.THIN);
        style.setAlignment(HorizontalAlignment.CENTER);
        return style;
    }

    private CellStyle createTitleStyle(Workbook workbook) {
        CellStyle style = workbook.createCellStyle();
        Font font = workbook.createFont();
        font.setBold(true);
        font.setFontHeightInPoints((short) 16);
        font.setColor(IndexedColors.DARK_BLUE.getIndex());
        style.setFont(font);
        style.setAlignment(HorizontalAlignment.CENTER);
        return style;
    }

    private CellStyle createDataStyle(Workbook workbook) {
        CellStyle style = workbook.createCellStyle();
        style.setBorderBottom(BorderStyle.THIN);
        style.setBorderTop(BorderStyle.THIN);
        style.setBorderLeft(BorderStyle.THIN);
        style.setBorderRight(BorderStyle.THIN);
        return style;
    }

    private CellStyle createStatsStyle(Workbook workbook) {
        CellStyle style = workbook.createCellStyle();
        Font font = workbook.createFont();
        font.setBold(true);
        font.setFontHeightInPoints((short) 12);
        font.setColor(IndexedColors.WHITE.getIndex());
        style.setFont(font);
        style.setFillForegroundColor(IndexedColors.GREY_50_PERCENT.getIndex());
        style.setFillPattern(FillPatternType.SOLID_FOREGROUND);
        style.setAlignment(HorizontalAlignment.CENTER);
        return style;
    }

    private CellStyle createDateStyle(Workbook workbook) {
        CellStyle style = createDataStyle(workbook);
        style.setDataFormat(workbook.createDataFormat().getFormat("dd/mm/yyyy hh:mm"));
        return style;
    }

    private String formatDate(Date date) {
        if (date == null) return "N/A";
        return new SimpleDateFormat("EEEE dd MMMM yyyy 'à' HH:mm").format(date);
    }

    private String saveWorkbook(Workbook workbook, JPO event, File customDir) throws IOException {
        Path exportDir;

        if (customDir != null && customDir.exists() && customDir.isDirectory()) {
            exportDir = customDir.toPath();
        } else {
            // Default directory
            String userHome = System.getProperty("user.home");
            exportDir = Paths.get(userHome, "CashFly", "Exports");
        }

        Files.createDirectories(exportDir);

        String timestamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(new Date());
        String safeEventName = event.getTitre().replaceAll("[^a-zA-Z0-9]", "_");
        String filename = "Participants_" + safeEventName + "_" + timestamp + ".xlsx";

        String filepath = exportDir.resolve(filename).toString();

        try (FileOutputStream fos = new FileOutputStream(filepath)) {
            workbook.write(fos);
        }

        return filepath;
    }
}