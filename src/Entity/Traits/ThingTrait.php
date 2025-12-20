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

/*
 * automatically keeps track of creation time & last change time
 */

trait ThingTrait
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $name = null {
        get => $this->name;
        set => $this->name = $value;
    }
}
