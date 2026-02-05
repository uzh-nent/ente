<?php

declare(strict_types=1);

namespace App\Security\Authentication\OAuth2;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

readonly class AzureResourceOwner implements ResourceOwnerInterface
{
    /**
     * @param array<mixed> $data
     */
    public function __construct(private array $data)
    {
    }

    public function getId(): string
    {
        return $this->data['oid'];
    }

    public function getFirstName(): string
    {
        return $this->data['given_name'];
    }

    public function getLastName(): string
    {
        return $this->data['family_name'];
    }

    public function getShortName(): string
    {
        return $this->data['shortname'];
    }

    public function getEmail(): string
    {
        return $this->data['email'];
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
