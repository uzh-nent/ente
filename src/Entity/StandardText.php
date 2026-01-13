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
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use App\Enum\Pathogen;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['standard-text:read']],
    paginationEnabled: false
)]
#[Get]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: ['pathogen' => SearchFilterInterface::STRATEGY_EXACT])]
class StandardText
{
    use IdTrait;
    use TimeTrait;

    #[ORM\Column(type: Types::STRING, enumType: Pathogen::class, nullable: true)]
    #[Groups(['standard-text:read'])]
    private ?Pathogen $pathogen = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['standard-text:read'])]
    private ?string $text = null;

    public function getPathogen(): ?Pathogen
    {
        return $this->pathogen;
    }

    public function setPathogen(?Pathogen $pathogen): void
    {
        $this->pathogen = $pathogen;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }
}
