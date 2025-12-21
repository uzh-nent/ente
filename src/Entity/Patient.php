<?php

/*
 * This file is part of the baupen project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Traits\AddressTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\PersonTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Patient
{
    use IdTrait;
    use TimeTrait;
    use PersonTrait;
    use AddressTrait;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTime $birthDate = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $ahvNumber = null;

    /**
     * @var Collection<int, Probe>
     */
    #[Groups(['item:read'])]
    #[ORM\OneToMany(targetEntity: Probe::class, mappedBy: 'patient')]
    private Collection $probes;

    public function __construct()
    {
        $this->probes = new ArrayCollection();
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTime $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getAhvNumber(): ?string
    {
        return $this->ahvNumber;
    }

    public function setAhvNumber(?string $ahvNumber): void
    {
        $this->ahvNumber = $ahvNumber;
    }

    public function getProbes(): Collection
    {
        return $this->probes;
    }
}
