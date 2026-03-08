<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260308071557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', probe_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', created_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', last_changed_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', address LONGTEXT DEFAULT NULL, line_items JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', invoice_identifier VARCHAR(255) DEFAULT NULL, reimbursement_voucher_filename VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_906517443D2D0D4A (probe_id), INDEX IDX_90651744B03A8386 (created_by_id), INDEX IDX_90651744EE85B337 (last_changed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517443D2D0D4A FOREIGN KEY (probe_id) REFERENCES probe (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744EE85B337 FOREIGN KEY (last_changed_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE probe ADD invoice_status VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517443D2D0D4A');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744B03A8386');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744EE85B337');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('ALTER TABLE probe DROP invoice_status');
    }
}
