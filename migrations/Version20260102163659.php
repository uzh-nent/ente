<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260102163659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784CD53EDB6');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784D2EDD3FB');
        $this->addSql('DROP INDEX IDX_C42F7784CD53EDB6 ON report');
        $this->addSql('DROP INDEX IDX_C42F7784D2EDD3FB ON report');
        $this->addSql('ALTER TABLE report ADD receiver_org_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD receiver_prac_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD validation_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD receiver VARCHAR(255) NOT NULL, DROP receiver_id, DROP signed_by_id');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784CA2C6F94 FOREIGN KEY (receiver_org_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77845E16880F FOREIGN KEY (receiver_prac_id) REFERENCES practitioner (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778442F42517 FOREIGN KEY (validation_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C42F7784CA2C6F94 ON report (receiver_org_id)');
        $this->addSql('CREATE INDEX IDX_C42F77845E16880F ON report (receiver_prac_id)');
        $this->addSql('CREATE INDEX IDX_C42F778442F42517 ON report (validation_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784CA2C6F94');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77845E16880F');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778442F42517');
        $this->addSql('DROP INDEX IDX_C42F7784CA2C6F94 ON report');
        $this->addSql('DROP INDEX IDX_C42F77845E16880F ON report');
        $this->addSql('DROP INDEX IDX_C42F778442F42517 ON report');
        $this->addSql('ALTER TABLE report ADD receiver_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD signed_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', DROP receiver_org_id, DROP receiver_prac_id, DROP validation_by_id, DROP receiver');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784D2EDD3FB FOREIGN KEY (signed_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C42F7784CD53EDB6 ON report (receiver_id)');
        $this->addSql('CREATE INDEX IDX_C42F7784D2EDD3FB ON report (signed_by_id)');
    }
}
