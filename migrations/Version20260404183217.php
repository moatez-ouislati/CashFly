<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260404183217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Synchronise la base cashflydb existante avec le schema attendu par l application.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS messenger_messages (
    id BIGINT AUTO_INCREMENT NOT NULL,
    body LONGTEXT NOT NULL,
    headers LONGTEXT NOT NULL,
    queue_name VARCHAR(190) NOT NULL,
    created_at DATETIME NOT NULL,
    available_at DATETIME NOT NULL,
    delivered_at DATETIME DEFAULT NULL,
    INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
SQL);
        $this->addSql("ALTER TABLE utilisateurs MODIFY cin VARCHAR(8) NOT NULL");
        $this->addSql("ALTER TABLE utilisateurs MODIFY roles VARCHAR(50) NOT NULL DEFAULT 'ROLE_INVESTISSEUR'");
        $this->addSql("ALTER TABLE documents ADD id_utilisateur INT DEFAULT NULL");
        $this->addSql("ALTER TABLE documents ADD description LONGTEXT DEFAULT NULL");
        $this->addSql("ALTER TABLE documents ADD texte_ocr LONGTEXT DEFAULT NULL");
        $this->addSql("ALTER TABLE documents ADD commentaire LONGTEXT DEFAULT NULL");
        $this->addSql("ALTER TABLE documents ADD id_validateur INT DEFAULT NULL");
        $this->addSql("ALTER TABLE documents ADD nom_validateur VARCHAR(150) DEFAULT NULL");
        $this->addSql("ALTER TABLE documents ADD date_validation DATETIME DEFAULT NULL");
        $this->addSql("ALTER TABLE documents ADD historique JSON DEFAULT NULL");
        $this->addSql("ALTER TABLE documents MODIFY date_upload DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS messenger_messages');
        $this->addSql('ALTER TABLE documents DROP COLUMN id_utilisateur, DROP COLUMN description, DROP COLUMN texte_ocr, DROP COLUMN commentaire, DROP COLUMN id_validateur, DROP COLUMN nom_validateur, DROP COLUMN date_validation, DROP COLUMN historique');
    }
}
