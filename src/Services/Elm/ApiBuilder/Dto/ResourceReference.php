<?php

namespace App\Services\Elm\ApiBuilder\Dto;

readonly class ResourceReference
{
    /** internal url base; used as a prefix for internal links in the XML */
    private const string IUB = "https://www.qant.uzh.ch/NENT";

    public function __construct(private string $type, private string $id)
    {
    }

    public function type(): string
    {
        return $this->type;
    }

    public function id(): string
    {
        return $this->type . '-' . $this->id;
    }

    public function fullUrl(): string
    {
        return self::IUB . "/" . $this->type . "/" . $this->id();
    }
}
