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

trait BusinessIdentifierTrait
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['business-identifier:read', 'business-identifier:write'])]
    private ?string $ber;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['business-identifier:read', 'business-identifier:write'])]
    private ?string $uid;

    public function getBer(): ?string
    {
        return $this->ber;
    }

    public function setBer(?string $ber): void
    {
        $this->ber = $ber;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid): void
    {
        $this->uid = $uid;
    }
}
