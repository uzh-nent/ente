<?php

declare(strict_types=1);

namespace App\Tests\Services;

use App\Enum\ElmApiStatus;
use App\Helper\DoctrineHelper;
use App\Services\Interfaces\ElmServiceInterface;
use App\Tests\Builders\ElmReportBuilder;
use App\Tests\Builders\ProbeBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ElmServiceTest extends KernelTestCase
{
    public function testSalmonellaSubmit(): void
    {
        self::bootKernel();

        // prepare test data
        /** @var ManagerRegistry $doctrine */
        $doctrine = self::getContainer()->get(ManagerRegistry::class);
        $probe = new ProbeBuilder()->withReferenceLaboratoryOrder()->withHumanProbe()->build();
        $report = new ElmReportBuilder()->withProbe($probe)->withSalmonellaComplete()->build();
        DoctrineHelper::persistAndFlush($doctrine,
            $probe, $probe->getOrdererOrg(), $probe->getPatient(),
            $report, $report->getLeadingCode(), $report->getSpecimen(), $report->getOrganism()
        );

        // do API submission
        /** @var ElmServiceInterface $elmService */
        $elmService = self::getContainer()->get(ElmServiceInterface::class);
        $elmService->send($report);
        $this->assertEquals($report->getApiStatus(), ElmApiStatus::IN_PROGRESS);
    }
}
