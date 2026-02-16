module Cashfly {

    requires javafx.graphics;
    requires javafx.controls;
    requires javafx.fxml;
    requires java.sql;
    requires java.mail;

    requires com.github.librepdf.openpdf; // ✅ PDF

    opens controllers to javafx.fxml;
    opens entities to javafx.base;
    opens test to javafx.graphics;
}
