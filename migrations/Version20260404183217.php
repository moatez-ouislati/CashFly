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
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user ADD years_experience VARCHAR(2) DEFAULT NULL, ADD highest_profit VARCHAR(10) DEFAULT NULL, DROP yearsExperience, DROP highestProfit, CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE roles roles JSON NOT NULL, CHANGE budget budget VARCHAR(50) DEFAULT NULL, CHANGE face_image face_image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE user ADD yearsExperience VARCHAR(2) DEFAULT \'NULL\', ADD highestProfit VARCHAR(10) DEFAULT \'NULL\', DROP years_experience, DROP highest_profit, CHANGE email email VARCHAR(255) DEFAULT \'NULL\' COLLATE `utf8mb4_bin`, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)	\', CHANGE budget budget VARCHAR(50) DEFAULT \'NULL\', CHANGE face_image face_image VARCHAR(255) DEFAULT \'NULL\'');
    }
}
