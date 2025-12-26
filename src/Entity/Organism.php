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
use App\Entity\Traits\CodedIdentifierTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    denormalizationContext: ['groups' => ['coded-identifier:read', 'organism:read']],
    paginationEnabled: false
)]
#[Get]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: ['organismGroup' => SearchFilterInterface::STRATEGY_EXACT])]
#[ApiFilter(OrderFilter::class, properties: ['displayName'])]
class Organism
{
    use IdTrait;
    use TimeTrait;
    use CodedIdentifierTrait;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['organism:read'])]
    private ?string $organismGroup = null;

    public function getOrganismGroup(): ?string
    {
        return $this->organismGroup;
    }

    public function setOrganismGroup(?string $organismGroup): void
    {
        $this->organismGroup = $organismGroup;
    }
}
