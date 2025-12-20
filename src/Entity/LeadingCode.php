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

use App\Entity\Traits\AddressTrait;
use App\Entity\Traits\AttributionTrait;
use App\Entity\Traits\CodedIdentifierTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\PersonTrait;
use App\Entity\Traits\TimeTrait;
use App\Enum\Pathogen;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class LeadingCode
{
    use IdTrait;
    use TimeTrait;
    use CodedIdentifierTrait;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?Pathogen $pathogen = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $organismGroup = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $specimenGroup = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $interpretationGroup = null;

    #[ORM\OneToMany(targetEntity: Specimen::class)]
    private ?string $specimen = null;
}
