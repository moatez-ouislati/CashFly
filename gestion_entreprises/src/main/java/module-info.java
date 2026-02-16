module com.example.gestion_entreprises {

    requires javafx.controls;
    requires javafx.fxml;
    requires java.sql;
    requires mysql.connector.j;

    opens com.example.gestion_entreprises.controllers to javafx.fxml;
    opens com.example.gestion_entreprises.entities to javafx.base;
    requires org.kordamp.ikonli.javafx;
    requires org.kordamp.ikonli.fontawesome;

    exports com.example.gestion_entreprises.main;
}
