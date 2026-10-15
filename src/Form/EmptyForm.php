<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

/**
 * @template T
 * @extends AbstractType<T>
 */
class EmptyForm extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return '';
    }
}
