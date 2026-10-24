<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261024071259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add analysis stop date as explicit field';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2A4A12CC70');
        $this->addSql('DROP INDEX IDX_D75E6F2A4A12CC70 ON probe');
        $this->addSql('ALTER TABLE probe ADD analysis_stop_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE finished_by_id closed_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE finished_at closed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('UPDATE probe SET analysis_stop_date = closed_at');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2AE1FA7797 FOREIGN KEY (closed_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D75E6F2AE1FA7797 ON probe (closed_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2AE1FA7797');
        $this->addSql('DROP INDEX IDX_D75E6F2AE1FA7797 ON probe');
        $this->addSql('ALTER TABLE probe DROP analysis_stop_date, CHANGE closed_by_id finished_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE closed_at finished_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2A4A12CC70 FOREIGN KEY (finished_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D75E6F2A4A12CC70 ON probe (finished_by_id)');
    }
}
