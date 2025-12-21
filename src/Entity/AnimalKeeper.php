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
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\PersonTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class AnimalKeeper
{
    use IdTrait;
    use TimeTrait;
    use ThingTrait;
    use PersonTrait;
    use AddressTrait;
}
