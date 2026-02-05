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
use App\Entity\Traits\ContactTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\PersonTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['person:read', 'address:read', 'contact:read', 'practitioner:read']],
    denormalizationContext: ['groups' => ['person:write', 'address:write', 'contact:write', 'practitioner:write']]
)]
#[Get]
#[Post]
#[Patch]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: [
    'title' => SearchFilterInterface::STRATEGY_IPARTIAL,
    'givenName' => SearchFilterInterface::STRATEGY_IPARTIAL, 'familyName' => SearchFilterInterface::STRATEGY_IPARTIAL,
    'city' => SearchFilterInterface::STRATEGY_IPARTIAL, 'postalCode' => SearchFilterInterface::STRATEGY_START, 'countryCode' => SearchFilterInterface::STRATEGY_IPARTIAL,
])]
#[ApiFilter(OrderFilter::class, properties: ['postalCode', 'familyName'])]
class Practitioner
{
    use IdTrait;
    use TimeTrait;
    use PersonTrait;
    use AddressTrait;
    use ContactTrait;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['practitioner:read', 'practitioner:write'])]
    private ?string $title = '';

    /**
     * @var Collection<int, Probe>
     */
    #[ORM\OneToMany(targetEntity: Probe::class, mappedBy: 'ordererPrac')]
    private Collection $probes;

    public function __construct()
    {
        $this->probes = new ArrayCollection();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return Collection<int, Probe>
     */
    public function getProbes(): Collection
    {
        return $this->probes;
    }

    public function getFullAddress(): string
    {
        $fullName = trim($this->getGivenName() . " " . $this->getFullAddress());

        return join("\n", array_filter([$fullName, $this->getAddress()]));
    }
}
