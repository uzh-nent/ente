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
use App\Entity\Probe;
use App\Helper\DoctrineHelper;
use App\Services\Interfaces\EmailServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportTasks extends Command
{
    use LockableTrait;

    private ManagerRegistry $doctrine;

    private LoggerInterface $logger;
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger, ManagerRegistry $managerRegistry)
    {
        parent::__construct();

        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->registry = $managerRegistry;
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
        $eras = $this->doctrine->getRepository(Organism::class)->findAll();
        if (count($eras) > 0) {
            $this->logger->info("Already imported; aborting");
            return Command::FAILURE;
        }

        // TODO

        return Command::SUCCESS;
    }
}
