<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251226090351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE observation ADD probe_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD interpretation_text VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE03D2D0D4A FOREIGN KEY (probe_id) REFERENCES probe (id)');
        $this->addSql('CREATE INDEX IDX_C576DBE03D2D0D4A ON observation (probe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE03D2D0D4A');
        $this->addSql('DROP INDEX IDX_C576DBE03D2D0D4A ON observation');
        $this->addSql('ALTER TABLE observation DROP probe_id, DROP interpretation_text');
    }
}
