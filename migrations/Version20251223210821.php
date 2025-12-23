<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223210821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inf_report DROP FOREIGN KEY FK_EC2DFBBFA45BB98C');
        $this->addSql('DROP INDEX IDX_EC2DFBBFA45BB98C ON inf_report');
        $this->addSql('ALTER TABLE inf_report ADD last_changed_by_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE sent_by_id created_by_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE inf_report ADD CONSTRAINT FK_EC2DFBBFB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inf_report ADD CONSTRAINT FK_EC2DFBBFEE85B337 FOREIGN KEY (last_changed_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EC2DFBBFB03A8386 ON inf_report (created_by_id)');
        $this->addSql('CREATE INDEX IDX_EC2DFBBFEE85B337 ON inf_report (last_changed_by_id)');
        $this->addSql('ALTER TABLE observation DROP analysis_start_at');
        $this->addSql('ALTER TABLE probe ADD analysis_type LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', ADD analysis_start_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD finished_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE report ADD created_by_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD last_changed_by_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784EE85B337 FOREIGN KEY (last_changed_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C42F7784B03A8386 ON report (created_by_id)');
        $this->addSql('CREATE INDEX IDX_C42F7784EE85B337 ON report (last_changed_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inf_report DROP FOREIGN KEY FK_EC2DFBBFB03A8386');
        $this->addSql('ALTER TABLE inf_report DROP FOREIGN KEY FK_EC2DFBBFEE85B337');
        $this->addSql('DROP INDEX IDX_EC2DFBBFB03A8386 ON inf_report');
        $this->addSql('DROP INDEX IDX_EC2DFBBFEE85B337 ON inf_report');
        $this->addSql('ALTER TABLE inf_report ADD sent_by_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', DROP created_by_id, DROP last_changed_by_id');
        $this->addSql('ALTER TABLE inf_report ADD CONSTRAINT FK_EC2DFBBFA45BB98C FOREIGN KEY (sent_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EC2DFBBFA45BB98C ON inf_report (sent_by_id)');
        $this->addSql('ALTER TABLE probe DROP analysis_type, DROP analysis_start_at, DROP finished_at');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784B03A8386');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784EE85B337');
        $this->addSql('DROP INDEX IDX_C42F7784B03A8386 ON report');
        $this->addSql('DROP INDEX IDX_C42F7784EE85B337 ON report');
        $this->addSql('ALTER TABLE report DROP created_by_id, DROP last_changed_by_id');
        $this->addSql('ALTER TABLE observation ADD analysis_start_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }
}
