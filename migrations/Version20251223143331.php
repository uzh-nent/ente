<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223143331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE observation CHANGE analysis_start_at analysis_start_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE probe ADD identifier VARCHAR(255) NOT NULL, ADD status VARCHAR(255) DEFAULT NULL, ADD pathogen_name VARCHAR(255) DEFAULT NULL, ADD specimen_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', DROP animal_keeper_given_name, DROP animal_keeper_family_name');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D75E6F2A772E836A ON probe (identifier)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_D75E6F2A772E836A ON probe');
        $this->addSql('ALTER TABLE probe ADD animal_keeper_given_name VARCHAR(255) DEFAULT NULL, ADD animal_keeper_family_name VARCHAR(255) DEFAULT NULL, DROP identifier, DROP status, DROP pathogen_name, DROP specimen_date');
        $this->addSql('ALTER TABLE observation CHANGE analysis_start_at analysis_start_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }
}
