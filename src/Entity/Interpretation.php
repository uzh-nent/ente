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

use App\Entity\Traits\CodedIdentifierTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Interpretation
{
    use IdTrait;
    use TimeTrait;
    use CodedIdentifierTrait;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $interpretationGroup = null;

    public function getInterpretationGroup(): ?string
    {
        return $this->interpretationGroup;
    }

    public function setInterpretationGroup(?string $interpretationGroup): void
    {
        $this->interpretationGroup = $interpretationGroup;
    }
}
