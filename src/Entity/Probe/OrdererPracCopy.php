<?php

namespace App\Entity\Probe;

use App\Services\Elm\ApiBuilder\Dto\AddressDto;
use App\Services\Elm\ApiBuilder\Dto\PersonDto;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait OrdererPracCopy
{
    #[ORM\Column(type: Types::STRING)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private string $ordererPracGivenName = '';

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private string $ordererPracFamilyName = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracAddressLines = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracCity = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracPostalCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracCountryCode = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracContact = null;

    public function getOrdererPracGivenName(): string
    {
        return $this->ordererPracGivenName;
    }

    public function setOrdererPracGivenName(string $ordererPracGivenName): void
    {
        $this->ordererPracGivenName = $ordererPracGivenName;
    }

    public function getOrdererPracFamilyName(): string
    {
        return $this->ordererPracFamilyName;
    }

    public function setOrdererPracFamilyName(string $ordererPracFamilyName): void
    {
        $this->ordererPracFamilyName = $ordererPracFamilyName;
    }

    public function getOrdererPracAddressLines(): ?string
    {
        return $this->ordererPracAddressLines;
    }

    public function setOrdererPracAddressLines(?string $ordererPracAddressLines): void
    {
        $this->ordererPracAddressLines = $ordererPracAddressLines;
    }

    public function getOrdererPracCity(): ?string
    {
        return $this->ordererPracCity;
    }

    public function setOrdererPracCity(?string $ordererPracCity): void
    {
        $this->ordererPracCity = $ordererPracCity;
    }

    public function getOrdererPracPostalCode(): ?string
    {
        return $this->ordererPracPostalCode;
    }

    public function setOrdererPracPostalCode(?string $orderPostalCode): void
    {
        $this->ordererPracPostalCode = $orderPostalCode;
    }

    public function getOrdererPracCountryCode(): ?string
    {
        return $this->ordererPracCountryCode;
    }

    public function setOrdererPracCountryCode(?string $ordererPracCountryCode): void
    {
        $this->ordererPracCountryCode = $ordererPracCountryCode;
    }

    public function getOrdererPracContact(): ?string
    {
        return $this->ordererPracContact;
    }

    public function setOrdererPracContact(?string $ordererPracContact): void
    {
        $this->ordererPracContact = $ordererPracContact;
    }

    public function writeOrdererPracAddressTo(AddressDto $target): void
    {
        $target->setAddressLines($this->ordererPracAddressLines);
        $target->setCity($this->ordererPracCity);
        $target->setPostalCode($this->ordererPracPostalCode);
        $target->setCountryCode($this->ordererPracCountryCode);
    }

    public function writeOrdererPracPersonTo(PersonDto $target): void
    {
        $target->setGivenName($this->ordererPracGivenName);
        $target->setFamilyName($this->ordererPracFamilyName);
    }
}
