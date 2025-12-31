<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251231163100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE probe ADD finished_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE finished_at finished_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2A4A12CC70 FOREIGN KEY (finished_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D75E6F2A4A12CC70 ON probe (finished_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2A4A12CC70');
        $this->addSql('DROP INDEX IDX_D75E6F2A4A12CC70 ON probe');
        $this->addSql('ALTER TABLE probe DROP finished_by_id, CHANGE finished_at finished_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }
}
