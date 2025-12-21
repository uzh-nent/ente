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

use App\Entity\Probe;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

trait AttributionTrait
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?Probe $createdBy = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?Probe $lastChangedBy = null;
}
