# **Cashlfy**

Cashlfy is a modern financial management desktop application built with **Java**, **JDBC**, and **JavaFX**, designed to help users track expenses, manage budgets and investments, and optimize cash flow efficiently.

## Table of Contents

* [Installation](#installation)
* [Usage](#usage)
* [Contributing](#contributing)
  

## **Introduction**

Cashlfy simplifies business finance management. It provides an intuitive interface for tracking income, expenses, and cash flow, investments and giving users clear insights into their financial health.

## Prerequisites

* Java JDK 17 or higher
* Maven (optional, if using Maven for build)
* Windows, macOS, or Linux operating system
* MySQL (depending on your JDBC database configuration)

## **Installation**

To install and run Cashlfy:

1. Clone the repository:
   **`git clone https://github.com/moatez-ouislati/cashlfy.git`**
2. Navigate to the project directory:
   **`cd cashlfy`**
3. Build the project:

   * If using Maven: **`mvn clean install`**
   * If not using Maven, compile manually: **`javac -d bin src/**/*.java`**
4. Run the application:
   **`java -cp bin;lib/* com.cashlfy.Main`** (adjust classpath based on OS and libraries)
5. Make sure your JDBC database is running and configured in `config.properties` or `DBConnection.java`.

## **Usage**

1. Launch Cashlfy using the run command above.
2. Use the JavaFX interface to add, view, and manage financial records.
3. Monitor cash flow and generate simple reports.
4. Modify source code to extend features if desired.

## **Contributing**

If you'd like to contribute to Cashlfy:

1. Fork the repository.
2. Create a new branch for your changes.
3. Implement your feature or bug fix.
4. Write tests (JUnit recommended).
5. Run tests to ensure they pass.
6. Commit and push your changes.
7. Submit a pull request.


## **Authors and Acknowledgment**

Cashlfy was created by **CashFly.org**



## **FAQ**

**Q:** What is Cashlfy?
**A:** A Java-based application for business finance management.

**Q:** How do I install Cashlfy?
**A:** Follow the installation steps above.

**Q:** How do I run Cashlfy?
**A:** Run the JavaFX main class with the correct classpath and database configuration.

**Q:** How can I contribute to Cashlfy?
**A:** Follow the contributing guidelines above.


## **Changelog**

* **0.1.0:** Initial release with JavaFX UI
* **0.1.1:** Added JDBC database connectivity
* **0.2.0:** Completed interface implementation and unit tests
* **0.2.1:** Optimized database queries and improved UI responsiveness

## **Contact**

For questions or comments about our application, contact **[CashFly](mailto:contact@cashlfy.com)**.
