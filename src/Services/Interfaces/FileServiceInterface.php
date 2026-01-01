<?php

namespace App\Services\Interfaces;

use App\Entity\Probe;
use App\Entity\Report;

interface FileServiceInterface
{
    public function saveFile(string $filename, string $content): string;
}
