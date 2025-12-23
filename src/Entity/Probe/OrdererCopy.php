<?php

namespace App\Entity\Probe;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait OrdererCopy
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererAddressLines = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererCity = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $orderPostalCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererCountryCode = null;

    public function getOrdererName(): ?string
    {
        return $this->ordererName;
    }

    public function setOrdererName(?string $ordererName): void
    {
        $this->ordererName = $ordererName;
    }

    public function getOrdererAddressLines(): ?string
    {
        return $this->ordererAddressLines;
    }

    public function setOrdererAddressLines(?string $ordererAddressLines): void
    {
        $this->ordererAddressLines = $ordererAddressLines;
    }

    public function getOrdererCity(): ?string
    {
        return $this->ordererCity;
    }

    public function setOrdererCity(?string $ordererCity): void
    {
        $this->ordererCity = $ordererCity;
    }

    public function getOrderPostalCode(): ?string
    {
        return $this->orderPostalCode;
    }

    public function setOrderPostalCode(?string $orderPostalCode): void
    {
        $this->orderPostalCode = $orderPostalCode;
    }

    public function getOrdererCountryCode(): ?string
    {
        return $this->ordererCountryCode;
    }

    public function setOrdererCountryCode(?string $ordererCountryCode): void
    {
        $this->ordererCountryCode = $ordererCountryCode;
    }
}
