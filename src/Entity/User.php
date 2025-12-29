<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\ElmReportProcessor;
use App\Api\Provider\ElmReportProvider;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    paginationEnabled: false
)]
#[Get]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: ['isEnabled' => SearchFilterInterface::STRATEGY_EXACT])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use IdTrait;
    use TimeTrait;

    public const string SUPER_SHORTNAME = 'flomos';

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['user:read'])]
    private ?string $name;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['user:read'])]
    private ?string $abbreviation;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private ?string $shortname;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['user:read'])]
    private bool $medicalValidation = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['user:read'])]
    private bool $isEnabled = true;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): void
    {
        $this->abbreviation = $abbreviation;
    }

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setShortname(string $shortname): void
    {
        $this->shortname = $shortname;
    }

    public function getUserIdentifier(): string
    {
        return $this->shortname;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isMedicalValidation(): bool
    {
        return $this->medicalValidation;
    }

    public function setMedicalValidation(bool $medicalValidation): void
    {
        $this->medicalValidation = $medicalValidation;
    }

    public function eraseCredentials(): void
    {
        // never plain password stored
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        if ($this->shortname === self::SUPER_SHORTNAME) {
            $roles[] = 'ROLE_ADMIN';
            $roles[] = 'ROLE_ALLOWED_TO_SWITCH';
        }

        return array_unique($roles);
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }
}
