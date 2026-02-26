module com.example.gestion_entreprises {

    requires javafx.controls;
    requires javafx.fxml;
    requires java.sql;
    requires mysql.connector.j;
    requires org.json;
    requires tess4j;
    requires java.desktop;


    opens com.example.gestion_entreprises.controllers to javafx.fxml;
    opens com.example.gestion_entreprises.entities to javafx.base;

    exports com.example.gestion_entreprises.main;
}
