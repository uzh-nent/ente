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

use App\Enum\CodeSystem;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait HideableTrait
{
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    #[Groups(['hideable:read', 'hideable:write'])]
    private bool $isHidden = false;

    public function getIsHidden(): bool
    {
        return $this->isHidden;
    }

    public function setIsHidden(bool $isHidden): void
    {
        $this->isHidden = $isHidden;
    }
}
