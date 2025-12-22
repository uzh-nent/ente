<?php

/*
 * This file is part of the baupen project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\AddressTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\PersonTrait;
use App\Entity\Traits\TimeTrait;
use App\Enum\AdministrativeGender;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['person:read', 'address:read', 'patient:read']],
    denormalizationContext: ['groups' => ['person:write', 'address:write', 'patient:write']]
)]
#[Get]
#[Post]
#[Patch]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: [
    'birthDate' => SearchFilterInterface::STRATEGY_EXACT, 'ahvNumber' => SearchFilterInterface::STRATEGY_START,
])]
#[ApiFilter(OrderFilter::class, properties: ['givenName', 'familyName', 'birthDate'])]
class Patient
{
    use IdTrait;
    use TimeTrait;
    use PersonTrait;
    use AddressTrait;

    #[ORM\Column(type: Types::TEXT, enumType: AdministrativeGender::class, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?AdministrativeGender $gender = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?\DateTimeImmutable $birthDate = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $ahvNumber = null;

    /**
     * @var Collection<int, Probe>
     */
    #[ORM\OneToMany(targetEntity: Probe::class, mappedBy: 'patient')]
    private Collection $probes;

    public function __construct()
    {
        $this->probes = new ArrayCollection();
    }

    public function getGender(): ?AdministrativeGender
    {
        return $this->gender;
    }

    public function setGender(?AdministrativeGender $gender): void
    {
        $this->gender = $gender;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeImmutable $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getAhvNumber(): ?string
    {
        return $this->ahvNumber;
    }

    public function setAhvNumber(?string $ahvNumber): void
    {
        $this->ahvNumber = $ahvNumber;
    }

    /**
     * @return Collection<int, Probe>
     */
    public function getProbes(): Collection
    {
        return $this->probes;
    }
}
