<?php

namespace App\Tests\Builders;

use App\Entity\User;

/**
 * @extends AbstractBuilder<User>
 */
class UserBuilder extends AbstractBuilder
{
    public function __construct()
    {
        $user = new User();
        $user->setIsEnabled(true);
        $user->setMedicalValidation(true);
        $user->setName("Florian Moser");
        $user->setShortname("shorty");
        $user->setAbbreviation("FM");

        parent::__construct($user);
    }
}
