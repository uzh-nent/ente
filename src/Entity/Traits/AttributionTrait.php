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

use ApiPlatform\Metadata\ApiProperty;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait AttributionTrait
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['attribution:read'])]
    #[ApiProperty(readableLink: false, writableLink: false)]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['attribution:read'])]
    #[ApiProperty(readableLink: false, writableLink: false)]
    private ?User $lastChangedBy = null;

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getLastChangedBy(): User
    {
        return $this->lastChangedBy;
    }

    public function setLastChangedBy(User $lastChangedBy): void
    {
        $this->lastChangedBy = $lastChangedBy;
    }

    public function attribute(User $user): void
    {
        if ($this->createdBy === null) {
            $this->createdBy = $user;
        }

        $this->lastChangedBy = $user;
    }
}
