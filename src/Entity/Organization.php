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
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Organization
{
    use IdTrait;
    use TimeTrait;
    use ThingTrait;
    use AddressTrait;

    /**
     * @var Collection<int, Probe>
     */
    #[Groups(['item:read'])]
    #[ORM\OneToMany(targetEntity: Probe::class, mappedBy: 'orderer')]
    private Collection $probes;

    public function __construct()
    {
        $this->probes = new ArrayCollection();
    }

    public function getProbes(): Collection
    {
        return $this->probes;
    }
}
