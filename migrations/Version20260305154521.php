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
        $this->addSql('CREATE TABLE interpretation_text (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', pathogen VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE standard_text RENAME report_text');
        $this->addSql('ALTER TABLE animal_keeper ADD ber VARCHAR(255) DEFAULT NULL, ADD uid VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD contact LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE observation ADD interpretation_meta VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE organization ADD uid VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, CHANGE gln_identifier ber VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD email VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD contact LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE practitioner ADD gln VARCHAR(255) NOT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE probe ADD orderer_org_email VARCHAR(255) DEFAULT NULL, ADD orderer_org_phone VARCHAR(255) DEFAULT NULL, ADD orderer_prac_email VARCHAR(255) DEFAULT NULL, ADD orderer_prac_phone VARCHAR(255) DEFAULT NULL, ADD animal_keeper_email VARCHAR(255) DEFAULT NULL, ADD animal_keeper_phone VARCHAR(255) DEFAULT NULL, ADD animal_keeper_contact LONGTEXT DEFAULT NULL, ADD patient_email VARCHAR(255) DEFAULT NULL, ADD patient_phone VARCHAR(255) DEFAULT NULL, ADD patient_contact LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
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
