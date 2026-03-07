<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Helper\DoctrineHelper;
use App\Repository\UserRepository;
use App\Services\Interfaces\ExportServiceInterface;
use App\Tests\Api\Traits\AuthenticationTrait;
use App\Tests\Builders\ProbeBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Zenstruck\Foundry\Test\ResetDatabase;

class ProbeTest extends ApiTestCase
{
    use ResetDatabase;
    use AuthenticationTrait;

    protected static ?bool $alwaysBootKernel = true;

    public function testGetCollection(): void
    {
        // prepare test data
        /** @var ManagerRegistry $doctrine */
        $doctrine = self::getContainer()->get(ManagerRegistry::class);
        $probe = (new ProbeBuilder())->withReferenceLaboratoryOrder()->withHumanProbe()->build();
        DoctrineHelper::persistAndFlush($doctrine, $probe, $probe->getCreatedBy(), $probe->getOrdererOrg(), $probe->getPatient());

        $client = static::createClient();
        $this->loginSomeUser($client);

        $client->request('GET', '/api/probes');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $client->request('GET', '/api/probes', ['headers' => ['accept' => ExportServiceInterface::MIME_EXCEL], 'query' => ['pagination' => '0']]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', ExportServiceInterface::MIME_EXCEL);
    }
}
