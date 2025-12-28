<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251228155305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elm_report DROP FOREIGN KEY FK_858D2FE13D2D0D4A');
        $this->addSql('ALTER TABLE elm_report ADD send_response_json JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD document_reference_id VARCHAR(255) DEFAULT NULL, ADD last_document_reference_response_json JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD comment LONGTEXT DEFAULT NULL, CHANGE sent_at sent_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE response_json validation_response_json JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE api_id api_queue_status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE elm_report ADD CONSTRAINT FK_858D2FE13D2D0D4A FOREIGN KEY (probe_id) REFERENCES probe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elm_report DROP FOREIGN KEY FK_858D2FE13D2D0D4A');
        $this->addSql('ALTER TABLE elm_report ADD response_json JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD api_id VARCHAR(255) DEFAULT NULL, DROP validation_response_json, DROP send_response_json, DROP api_queue_status, DROP document_reference_id, DROP last_document_reference_response_json, DROP comment, CHANGE sent_at sent_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE elm_report ADD CONSTRAINT FK_858D2FE13D2D0D4A FOREIGN KEY (probe_id) REFERENCES organization (id)');
    }
}
