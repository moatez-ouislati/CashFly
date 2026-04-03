<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260403083327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE chat_messages DROP FOREIGN KEY `chat_messages_ibfk_1`');
        $this->addSql('ALTER TABLE chat_messages DROP FOREIGN KEY `chat_messages_ibfk_2`');
        $this->addSql('ALTER TABLE chat_messages DROP FOREIGN KEY `chat_messages_ibfk_3`');
        $this->addSql('ALTER TABLE chat_presence DROP FOREIGN KEY `chat_presence_ibfk_1`');
        $this->addSql('ALTER TABLE chat_presence DROP FOREIGN KEY `chat_presence_ibfk_2`');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY `fk_documents_entreprise`');
        $this->addSql('ALTER TABLE investissement DROP FOREIGN KEY `fk_inv_entreprise`');
        $this->addSql('ALTER TABLE investissement DROP FOREIGN KEY `fk_inv_user`');
        $this->addSql('ALTER TABLE participation_jpo DROP FOREIGN KEY `participation_jpo_ibfk_2`');
        $this->addSql('ALTER TABLE participation_jpo DROP FOREIGN KEY `participation_jpo_ibfk_3`');
        $this->addSql('ALTER TABLE rendement_investissement DROP FOREIGN KEY `fk_rend_inv_invest`');
        $this->addSql('ALTER TABLE score_entreprise DROP FOREIGN KEY `score_entreprise_ibfk_1`');
        $this->addSql('ALTER TABLE user_kyc DROP FOREIGN KEY `fk_user_kyc`');
        $this->addSql('DROP TABLE chat_messages');
        $this->addSql('DROP TABLE chat_presence');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE investissement');
        $this->addSql('DROP TABLE journees_portes_ouvertes');
        $this->addSql('DROP TABLE journées_portes_ouvertes');
        $this->addSql('DROP TABLE operation_notes');
        $this->addSql('DROP TABLE participation_jpo');
        $this->addSql('DROP TABLE rendement_investissement');
        $this->addSql('DROP TABLE score_entreprise');
        $this->addSql('DROP TABLE user_kyc');
        $this->addSql('ALTER TABLE entreprises CHANGE capital capital NUMERIC(15, 2) DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE entreprises RENAME INDEX id_proprietaire TO IDX_56B1B7A94A22ECA4');
        $this->addSql('ALTER TABLE opérations CHANGE type type VARCHAR(20) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE opérations RENAME INDEX reference TO UNIQ_E9709C69AEA34913');
        $this->addSql('ALTER TABLE opérations RENAME INDEX facture TO UNIQ_E9709C69FE866410');
        $this->addSql('ALTER TABLE opérations RENAME INDEX id_tresorerie TO IDX_E9709C698AA6B26F');
        $this->addSql('ALTER TABLE trésorerie CHANGE type_compte type_compte VARCHAR(20) NOT NULL, CHANGE solde solde NUMERIC(15, 2) DEFAULT \'0.00\' NOT NULL, CHANGE devise devise VARCHAR(3) DEFAULT \'EUR\' NOT NULL');
        $this->addSql('ALTER TABLE trésorerie RENAME INDEX numero_compte TO UNIQ_96D23CB29731415A');
        $this->addSql('ALTER TABLE trésorerie RENAME INDEX id_entreprise TO IDX_96D23CB2A8937AB7');
        $this->addSql('ALTER TABLE utilisateurs CHANGE role role VARCHAR(50) NOT NULL, CHANGE active active TINYINT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs RENAME INDEX email TO UNIQ_497B315EE7927C74');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat_messages (id_message INT AUTO_INCREMENT NOT NULL, id_room INT NOT NULL, id_utilisateur INT NOT NULL, message TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, type ENUM(\'text\', \'image\', \'file\', \'system\') CHARACTER SET utf8mb4 DEFAULT \'text\' COLLATE `utf8mb4_general_ci`, reply_to INT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, is_edited TINYINT DEFAULT 0, status ENUM(\'active\', \'deleted\') CHARACTER SET utf8mb4 DEFAULT \'active\' COLLATE `utf8mb4_general_ci`, edited_at DATETIME DEFAULT NULL, INDEX id_utilisateur (id_utilisateur), INDEX idx_message_room (id_room, created_at), INDEX reply_to (reply_to), INDEX IDX_EF20C9A6F9BF4D99 (id_room), PRIMARY KEY (id_message)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE chat_presence (id_presence INT AUTO_INCREMENT NOT NULL, id_room INT NOT NULL, id_utilisateur INT NOT NULL, last_seen DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, is_online TINYINT DEFAULT 1, INDEX id_utilisateur (id_utilisateur), UNIQUE INDEX unique_presence (id_room, id_utilisateur), INDEX IDX_5D551095F9BF4D99 (id_room), PRIMARY KEY (id_presence)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE documents (id_document INT AUTO_INCREMENT NOT NULL, id_entreprise INT NOT NULL, id_utilisateur INT DEFAULT NULL, nom_document VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, type_document VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, statut VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, chemin_fichier VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, texte_ocr LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, date_upload DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX fk_documents_entreprise (id_entreprise), PRIMARY KEY (id_document)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE investissement (id_investissement INT AUTO_INCREMENT NOT NULL, id_investisseur INT NOT NULL, id_entreprise INT NOT NULL, montant NUMERIC(15, 2) NOT NULL, date_investissement DATETIME DEFAULT CURRENT_TIMESTAMP, statut ENUM(\'EN_ATTENTE\', \'ACTIF\', \'TERMINE\', \'ANNULE\') CHARACTER SET utf8mb4 DEFAULT \'EN_ATTENTE\' COLLATE `utf8mb4_general_ci`, taux_rendement_prevu NUMERIC(5, 2) DEFAULT NULL COMMENT \'Expected ROI %\', duree_mois INT DEFAULT NULL COMMENT \'Duration in months\', description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_inv_entreprise (id_entreprise), INDEX fk_inv_user (id_investisseur), PRIMARY KEY (id_investissement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE journees_portes_ouvertes (id_evenement INT AUTO_INCREMENT NOT NULL, titre VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_evenement DATETIME NOT NULL, lieu VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY (id_evenement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE journées_portes_ouvertes (id_evenement INT AUTO_INCREMENT NOT NULL, titre VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_evenement DATE NOT NULL, lieu VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, image_path VARCHAR(500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, max_participants INT DEFAULT 50 NOT NULL, current_participants INT DEFAULT 0 NOT NULL, id_createur INT DEFAULT NULL, PRIMARY KEY (id_evenement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE operation_notes (id_note INT AUTO_INCREMENT NOT NULL, id_operation INT NOT NULL, content TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX id_operation (id_operation), PRIMARY KEY (id_note)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE participation_jpo (id_participation INT AUTO_INCREMENT NOT NULL, id_evenement INT NOT NULL, id_entreprise INT DEFAULT NULL, id_utilisateur INT NOT NULL, role ENUM(\'entreprise\', \'investisseur\') CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, statut VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'en_attente\' NOT NULL COLLATE `utf8mb4_unicode_ci`, date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP, badge_genere TINYINT DEFAULT 0 NOT NULL, INDEX id_entreprise (id_entreprise), INDEX id_utilisateur (id_utilisateur), UNIQUE INDEX unique_participation_entreprise (id_evenement, id_entreprise), UNIQUE INDEX unique_participation_investisseur (id_evenement, id_utilisateur), PRIMARY KEY (id_participation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rendement_investissement (id_rendement INT AUTO_INCREMENT NOT NULL, id_investissement INT NOT NULL, date_calcul DATE NOT NULL, gain NUMERIC(15, 2) DEFAULT \'0.00\', perte NUMERIC(15, 2) DEFAULT \'0.00\', valeur_portefeuille NUMERIC(15, 2) NOT NULL, INDEX fk_rend_inv_invest (id_investissement), PRIMARY KEY (id_rendement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE score_entreprise (id_score INT AUTO_INCREMENT NOT NULL, id_entreprise INT NOT NULL, score_ai NUMERIC(5, 2) DEFAULT NULL, niveau_risque ENUM(\'faible\', \'moyen\', \'eleve\') CHARACTER SET utf8mb4 DEFAULT \'moyen\' COLLATE `utf8mb4_unicode_ci`, score_rentabilite NUMERIC(5, 2) DEFAULT NULL, score_croissance NUMERIC(5, 2) DEFAULT NULL, derniere_maj DATETIME DEFAULT CURRENT_TIMESTAMP, UNIQUE INDEX unique_entreprise_score (id_entreprise), PRIMARY KEY (id_score)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_kyc (user_id INT NOT NULL, face_embedding BLOB NOT NULL, is_verified TINYINT DEFAULT 0, verified_at DATETIME DEFAULT NULL, id_document_path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE chat_messages ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (id_room) REFERENCES journées_portes_ouvertes (id_evenement) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_messages ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id_utilisateur) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_messages ADD CONSTRAINT `chat_messages_ibfk_3` FOREIGN KEY (reply_to) REFERENCES chat_messages (id_message) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE chat_presence ADD CONSTRAINT `chat_presence_ibfk_1` FOREIGN KEY (id_room) REFERENCES journées_portes_ouvertes (id_evenement) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_presence ADD CONSTRAINT `chat_presence_ibfk_2` FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id_utilisateur) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT `fk_documents_entreprise` FOREIGN KEY (id_entreprise) REFERENCES entreprises (id_entreprise) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE investissement ADD CONSTRAINT `fk_inv_entreprise` FOREIGN KEY (id_entreprise) REFERENCES entreprises (id_entreprise) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE investissement ADD CONSTRAINT `fk_inv_user` FOREIGN KEY (id_investisseur) REFERENCES utilisateurs (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation_jpo ADD CONSTRAINT `participation_jpo_ibfk_2` FOREIGN KEY (id_entreprise) REFERENCES entreprises (id_entreprise) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE participation_jpo ADD CONSTRAINT `participation_jpo_ibfk_3` FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id_utilisateur) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE rendement_investissement ADD CONSTRAINT `fk_rend_inv_invest` FOREIGN KEY (id_investissement) REFERENCES investissement (id_investissement) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE score_entreprise ADD CONSTRAINT `score_entreprise_ibfk_1` FOREIGN KEY (id_entreprise) REFERENCES entreprises (id_entreprise) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_kyc ADD CONSTRAINT `fk_user_kyc` FOREIGN KEY (user_id) REFERENCES utilisateurs (id_utilisateur) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE entreprises CHANGE capital capital NUMERIC(15, 2) DEFAULT \'0.00\'');
        $this->addSql('ALTER TABLE entreprises RENAME INDEX idx_56b1b7a94a22eca4 TO id_proprietaire');
        $this->addSql('ALTER TABLE opérations CHANGE type type ENUM(\'revenu\', \'depense\') NOT NULL, CHANGE description description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE opérations RENAME INDEX uniq_e9709c69fe866410 TO facture');
        $this->addSql('ALTER TABLE opérations RENAME INDEX idx_e9709c698aa6b26f TO id_tresorerie');
        $this->addSql('ALTER TABLE opérations RENAME INDEX uniq_e9709c69aea34913 TO reference');
        $this->addSql('ALTER TABLE trésorerie CHANGE type_compte type_compte ENUM(\'CAISSE\', \'BANQUE\', \'CARTE\', \'WALLET\') NOT NULL, CHANGE solde solde NUMERIC(15, 2) DEFAULT \'0.00\', CHANGE devise devise VARCHAR(3) DEFAULT \'EUR\'');
        $this->addSql('ALTER TABLE trésorerie RENAME INDEX idx_96d23cb2a8937ab7 TO id_entreprise');
        $this->addSql('ALTER TABLE trésorerie RENAME INDEX uniq_96d23cb29731415a TO numero_compte');
        $this->addSql('ALTER TABLE utilisateurs CHANGE role role ENUM(\'proprietaire\', \'investisseur\', \'administrateur\') NOT NULL, CHANGE active active TINYINT DEFAULT 1');
        $this->addSql('ALTER TABLE utilisateurs RENAME INDEX uniq_497b315ee7927c74 TO email');
    }
}
