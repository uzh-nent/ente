<?php

/*
 * This file is part of the baupen project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait AddressTrait
{
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['address:read', 'address:write'])]
    private ?string $addressLines = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['address:read', 'address:write'])]
    private ?string $city = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['address:read', 'address:write'])]
    private ?string $postalCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['address:read', 'address:write'])]
    private ?string $countryCode = null;

    public function getAddressLines(): ?string
    {
        return $this->addressLines;
    }

    public function setAddressLines(?string $addressLines): void
    {
        $this->addressLines = $addressLines;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getAddress(): string
    {
        $countryPrefix = $this->getCountryCode() === 'CH' ? "" : $this->getCountryCode() . " ";
        $city = trim($this->getPostalCode() . " " . $this->getCity());

        return join("\n", array_filter([
            $this->getAddressLines(),
            $countryPrefix . $city
        ]));
    }
}
