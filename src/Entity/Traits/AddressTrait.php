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

trait AddressTrait
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $addressLines = null {
        get => $this->addressLines;
        set => $this->addressLines = $value;
    }

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $city = null {
        get => $this->city;
        set => $this->city = $value;
    }

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $postalCode = null {
        get => $this->postalCode;
        set => $this->postalCode = $value;
    }

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $countryCode = null {
        get => $this->countryCode;
        set => $this->countryCode = $value;
    }
}
