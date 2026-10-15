<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migrates cgMLST into dedicated observation
 */
final class Version20261015100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // we do not copy the comment field, as unclear whether it would make sense. Fine as NULL in prod data
        $this->addSql('
            INSERT INTO observation (
                 id,
                organism_id,
                probe_id,
                created_by_id,
                last_changed_by_id,
                analysis_type,
                effective_at,
                created_at,
                last_changed_at,
                interpretation_text,
                pathogen,
                pathogen_name
            )
            SELECT
                UUID(),
                organism_id,
                probe_id,
                created_by_id,
                last_changed_by_id,
                "CG_MLST",
                effective_at,
                created_at,
                last_changed_at,
                cg_mlst,
                pathogen,
                pathogen_name
            FROM observation
            WHERE cg_mlst IS NOT NULL
        ');
        $this->addSql('
            UPDATE probe
            SET analysis_types = CONCAT(analysis_types, \',CG_MLST\')
            WHERE id IN (
                SELECT probe_id
                FROM observation
                WHERE cg_mlst IS NOT NULL
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM observation WHERE analysis_type = "CG_MLST"');
        $this->addSql('
            UPDATE probe
            SET analysis_types = LEFT(
                analysis_types,
                LENGTH(analysis_types) - LENGTH(\',CG_MLST\')
            )
            WHERE analysis_types LIKE \'%,CG_MLST\'
        ');
    }
}
