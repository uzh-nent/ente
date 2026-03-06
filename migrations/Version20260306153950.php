<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260306153950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE probe ADD orderer_org_ber VARCHAR(255) DEFAULT NULL, ADD orderer_org_uid VARCHAR(255) DEFAULT NULL, ADD orderer_prac_gln VARCHAR(255) DEFAULT NULL, ADD animal_keeper_ber VARCHAR(255) DEFAULT NULL, ADD animal_keeper_uid VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE probe DROP orderer_org_ber, DROP orderer_org_uid, DROP orderer_prac_gln, DROP animal_keeper_ber, DROP animal_keeper_uid');
    }
}
