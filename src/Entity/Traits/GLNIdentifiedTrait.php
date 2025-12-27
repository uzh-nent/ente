<?php

/*
 * This file is part of the baupen project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

/*
 * automatically keeps track of creation time & last change time
 */

trait GLNIdentifiedTrait
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['gln-identified:read', 'gln-identified:write'])]
    private ?string $glnIdentifier;

    public function getGlnIdentifier(): ?string
    {
        return $this->glnIdentifier;
    }

    public function setGlnIdentifier(?string $glnIdentifier): void
    {
        $this->glnIdentifier = $glnIdentifier;
    }
}
