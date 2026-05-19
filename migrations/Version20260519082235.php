<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260519082235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE report_email (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', probe_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', report_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', created_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', last_changed_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', `to` LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', cc LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', subject VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, sent_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CD8E7B0D3D2D0D4A (probe_id), INDEX IDX_CD8E7B0D4BD2A4C0 (report_id), INDEX IDX_CD8E7B0DB03A8386 (created_by_id), INDEX IDX_CD8E7B0DEE85B337 (last_changed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report_email ADD CONSTRAINT FK_CD8E7B0D3D2D0D4A FOREIGN KEY (probe_id) REFERENCES probe (id)');
        $this->addSql('ALTER TABLE report_email ADD CONSTRAINT FK_CD8E7B0D4BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
        $this->addSql('ALTER TABLE report_email ADD CONSTRAINT FK_CD8E7B0DB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report_email ADD CONSTRAINT FK_CD8E7B0DEE85B337 FOREIGN KEY (last_changed_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report_email DROP FOREIGN KEY FK_CD8E7B0D3D2D0D4A');
        $this->addSql('ALTER TABLE report_email DROP FOREIGN KEY FK_CD8E7B0D4BD2A4C0');
        $this->addSql('ALTER TABLE report_email DROP FOREIGN KEY FK_CD8E7B0DB03A8386');
        $this->addSql('ALTER TABLE report_email DROP FOREIGN KEY FK_CD8E7B0DEE85B337');
        $this->addSql('DROP TABLE report_email');
    }
}
