<?php

namespace App\Entity\Probe;

use App\Services\Elm\ApiBuilder\Dto\AddressDto;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait OrdererOrgCopy
{
    #[ORM\Column(type: Types::STRING)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private string $ordererOrgName = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererOrgAddressLines = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererOrgCity = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererOrgPostalCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererOrgCountryCode = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererOrgContact = null;

    public function getOrdererOrgName(): string
    {
        return $this->ordererOrgName;
    }

    public function setOrdererOrgName(string $ordererOrgName): void
    {
        $this->ordererOrgName = $ordererOrgName;
    }

    public function getOrdererOrgAddressLines(): ?string
    {
        return $this->ordererOrgAddressLines;
    }

    public function setOrdererOrgAddressLines(?string $ordererOrgAddressLines): void
    {
        $this->ordererOrgAddressLines = $ordererOrgAddressLines;
    }

    public function getOrdererOrgCity(): ?string
    {
        return $this->ordererOrgCity;
    }

    public function setOrdererOrgCity(?string $ordererOrgCity): void
    {
        $this->ordererOrgCity = $ordererOrgCity;
    }

    public function getOrdererOrgPostalCode(): ?string
    {
        return $this->ordererOrgPostalCode;
    }

    public function setOrdererOrgPostalCode(?string $orderPostalCode): void
    {
        $this->ordererOrgPostalCode = $orderPostalCode;
    }

    public function getOrdererOrgCountryCode(): ?string
    {
        return $this->ordererOrgCountryCode;
    }

    public function setOrdererOrgCountryCode(?string $ordererOrgCountryCode): void
    {
        $this->ordererOrgCountryCode = $ordererOrgCountryCode;
    }

    public function getOrdererOrgContact(): ?string
    {
        return $this->ordererOrgContact;
    }

    public function setOrdererOrgContact(?string $ordererOrgContact): void
    {
        $this->ordererOrgContact = $ordererOrgContact;
    }

    public function writeOrdererOrgAddressTo(AddressDto $target): void
    {
        $target->setAddressLines($this->ordererOrgAddressLines);
        $target->setCity($this->ordererOrgCity);
        $target->setPostalCode($this->ordererOrgPostalCode);
        $target->setCountryCode($this->ordererOrgCountryCode);
    }
}
