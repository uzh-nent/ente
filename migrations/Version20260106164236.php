<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260106164236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77845E16880F');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778442F42517');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784CA2C6F94');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77843D2D0D4A');
        $this->addSql('DROP INDEX IDX_C42F77845E16880F ON report');
        $this->addSql('DROP INDEX IDX_C42F778442F42517 ON report');
        $this->addSql('DROP INDEX IDX_C42F7784CA2C6F94 ON report');
        $this->addSql('ALTER TABLE report ADD claim_certification TINYINT(1) NOT NULL, ADD addresses LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', DROP receiver_org_id, DROP receiver_prac_id, DROP validation_by_id, DROP receiver, CHANGE payload results JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77843D2D0D4A FOREIGN KEY (probe_id) REFERENCES probe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77843D2D0D4A');
        $this->addSql('ALTER TABLE report ADD receiver_org_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD receiver_prac_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD validation_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD receiver VARCHAR(255) NOT NULL, DROP claim_certification, DROP addresses, CHANGE results payload JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77845E16880F FOREIGN KEY (receiver_prac_id) REFERENCES practitioner (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778442F42517 FOREIGN KEY (validation_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784CA2C6F94 FOREIGN KEY (receiver_org_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77843D2D0D4A FOREIGN KEY (probe_id) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_C42F77845E16880F ON report (receiver_prac_id)');
        $this->addSql('CREATE INDEX IDX_C42F778442F42517 ON report (validation_by_id)');
        $this->addSql('CREATE INDEX IDX_C42F7784CA2C6F94 ON report (receiver_org_id)');
    }
}
