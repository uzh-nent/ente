<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223073616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interpretation CHANGE `group` interpretation_group VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE organism CHANGE `group` organism_group VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE specimen CHANGE `group` specimen_group VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specimen CHANGE specimen_group `group` VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE organism CHANGE organism_group `group` VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE interpretation CHANGE interpretation_group `group` VARCHAR(255) DEFAULT NULL');
    }
}
