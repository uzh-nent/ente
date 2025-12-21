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

trait CodedIdentifierTrait
{
    #[ORM\Column(type: Types::STRING, enumType: CodeSystem::class, nullable: true)]
    private ?CodeSystem $system = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $displayName = null;
}
