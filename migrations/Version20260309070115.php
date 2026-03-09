<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260309070115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_keeper ADD invoice_address LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE organization ADD invoice_address LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD invoice_address LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE practitioner ADD invoice_address LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization DROP invoice_address');
        $this->addSql('ALTER TABLE practitioner DROP invoice_address');
        $this->addSql('ALTER TABLE animal_keeper DROP invoice_address');
        $this->addSql('ALTER TABLE patient DROP invoice_address');
    }
}
