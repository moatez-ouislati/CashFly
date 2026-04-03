-- Database: cashflydb
CREATE DATABASE IF NOT EXISTS cashflydb;
USE cashflydb;

-- 1. Table Utilisateurs
CREATE TABLE IF NOT EXISTS `utilisateurs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cin` INT UNIQUE,
    `tel` VARCHAR(20),
    `nom` VARCHAR(100),
    `prenom` VARCHAR(100),
    `email` VARCHAR(150) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `roles` VARCHAR(50) DEFAULT 'investisseur',
    `yearsExperience` VARCHAR(50),
    `highestProfit` VARCHAR(50),
    `budget` VARCHAR(50),
    `faceImage` VARCHAR(255),
    `active` INT DEFAULT 1,
    `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Table Entreprises
CREATE TABLE IF NOT EXISTS `entreprises` (
    `id_entreprise` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(150) NOT NULL,
    `secteur` VARCHAR(100),
    `forme_juridique` VARCHAR(100),
    `date_creation` DATE,
    `capital` DECIMAL(15, 2),
    `id_proprietaire` INT,
    `latitude` DOUBLE,
    `longitude` DOUBLE,
    `adresse` TEXT,
    FOREIGN KEY (`id_proprietaire`) REFERENCES `utilisateurs`(`id`) ON DELETE SET NULL
);

-- 3. Table OPÉRATIONS
CREATE TABLE IF NOT EXISTS `OPÉRATIONS` (
    `id_operation` INT AUTO_INCREMENT PRIMARY KEY,
    `type_operation` ENUM('RECETTE', 'DEPENSE') NOT NULL,
    `montant` DECIMAL(15, 2) NOT NULL,
    `date_operation` DATE NOT NULL,
    `description` TEXT,
    `id_entreprise` INT,
    FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises`(`id_entreprise`) ON DELETE CASCADE
);

-- 4. Table TRÉSORERIE
CREATE TABLE IF NOT EXISTS `TRÉSORERIE` (
    `id_tresorerie` INT AUTO_INCREMENT PRIMARY KEY,
    `id_entreprise` INT NOT NULL,
    `solde_actuel` DECIMAL(15, 2) DEFAULT 0,
    `date_mise_a_jour` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises`(`id_entreprise`) ON DELETE CASCADE
);

-- 5. Table Operation Notes
CREATE TABLE IF NOT EXISTS `operation_notes` (
    `id_note` INT AUTO_INCREMENT PRIMARY KEY,
    `id_operation` INT NOT NULL,
    `content` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_operation`) REFERENCES `OPÉRATIONS`(`id_operation`) ON DELETE CASCADE
);

-- 6. Table User KYC
CREATE TABLE IF NOT EXISTS `user_kyc` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `status` VARCHAR(20) DEFAULT 'PENDING',
    `id_card_front` VARCHAR(255),
    `id_card_back` VARCHAR(255),
    `selfie` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `utilisateurs`(`id`) ON DELETE CASCADE
);

-- 7. Table Documents
CREATE TABLE IF NOT EXISTS `documents` (
    `id_document` INT AUTO_INCREMENT PRIMARY KEY,
    `id_entreprise` INT NOT NULL,
    `nom_document` VARCHAR(255) NOT NULL,
    `type_document` VARCHAR(50),
    `statut` VARCHAR(50),
    `chemin_fichier` VARCHAR(255),
    `date_upload` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises`(`id_entreprise`) ON DELETE CASCADE
);

-- 8. Table Investissement (from INVESTISOR)
CREATE TABLE IF NOT EXISTS `investissement` (
    `id_investissement` INT AUTO_INCREMENT PRIMARY KEY,
    `id_investisseur` INT NOT NULL,
    `id_entreprise` INT NOT NULL,
    `montant` DECIMAL(15, 2) NOT NULL,
    `date_investissement` DATE NOT NULL,
    `statut` ENUM('EN_ATTENTE', 'VALIDE', 'REFUSE', 'CLOTURE') DEFAULT 'EN_ATTENTE',
    `taux_rendement_prevu` DECIMAL(5, 2),
    `duree_mois` INT,
    `description` TEXT,
    FOREIGN KEY (`id_investisseur`) REFERENCES `utilisateurs`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises`(`id_entreprise`) ON DELETE CASCADE
);

-- 9. Table Rendement Investissement (from INVESTISOR)
CREATE TABLE IF NOT EXISTS `rendement_investissement` (
    `id_rendement` INT AUTO_INCREMENT PRIMARY KEY,
    `id_investissement` INT NOT NULL,
    `date_calcul` DATE NOT NULL,
    `gain` DECIMAL(15, 2) DEFAULT 0,
    `perte` DECIMAL(15, 2) DEFAULT 0,
    `valeur_portefeuille` DECIMAL(15, 2) NOT NULL,
    FOREIGN KEY (`id_investissement`) REFERENCES `investissement`(`id_investissement`) ON DELETE CASCADE
);
