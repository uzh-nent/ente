<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251228073919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal_keeper (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, address_lines LONGTEXT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE elm_report (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', probe_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', observation_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', created_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', last_changed_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', leading_code_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', organism_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', specimen_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', sent_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', diagnostic_report_id VARCHAR(255) DEFAULT NULL, request_json JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', response_json JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', api_id VARCHAR(255) DEFAULT NULL, api_status VARCHAR(255) NOT NULL, organism_text VARCHAR(255) DEFAULT NULL, interpretation VARCHAR(255) DEFAULT NULL, effective_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_858D2FE13D2D0D4A (probe_id), INDEX IDX_858D2FE11409DD88 (observation_id), INDEX IDX_858D2FE1B03A8386 (created_by_id), INDEX IDX_858D2FE1EE85B337 (last_changed_by_id), INDEX IDX_858D2FE1CA52EA18 (leading_code_id), INDEX IDX_858D2FE164180A36 (organism_id), INDEX IDX_858D2FE1BF112A8 (specimen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leading_code (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', specimen_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', pathogen VARCHAR(255) DEFAULT NULL, organism_group VARCHAR(255) DEFAULT NULL, specimen_group VARCHAR(255) DEFAULT NULL, interpretation_group VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', system VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, INDEX IDX_A56A662EBF112A8 (specimen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE observation (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', organism_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', probe_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', created_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', last_changed_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', analysis_type VARCHAR(255) NOT NULL, effective_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', interpretation VARCHAR(255) DEFAULT NULL, interpretation_text VARCHAR(255) DEFAULT NULL, cg_mlst VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment LONGTEXT DEFAULT NULL, INDEX IDX_C576DBE064180A36 (organism_id), INDEX IDX_C576DBE03D2D0D4A (probe_id), INDEX IDX_C576DBE0B03A8386 (created_by_id), INDEX IDX_C576DBE0EE85B337 (last_changed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organism (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', organism_group VARCHAR(255) DEFAULT NULL, pathogen VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', system VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, gln_identifier VARCHAR(255) DEFAULT NULL, address_lines LONGTEXT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) DEFAULT NULL, contact LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', gender LONGTEXT DEFAULT NULL, birth_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ahv_number VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', given_name VARCHAR(255) NOT NULL, family_name VARCHAR(255) NOT NULL, address_lines LONGTEXT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', given_name VARCHAR(255) NOT NULL, family_name VARCHAR(255) NOT NULL, address_lines LONGTEXT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) DEFAULT NULL, contact LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE probe (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', last_changed_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', orderer_org_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', orderer_prac_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', animal_keeper_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', patient_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', specimen_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', identifier VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment LONGTEXT DEFAULT NULL, laboratory_function VARCHAR(255) NOT NULL, pathogen VARCHAR(255) DEFAULT NULL, pathogen_name VARCHAR(255) DEFAULT NULL, analysis_types LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', requisition_identifier VARCHAR(255) DEFAULT NULL, orderer_org_name VARCHAR(255) NOT NULL, orderer_org_address_lines LONGTEXT DEFAULT NULL, orderer_org_city VARCHAR(255) DEFAULT NULL, orderer_org_postal_code VARCHAR(255) DEFAULT NULL, orderer_org_country_code VARCHAR(255) DEFAULT NULL, orderer_org_contact LONGTEXT DEFAULT NULL, orderer_prac_given_name VARCHAR(255) NOT NULL, orderer_prac_family_name VARCHAR(255) NOT NULL, orderer_prac_address_lines LONGTEXT DEFAULT NULL, orderer_prac_city VARCHAR(255) DEFAULT NULL, orderer_prac_postal_code VARCHAR(255) DEFAULT NULL, orderer_prac_country_code VARCHAR(255) DEFAULT NULL, orderer_prac_contact LONGTEXT DEFAULT NULL, specimen_collection_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', specimen_source VARCHAR(255) DEFAULT NULL, specimen_source_text VARCHAR(255) DEFAULT NULL, specimen_text VARCHAR(255) DEFAULT NULL, specimen_type_text VARCHAR(255) DEFAULT NULL, specimen_location VARCHAR(255) DEFAULT NULL, specimen_food_type VARCHAR(255) DEFAULT NULL, specimen_animal_type VARCHAR(255) DEFAULT NULL, animal_name VARCHAR(255) DEFAULT NULL, specimen_isolate TINYINT(1) DEFAULT NULL, animal_keeper_name VARCHAR(255) NOT NULL, animal_keeper_address_lines LONGTEXT DEFAULT NULL, animal_keeper_city VARCHAR(255) DEFAULT NULL, animal_keeper_postal_code VARCHAR(255) DEFAULT NULL, animal_keeper_country_code VARCHAR(255) DEFAULT NULL, patient_birth_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', patient_ahv_number VARCHAR(255) DEFAULT NULL, patient_gender LONGTEXT DEFAULT NULL, patient_given_name VARCHAR(255) DEFAULT NULL, patient_family_name VARCHAR(255) DEFAULT NULL, patient_address_lines LONGTEXT DEFAULT NULL, patient_city VARCHAR(255) DEFAULT NULL, patient_postal_code VARCHAR(255) DEFAULT NULL, patient_country_code VARCHAR(255) DEFAULT NULL, received_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', analysis_start_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', finished_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', UNIQUE INDEX UNIQ_D75E6F2A772E836A (identifier), INDEX IDX_D75E6F2AB03A8386 (created_by_id), INDEX IDX_D75E6F2AEE85B337 (last_changed_by_id), INDEX IDX_D75E6F2A416CA59F (orderer_org_id), INDEX IDX_D75E6F2AC94F114D (orderer_prac_id), INDEX IDX_D75E6F2A83D86AEC (animal_keeper_id), INDEX IDX_D75E6F2A6B899279 (patient_id), INDEX IDX_D75E6F2ABF112A8 (specimen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', probe_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', receiver_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', signed_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', created_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', last_changed_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', payload JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', filename VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_C42F77843D2D0D4A (probe_id), INDEX IDX_C42F7784CD53EDB6 (receiver_id), INDEX IDX_C42F7784D2EDD3FB (signed_by_id), INDEX IDX_C42F7784B03A8386 (created_by_id), INDEX IDX_C42F7784EE85B337 (last_changed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specimen (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', specimen_group VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', system VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, abbreviation VARCHAR(255) NOT NULL, shortname VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, medical_validation TINYINT(1) NOT NULL, is_enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D64964082763 (shortname), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE elm_report ADD CONSTRAINT FK_858D2FE13D2D0D4A FOREIGN KEY (probe_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE elm_report ADD CONSTRAINT FK_858D2FE11409DD88 FOREIGN KEY (observation_id) REFERENCES observation (id)');
        $this->addSql('ALTER TABLE elm_report ADD CONSTRAINT FK_858D2FE1B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE elm_report ADD CONSTRAINT FK_858D2FE1EE85B337 FOREIGN KEY (last_changed_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE elm_report ADD CONSTRAINT FK_858D2FE1CA52EA18 FOREIGN KEY (leading_code_id) REFERENCES leading_code (id)');
        $this->addSql('ALTER TABLE elm_report ADD CONSTRAINT FK_858D2FE164180A36 FOREIGN KEY (organism_id) REFERENCES organism (id)');
        $this->addSql('ALTER TABLE elm_report ADD CONSTRAINT FK_858D2FE1BF112A8 FOREIGN KEY (specimen_id) REFERENCES specimen (id)');
        $this->addSql('ALTER TABLE leading_code ADD CONSTRAINT FK_A56A662EBF112A8 FOREIGN KEY (specimen_id) REFERENCES specimen (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE064180A36 FOREIGN KEY (organism_id) REFERENCES organism (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE03D2D0D4A FOREIGN KEY (probe_id) REFERENCES probe (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0EE85B337 FOREIGN KEY (last_changed_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2AEE85B337 FOREIGN KEY (last_changed_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2A416CA59F FOREIGN KEY (orderer_org_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2AC94F114D FOREIGN KEY (orderer_prac_id) REFERENCES practitioner (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2A83D86AEC FOREIGN KEY (animal_keeper_id) REFERENCES animal_keeper (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2A6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE probe ADD CONSTRAINT FK_D75E6F2ABF112A8 FOREIGN KEY (specimen_id) REFERENCES specimen (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77843D2D0D4A FOREIGN KEY (probe_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784D2EDD3FB FOREIGN KEY (signed_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784EE85B337 FOREIGN KEY (last_changed_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elm_report DROP FOREIGN KEY FK_858D2FE13D2D0D4A');
        $this->addSql('ALTER TABLE elm_report DROP FOREIGN KEY FK_858D2FE11409DD88');
        $this->addSql('ALTER TABLE elm_report DROP FOREIGN KEY FK_858D2FE1B03A8386');
        $this->addSql('ALTER TABLE elm_report DROP FOREIGN KEY FK_858D2FE1EE85B337');
        $this->addSql('ALTER TABLE elm_report DROP FOREIGN KEY FK_858D2FE1CA52EA18');
        $this->addSql('ALTER TABLE elm_report DROP FOREIGN KEY FK_858D2FE164180A36');
        $this->addSql('ALTER TABLE elm_report DROP FOREIGN KEY FK_858D2FE1BF112A8');
        $this->addSql('ALTER TABLE leading_code DROP FOREIGN KEY FK_A56A662EBF112A8');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE064180A36');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE03D2D0D4A');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE0B03A8386');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE0EE85B337');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2AB03A8386');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2AEE85B337');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2A416CA59F');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2AC94F114D');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2A83D86AEC');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2A6B899279');
        $this->addSql('ALTER TABLE probe DROP FOREIGN KEY FK_D75E6F2ABF112A8');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77843D2D0D4A');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784CD53EDB6');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784D2EDD3FB');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784B03A8386');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784EE85B337');
        $this->addSql('DROP TABLE animal_keeper');
        $this->addSql('DROP TABLE elm_report');
        $this->addSql('DROP TABLE leading_code');
        $this->addSql('DROP TABLE observation');
        $this->addSql('DROP TABLE organism');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE practitioner');
        $this->addSql('DROP TABLE probe');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE specimen');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
