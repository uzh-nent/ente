<?php

namespace App\Tests\Api\Traits;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Repository\UserRepository;

trait AuthenticationTrait {

    public function loginSomeUser(Client $client): void
    {
        $container = $client->getContainer();

        $userRepository = $container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);

        if (!$testUser) {
            throw new Exception('Call createSomeUser() first.');
        }

        $client->loginUser($testUser);
    }
}
