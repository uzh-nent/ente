<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class EmptyForm extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return '';
    }
}
