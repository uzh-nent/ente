<?php

namespace App\Entity\Probe;

use App\Entity\Practitioner;
use App\Services\Elm\ApiBuilder\Dto\AddressDto;
use App\Services\Elm\ApiBuilder\Dto\PersonDto;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait OrdererPracCopy
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracGln = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracTitle = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracGivenName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracFamilyName = null;

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

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracEmail = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracPhone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['orderer:read', 'orderer:write'])]
    private ?string $ordererPracContact = null;

    public function getOrdererPracGln(): ?string
    {
        return $this->ordererPracGln;
    }

    public function setOrdererPracGln(?string $ordererPracGln): void
    {
        $this->ordererPracGln = $ordererPracGln;
    }

    public function getOrdererPracTitle(): ?string
    {
        return $this->ordererPracTitle;
    }

    public function setOrdererPracTitle(?string $ordererPracTitle): void
    {
        $this->ordererPracTitle = $ordererPracTitle;
    }

    public function getOrdererPracGivenName(): ?string
    {
        return $this->ordererPracGivenName;
    }

    public function setOrdererPracGivenName(?string $ordererPracGivenName): void
    {
        $this->ordererPracGivenName = $ordererPracGivenName;
    }

    public function getOrdererPracFamilyName(): ?string
    {
        return $this->ordererPracFamilyName;
    }

    public function setOrdererPracFamilyName(?string $ordererPracFamilyName): void
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

    public function getOrdererPracEmail(): ?string
    {
        return $this->ordererPracEmail;
    }

    public function setOrdererPracEmail(?string $ordererPracEmail): void
    {
        $this->ordererPracEmail = $ordererPracEmail;
    }

    public function getOrdererPracPhone(): ?string
    {
        return $this->ordererPracPhone;
    }

    public function setOrdererPracPhone(?string $ordererPracPhone): void
    {
        $this->ordererPracPhone = $ordererPracPhone;
    }

    public function getOrdererPracContact(): ?string
    {
        return $this->ordererPracContact;
    }

    public function setOrdererPracContact(?string $ordererPracContact): void
    {
        $this->ordererPracContact = $ordererPracContact;
    }

    public function copyOrdererPracFrom(Practitioner $practitioner): void
    {
        $this->ordererPracGivenName = $practitioner->getGivenName();
        $this->ordererPracFamilyName = $practitioner->getFamilyName();
        $this->ordererPracAddressLines = $practitioner->getAddressLines();
        $this->ordererPracCountryCode = $practitioner->getCountryCode();
        $this->ordererPracCity = $practitioner->getCity();
        $this->ordererPracPostalCode = $practitioner->getPostalCode();
        $this->ordererPracEmail = $practitioner->getEmail();
        $this->ordererPracPhone = $practitioner->getPhone();
        $this->ordererPracContact = $practitioner->getContact();
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

    public function getOrdererPracFullAddress(): string
    {
        $fullName = trim($this->getOrdererPracGivenName() . " " . $this->getOrdererPracFamilyName());
        $countryPrefix = $this->getOrdererPracCountryCode() === 'CH' ? "" : $this->getOrdererPracCountryCode() . " ";
        $city = trim($this->getOrdererPracPostalCode() . " " . $this->getOrdererPracCity());

        return join("\n", array_filter([
            $fullName,
            $this->getOrdererPracAddressLines(),
            $countryPrefix . $city
        ]));
    }

    public function getOrdererPracShortAddress(): string
    {
        $fullName = trim($this->getOrdererPracTitle() . " " . $this->getOrdererPracGivenName() . " " . $this->getOrdererPracFamilyName());
        $countryPrefix = $this->getOrdererPracCountryCode() === 'CH' ? "" : $this->getOrdererPracCountryCode() . " ";
        $city = trim($this->getOrdererPracPostalCode() . " " . $this->getOrdererPracCity());

        return join(", ", array_filter([
            $fullName,
            $countryPrefix . $city
        ]));
    }
}
