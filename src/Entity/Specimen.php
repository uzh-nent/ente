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
use App\Entity\Traits\HideableTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['coded-identifier:read', 'hideable:read', 'specimen:read']],
    paginationEnabled: false
)]
#[Get]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: ['specimenGroup' => SearchFilterInterface::STRATEGY_EXACT])]
#[ApiFilter(OrderFilter::class, properties: ['displayName'])]
class Specimen
{
    use IdTrait;
    use TimeTrait;
    use CodedIdentifierTrait;
    use HideableTrait;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['specimen:read'])]
    private ?string $reportTranslation = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['specimen:read'])]
    private ?string $specimenGroup = null;

    public function getReportTranslation(): ?string
    {
        return $this->reportTranslation;
    }

    public function setReportTranslation(?string $reportTranslation): void
    {
        $this->reportTranslation = $reportTranslation;
    }

    public function getSpecimenGroup(): ?string
    {
        return $this->specimenGroup;
    }

    public function setSpecimenGroup(?string $specimenGroup): void
    {
        $this->specimenGroup = $specimenGroup;
    }

    public function getIndividualSpecimenGroups(): array
    {
        $groups = array_map(fn($entry) => trim($entry), explode(",", $this->specimenGroup));
        return array_filter($groups);
    }
}
