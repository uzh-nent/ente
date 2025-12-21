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

trait AddressTrait
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $addressLines = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $countryCode = null;
}
