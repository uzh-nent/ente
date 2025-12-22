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

use ApiPlatform\Doctrine\Common\Filter\OrderFilterInterface;
use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\AddressTrait;
use App\Entity\Traits\ContactTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['contact:read', 'thing:read', 'address:read']],
    denormalizationContext: ['groups' => ['contact:write', 'thing:write', 'address:write']]
)]
#[Get]
#[Post]
#[Patch]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: [
    'postalCode' => SearchFilterInterface::STRATEGY_EXACT, 'name' => SearchFilterInterface::STRATEGY_IPARTIAL,
])]
#[ApiFilter(OrderFilter::class, properties: ['name'])]
class Organization
{
    use IdTrait;
    use TimeTrait;
    use ThingTrait;
    use AddressTrait;
    use ContactTrait;

    /**
     * @var Collection<int, Probe>
     */
    #[ORM\OneToMany(targetEntity: Probe::class, mappedBy: 'orderer')]
    private Collection $probes;

    public function __construct()
    {
        $this->probes = new ArrayCollection();
    }

    public function getProbes(): Collection
    {
        return $this->probes;
    }
}
