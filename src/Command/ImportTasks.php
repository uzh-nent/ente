<?php

declare(strict_types=1);

/*
 * This file is part of the evoting.uzh.ch project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Entity\LeadingCode;
use App\Entity\Organism;
use App\Entity\Specimen;
use App\Enum\CodeSystem;
use App\Enum\InterpretationGroup;
use App\Enum\Pathogen;
use App\Helper\DoctrineHelper;
use Doctrine\Bundle\DoctrineBundle\Twig\DoctrineExtension;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ImportTasks extends Command
{
    use LockableTrait;

    private const string RESOURCES_DIR = __DIR__ . '/../../assets/resources';

    public function __construct(private readonly ManagerRegistry $doctrine)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:import')
            // the short description shown while running "php bin/console list"
            ->setDescription('Imports the various data sources into the DB.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->importSpecimen($output);
        $this->importOrganism($output);
        $this->importLeadingCode($output);

        return Command::SUCCESS;
    }

    /**
     * @param OutputInterface $output
     */
    public function importLeadingCode(OutputInterface $output): void
    {
        /** @var LeadingCode[] $leadingCodes */
        $leadingCodes = $this->doctrine->getRepository(LeadingCode::class)->findAll();

        $updateCount = 0;
        $createCount = 0;
        $loincLeadingCodes = $this->readTsvFile('loinc_leading_code.tsv');
        foreach ($loincLeadingCodes as $leadingCode) {
            $entity = new LeadingCode();
            $entity->setCode($leadingCode['code']);
            $entity->setDisplayName($leadingCode['display_name']);
            $entity->setSystem(CodeSystem::LOINC);

            $entity->setPathogen(self::parsePathogen($leadingCode['pathogen']));
            $entity->setOrganismGroup($leadingCode['organism_group']);
            $entity->setSpecimenGroup($leadingCode['specimen_group']);
            $entity->setInterpretationGroup(self::parseInterpretationGroup($leadingCode['interpretation_group']));

            if (isset($leadingCode['specimen_snomed_code'])) {
                $specimen = $this->doctrine->getRepository(Specimen::class)->findOneBy(['code' => $leadingCode['specimen_snomed_code'], 'system' => CodeSystem::SNOMED]);
                if (!$specimen) {
                    $output->writeln("Specimen with code {$leadingCode['specimen_snomed_code']} not found.");
                } else {
                    $entity->setSpecimen($specimen);
                }
            }

            $existingLeadingCode = array_find($leadingCodes, fn(LeadingCode $s) => $s->isDuplicateOf($entity));
            if ($existingLeadingCode) {
                $existingLeadingCode->setDisplayName($entity->getDisplayName());
                $existingLeadingCode->setPathogen($entity->getPathogen());
                $existingLeadingCode->setOrganismGroup($entity->getOrganismGroup());
                $existingLeadingCode->setSpecimenGroup($entity->getSpecimenGroup());
                $existingLeadingCode->setInterpretationGroup($entity->getInterpretationGroup());
                $existingLeadingCode->setSpecimen($entity->getSpecimen());
                $updateCount++;
                DoctrineHelper::persistAndFlush($this->doctrine, $existingLeadingCode);
            } else {
                $createCount++;
                DoctrineHelper::persistAndFlush($this->doctrine, $entity);
            }
        }

        $output->writeln("Updated $updateCount leading loinc, created $createCount new ones.");
    }

    /**
     * @param OutputInterface $output
     */
    public function importSpecimen(OutputInterface $output): void
    {
        /** @var Specimen[] $specimens */
        $specimens = $this->doctrine->getRepository(Specimen::class)->findAll();

        $updateCount = 0;
        $createCount = 0;
        $snomedSpecimen = $this->readTsvFile('snomed_specimen.tsv');
        foreach ($snomedSpecimen as $specimen) {
            $entity = new Specimen();
            $entity->setCode($specimen['code']);
            $entity->setDisplayName($specimen['display_name']);
            $entity->setSpecimenGroup($specimen['specimen_group']);
            $entity->setSystem(CodeSystem::SNOMED);

            $existingSpecimen = array_find($specimens, fn(Specimen $s) => $s->isDuplicateOf($entity));
            if ($existingSpecimen) {
                if ($existingSpecimen->getDisplayName() !== $entity->getDisplayName()) {
                    $existingSpecimen->setDisplayName($entity->getDisplayName());
                    $updateCount++;
                    DoctrineHelper::persistAndFlush($this->doctrine, $existingSpecimen);
                }
            } else {
                $createCount++;
                DoctrineHelper::persistAndFlush($this->doctrine, $entity);
            }
        }

        $output->writeln("Updated $updateCount specimen, created $createCount new ones.");
    }

    /**
     * @param OutputInterface $output
     */
    public function importOrganism(OutputInterface $output): void
    {
        /** @var Organism[] $organisms */
        $organisms = $this->doctrine->getRepository(Organism::class)->findAll();

        $updateCount = 0;
        $createCount = 0;
        $createOrUpdate = function (Organism $importedEntity) use (&$updateCount, &$createCount, $organisms) {
            $existingOrganism = array_find($organisms, fn(Organism $o) => $o->isDuplicateOf($importedEntity));
            if ($existingOrganism) {
                if ($existingOrganism->getDisplayName() !== $importedEntity->getDisplayName() || $existingOrganism->getPathogen() !== $importedEntity->getPathogen()) {
                    $existingOrganism->setDisplayName($importedEntity->getDisplayName());
                    $existingOrganism->setPathogen($importedEntity->getPathogen());
                    $updateCount++;
                    DoctrineHelper::persistAndFlush($this->doctrine, $existingOrganism);
                }
            } else {
                $createCount++;
                DoctrineHelper::persistAndFlush($this->doctrine, $importedEntity);
            }
        };

        $snomedOrganisms = $this->readTsvFile('snomed_organism.tsv');
        foreach ($snomedOrganisms as $organism) {
            $entity = new Organism();
            $entity->setCode($organism['code']);
            $entity->setDisplayName($organism['display_name']);
            $entity->setOrganismGroup($organism['organism_group']);
            $entity->setSystem(CodeSystem::SNOMED);
            $entity->setPathogen(self::parsePathogen($organism['pathogen']));

            $createOrUpdate($entity);
        }

        $snomedSalOrganisms = $this->readTsvFile('snomed_organism_sal_org_complete.tsv');
        foreach ($snomedSalOrganisms as $organism) {
            $entity = new Organism();
            $entity->setCode($organism['code']);
            $entity->setDisplayName($organism['display_name']);
            $entity->setOrganismGroup('sal_org_complete');
            $entity->setSystem(CodeSystem::SNOMED);
            $entity->setPathogen(Pathogen::SALMONELLA);

            $createOrUpdate($entity);
        }

        $output->writeln("Updated $updateCount organisms, created $createCount new ones.");
    }

    private function parsePathogen(string $pathogen): Pathogen
    {
        return match ($pathogen) {
            'Salmonella', 'Salmonella Typhi/Paratyphi' => Pathogen::SALMONELLA,
            'Shigella' => Pathogen::SHIGELLA,
            'Vibrio cholerae' => Pathogen::VIBRIO_CHOLERAE,
            'Listeria monocytogenes' => Pathogen::LISTERIA_MONOCYTOGENES,
            'Yersinia Pestis', 'Yersinia pestis' => Pathogen::YERSINIA,
            'Enterohaemorrhagic Escherichia coli' => Pathogen::ESCHERICHIA_COLI,
            default => throw new \Exception("Unknown pathogen $pathogen"),
        };
    }

    private function parseInterpretationGroup(string $interpretation): InterpretationGroup
    {
        return match ($interpretation) {
            'POS-NEG' => InterpretationGroup::POS_NEG, // mapping TEXT to POS_NEG as probably a typo in the source data
            'POS' => InterpretationGroup::POS,
            'TEXT' => InterpretationGroup::TEXT,
            default => throw new \Exception("Unknown interpretation $interpretation"),
        };
    }

    /**
     * @return array<string[]>
     */
    protected function readTsvFile(string $filename): array
    {
        $fileContent = file_get_contents(self::RESOURCES_DIR . "/" . $filename);

        $csvEncoder = new CsvEncoder();
        $entries = $csvEncoder->decode($fileContent, 'csv', [CsvEncoder::DELIMITER_KEY => "\t"]);
        foreach ($entries as $entry) {
            $entry['display_name'] = preg_replace('~\x{00a0}~u', '', $entry['display_name']);
        }

        return $entries;
    }
}
