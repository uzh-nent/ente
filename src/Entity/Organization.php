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
use App\Entity\Traits\GLNIdentifiedTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['thing:read', 'gln-identified:read', 'address:read', 'contact:read']],
    denormalizationContext: ['groups' => ['thing:write', 'gln-identified:write', 'address:write', 'contact:write']]
)]
#[Get]
#[Post]
#[Patch]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: [
    'postalCode' => SearchFilterInterface::STRATEGY_START, 'name' => SearchFilterInterface::STRATEGY_IPARTIAL,
])]
#[ApiFilter(OrderFilter::class, properties: ['postalCode', 'name'])]
class Organization
{
    use IdTrait;
    use TimeTrait;
    use ThingTrait;
    use GLNIdentifiedTrait;
    use AddressTrait;
    use ContactTrait;

    /**
     * @var Collection<int, Probe>
     */
    #[ORM\OneToMany(targetEntity: Probe::class, mappedBy: 'ordererOrg')]
    private Collection $probes;

    public function __construct()
    {
        $this->probes = new ArrayCollection();
    }

    /**
     * @return Collection<int, Probe>
     */
    public function getProbes(): Collection
    {
        return $this->probes;
    }
}
