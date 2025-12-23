<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223082124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inf_report DROP FOREIGN KEY FK_EC2DFBBFF60A8F2C');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE0F60A8F2C');
        $this->addSql('DROP TABLE interpretation');
        $this->addSql('DROP INDEX IDX_EC2DFBBFF60A8F2C ON inf_report');
        $this->addSql('ALTER TABLE inf_report ADD interpretation VARCHAR(255) DEFAULT NULL, DROP interpretation_id');
        $this->addSql('DROP INDEX IDX_C576DBE0F60A8F2C ON observation');
        $this->addSql('ALTER TABLE observation ADD interpretation VARCHAR(255) DEFAULT NULL, DROP interpretation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE interpretation (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', interpretation_group VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', system VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, display_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE inf_report ADD interpretation_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', DROP interpretation');
        $this->addSql('ALTER TABLE inf_report ADD CONSTRAINT FK_EC2DFBBFF60A8F2C FOREIGN KEY (interpretation_id) REFERENCES interpretation (id)');
        $this->addSql('CREATE INDEX IDX_EC2DFBBFF60A8F2C ON inf_report (interpretation_id)');
        $this->addSql('ALTER TABLE observation ADD interpretation_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', DROP interpretation');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0F60A8F2C FOREIGN KEY (interpretation_id) REFERENCES interpretation (id)');
        $this->addSql('CREATE INDEX IDX_C576DBE0F60A8F2C ON observation (interpretation_id)');
    }
}
