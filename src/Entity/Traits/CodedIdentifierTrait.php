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

trait CodedIdentifierTrait
{
    #[ORM\Column(type: Types::STRING, enumType: CodeSystem::class)]
    #[Groups(['coded-identifier:read'])]
    private CodeSystem $system = CodeSystem::LOINC;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['coded-identifier:read'])]
    private string $code = '';

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['coded-identifier:read'])]
    private string $displayName = '';

    public function getSystem(): CodeSystem
    {
        return $this->system;
    }

    public function setSystem(CodeSystem $system): void
    {
        $this->system = $system;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }
}
