<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260305154521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add identifiers, add interpretation text, add contact info';
    }

    public function up(Schema $schema): void
    {
        // Add identifiers, add interpretation text, add contact info
        $this->addSql('CREATE TABLE interpretation_text (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', pathogen VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE standard_text RENAME report_text');
        $this->addSql('ALTER TABLE animal_keeper ADD ber VARCHAR(255) DEFAULT NULL, ADD uid VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD contact LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE observation ADD interpretation_meta VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE organization ADD uid VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, CHANGE gln_identifier ber VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD email VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD contact LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE practitioner ADD gln VARCHAR(255) NOT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE probe ADD orderer_org_email VARCHAR(255) DEFAULT NULL, ADD orderer_org_phone VARCHAR(255) DEFAULT NULL, ADD orderer_prac_email VARCHAR(255) DEFAULT NULL, ADD orderer_prac_phone VARCHAR(255) DEFAULT NULL, ADD animal_keeper_email VARCHAR(255) DEFAULT NULL, ADD animal_keeper_phone VARCHAR(255) DEFAULT NULL, ADD animal_keeper_contact LONGTEXT DEFAULT NULL, ADD patient_email VARCHAR(255) DEFAULT NULL, ADD patient_phone VARCHAR(255) DEFAULT NULL, ADD patient_contact LONGTEXT DEFAULT NULL');

        // copy identifiers in probe
        $this->addSql('ALTER TABLE probe ADD orderer_org_ber VARCHAR(255) DEFAULT NULL, ADD orderer_org_uid VARCHAR(255) DEFAULT NULL, ADD orderer_prac_gln VARCHAR(255) DEFAULT NULL, ADD animal_keeper_ber VARCHAR(255) DEFAULT NULL, ADD animal_keeper_uid VARCHAR(255) DEFAULT NULL');

        // add invoice
        $this->addSql('CREATE TABLE invoice (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', probe_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', created_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', last_changed_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', receiver VARCHAR(255) DEFAULT NULL, line_items JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', invoice_identifier VARCHAR(255) DEFAULT NULL, reimbursement_voucher_filename VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_906517443D2D0D4A (probe_id), INDEX IDX_90651744B03A8386 (created_by_id), INDEX IDX_90651744EE85B337 (last_changed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517443D2D0D4A FOREIGN KEY (probe_id) REFERENCES probe (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744EE85B337 FOREIGN KEY (last_changed_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE probe ADD invoice_status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD address LONGTEXT DEFAULT NULL');

        // add invoice address to entities
        $this->addSql('ALTER TABLE animal_keeper ADD invoice_address LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE organization ADD invoice_address LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD invoice_address LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE practitioner ADD invoice_address LONGTEXT DEFAULT NULL');

        // translate specimen for repor
        $this->addSql('ALTER TABLE specimen ADD report_translation VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE specimen DROP report_translation');

        $this->addSql('ALTER TABLE organization DROP invoice_address');
        $this->addSql('ALTER TABLE practitioner DROP invoice_address');
        $this->addSql('ALTER TABLE animal_keeper DROP invoice_address');
        $this->addSql('ALTER TABLE patient DROP invoice_address');

        $this->addSql('ALTER TABLE invoice DROP address');

        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517443D2D0D4A');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744B03A8386');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744EE85B337');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('ALTER TABLE probe DROP invoice_status');

        $this->addSql('ALTER TABLE probe DROP orderer_org_ber, DROP orderer_org_uid, DROP orderer_prac_gln, DROP animal_keeper_ber, DROP animal_keeper_uid');

        $this->addSql('ALTER TABLE report_text RENAME standard_text');
        $this->addSql('DROP TABLE interpretation_text');
        $this->addSql('ALTER TABLE organization ADD gln_identifier VARCHAR(255) DEFAULT NULL, DROP ber, DROP uid, DROP email, DROP phone');
        $this->addSql('ALTER TABLE observation DROP interpretation_meta');
        $this->addSql('ALTER TABLE probe DROP orderer_org_email, DROP orderer_org_phone, DROP orderer_prac_email, DROP orderer_prac_phone, DROP animal_keeper_email, DROP animal_keeper_phone, DROP animal_keeper_contact, DROP patient_email, DROP patient_phone, DROP patient_contact');
        $this->addSql('ALTER TABLE practitioner DROP gln, DROP email, DROP phone');
        $this->addSql('ALTER TABLE animal_keeper DROP ber, DROP uid, DROP email, DROP phone, DROP contact');
        $this->addSql('ALTER TABLE patient DROP email, DROP phone, DROP contact');
    }
}
