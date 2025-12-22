<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251222070820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal_keeper (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) DEFAULT NULL, given_name VARCHAR(255) DEFAULT NULL, family_name VARCHAR(255) DEFAULT NULL, address_lines LONGTEXT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inf_report (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', probe_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', leading_code_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', organism_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', specimen_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', interpretation_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', sent_by_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', sent_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', document_id VARCHAR(255) DEFAULT NULL, payload JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', response JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', api_id VARCHAR(255) DEFAULT NULL, api_status VARCHAR(255) NOT NULL, INDEX IDX_EC2DFBBF3D2D0D4A (probe_id), INDEX IDX_EC2DFBBFCA52EA18 (leading_code_id), INDEX IDX_EC2DFBBF64180A36 (organism_id), INDEX IDX_EC2DFBBFBF112A8 (specimen_id), INDEX IDX_EC2DFBBFF60A8F2C (interpretation_id), INDEX IDX_EC2DFBBFA45BB98C (sent_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interpretation (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', `group` VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', system VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, display_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leading_code (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', specimen_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', pathogen VARCHAR(255) DEFAULT NULL, organism_group VARCHAR(255) DEFAULT NULL, specimen_group VARCHAR(255) DEFAULT NULL, interpretation_group VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', system VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, display_name VARCHAR(255) DEFAULT NULL, INDEX IDX_A56A662EBF112A8 (specimen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE observation (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', interpretation_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', organism_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', created_by_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', last_changed_by_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', analysis_type VARCHAR(255) NOT NULL, analysis_start_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', analysis_stop_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment LONGTEXT DEFAULT NULL, INDEX IDX_C576DBE0F60A8F2C (interpretation_id), INDEX IDX_C576DBE064180A36 (organism_id), INDEX IDX_C576DBE0B03A8386 (created_by_id), INDEX IDX_C576DBE0EE85B337 (last_changed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organism (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', `group` VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', system VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, display_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) DEFAULT NULL, address_lines LONGTEXT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', birth_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ahv_number VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', given_name VARCHAR(255) DEFAULT NULL, family_name VARCHAR(255) DEFAULT NULL, address_lines LONGTEXT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE probe (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', orderer_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', animal_keeper_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', patient_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', specimen_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', created_by_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', last_changed_by_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', orderer_identifier VARCHAR(255) DEFAULT NULL, laboratory_function VARCHAR(255) DEFAULT NULL, pathogen VARCHAR(255) DEFAULT NULL, received_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', specimen_source VARCHAR(255) DEFAULT NULL, specimen_source_text VARCHAR(255) DEFAULT NULL, specimen_text VARCHAR(255) DEFAULT NULL, specimen_type_text VARCHAR(255) DEFAULT NULL, specimen_location VARCHAR(255) DEFAULT NULL, specimen_food_type VARCHAR(255) DEFAULT NULL, specimen_animal_type VARCHAR(255) DEFAULT NULL, specimen_isolate TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment LONGTEXT DEFAULT NULL, orderer_name VARCHAR(255) DEFAULT NULL, orderer_address_lines LONGTEXT DEFAULT NULL, orderer_city VARCHAR(255) DEFAULT NULL, order_postal_code VARCHAR(255) DEFAULT NULL, orderer_country_code VARCHAR(255) DEFAULT NULL, animal_keeper_name VARCHAR(255) DEFAULT NULL, animal_keeper_given_name VARCHAR(255) DEFAULT NULL, animal_keeper_family_name VARCHAR(255) DEFAULT NULL, animal_keeper_address_lines LONGTEXT DEFAULT NULL, animal_keeper_city VARCHAR(255) DEFAULT NULL, animal_keeper_postal_code VARCHAR(255) DEFAULT NULL, animal_keeper_country_code VARCHAR(255) DEFAULT NULL, patient_birth_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', patient_ahv_number VARCHAR(255) DEFAULT NULL, patient_given_name VARCHAR(255) DEFAULT NULL, patient_family_name VARCHAR(255) DEFAULT NULL, patient_address_lines LONGTEXT DEFAULT NULL, patient_city VARCHAR(255) DEFAULT NULL, patient_postal_code VARCHAR(255) DEFAULT NULL, patient_country_code VARCHAR(255) DEFAULT NULL, INDEX IDX_D75E6F2ADF123119 (orderer_id), INDEX IDX_D75E6F2A83D86AEC (animal_keeper_id), INDEX IDX_D75E6F2A6B899279 (patient_id), INDEX IDX_D75E6F2ABF112A8 (specimen_id), INDEX IDX_D75E6F2AB03A8386 (created_by_id), INDEX IDX_D75E6F2AEE85B337 (last_changed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', probe_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', receiver_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', signed_by_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', payload JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', filename VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_C42F77843D2D0D4A (probe_id), INDEX IDX_C42F7784CD53EDB6 (receiver_id), INDEX IDX_C42F7784D2EDD3FB (signed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specimen (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', `group` VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', system VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, display_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', shortname VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, medical_validation TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D64964082763 (shortname), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inf_report ADD CONSTRAINT FK_EC2DFBBF3D2D0D4A FOREIGN KEY (probe_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE inf_report ADD CONSTRAINT FK_EC2DFBBFCA52EA18 FOREIGN KEY (leading_code_id) REFERENCES leading_code (id)');
        $this->addSql('ALTER TABLE inf_report ADD CONSTRAINT FK_EC2DFBBF64180A36 FOREIGN KEY (organism_id) REFERENCES organism (id)');
        $this->addSql('ALTER TABLE inf_report ADD CONSTRAINT FK_EC2DFBBFBF112A8 FOREIGN KEY (specimen_id) REFERENCES specimen (id)');
        $this->addSql('ALTER TABLE inf_report ADD CONSTRAINT FK_EC2DFBBFF60A8F2C FOREIGN KEY (interpretation_id) REFERENCES interpretation (id)');
        $this->addSql('ALTER TABLE inf_report ADD CONSTRAINT FK_EC2DFBBFA45BB98C FOREIGN KEY (sent_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE leading_code ADD CONSTRAINT FK_A56A662EBF112A8 FOREIGN KEY (specimen_id) REFERENCES specimen (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0F60A8F2C FOREIGN KEY (interpretation_id) REFERENCES interpretation (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE064180A36 FOREIGN KEY (organism_id) REFERENCES organism (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0EE85B337 FOREIGN KEY (last_changed_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2ADF123119 FOREIGN KEY (orderer_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2A83D86AEC FOREIGN KEY (animal_keeper_id) REFERENCES animal_keeper (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2A6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2ABF112A8 FOREIGN KEY (specimen_id) REFERENCES specimen (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2AEE85B337 FOREIGN KEY (last_changed_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77843D2D0D4A FOREIGN KEY (probe_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784D2EDD3FB FOREIGN KEY (signed_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inf_report DROP FOREIGN KEY FK_EC2DFBBF3D2D0D4A');
        $this->addSql('ALTER TABLE inf_report DROP FOREIGN KEY FK_EC2DFBBFCA52EA18');
        $this->addSql('ALTER TABLE inf_report DROP FOREIGN KEY FK_EC2DFBBF64180A36');
        $this->addSql('ALTER TABLE inf_report DROP FOREIGN KEY FK_EC2DFBBFBF112A8');
        $this->addSql('ALTER TABLE inf_report DROP FOREIGN KEY FK_EC2DFBBFF60A8F2C');
        $this->addSql('ALTER TABLE inf_report DROP FOREIGN KEY FK_EC2DFBBFA45BB98C');
        $this->addSql('ALTER TABLE leading_code DROP FOREIGN KEY FK_A56A662EBF112A8');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE0F60A8F2C');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE064180A36');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE0B03A8386');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE0EE85B337');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2ADF123119');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2A83D86AEC');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2A6B899279');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2ABF112A8');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2AB03A8386');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2AEE85B337');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77843D2D0D4A');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784CD53EDB6');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784D2EDD3FB');
        $this->addSql('DROP TABLE animal_keeper');
        $this->addSql('DROP TABLE inf_report');
        $this->addSql('DROP TABLE interpretation');
        $this->addSql('DROP TABLE leading_code');
        $this->addSql('DROP TABLE observation');
        $this->addSql('DROP TABLE organism');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE probe');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE specimen');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
