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

use App\Entity\Organism;
use App\Enum\CodeSystem;
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

    public function __construct(private readonly ManagerRegistry $doctrine, private readonly LoggerInterface $logger, private readonly SerializerInterface $serializer)
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

        return Command::SUCCESS;
    }

    protected function readTsvFile(string $filename): array
    {
        $fileContent = file_get_contents(self::RESOURCES_DIR ."/".$filename);

        $csvEncoder = new CsvEncoder();
        $entries = $csvEncoder->decode($fileContent, 'csv', [CsvEncoder::DELIMITER_KEY => "\t"]);
        foreach ($entries as $entry) {
            $entry['display_name'] = preg_replace('~\x{00a0}~u','',$entry['display_name']);
        }

        return $entries;
    }

    private function parsePathogen(string $pathogen) : Pathogen
    {
        return match ($pathogen) {
            'Salmonella', 'Salmonella Typhi/Paratyphi' => Pathogen::SALMONELLA,
            'Shigella' => Pathogen::SHIGELLA,
            'Vibrio cholerae' => Pathogen::VIBRIO_CHOLERAE,
            'Listeria monocytogenes' => Pathogen::LISTERIA_MONOCYTOGENES,
            'Yersinia pestis' => Pathogen::YERSINIA,
        };
    }
}
