<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205072953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE leading_code ADD is_hidden TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE organism ADD is_hidden TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE specimen ADD is_hidden TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organism DROP is_hidden');
        $this->addSql('ALTER TABLE leading_code DROP is_hidden');
        $this->addSql('ALTER TABLE specimen DROP is_hidden');
    }
}
