<?php

namespace App\Tests\Builders;

use App\Entity\ElmReport;
use App\Tests\Helpers\CodeRepository;

/**
 * @template T
 */
abstract class AbstractBuilder
{
    /**
     * @var T
     */
    protected mixed $entity;

    /**
     * @param T $entity
     */
    public function __construct(mixed $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return T
     */
    public function build(): mixed
    {
        return $this->entity;
    }
}
