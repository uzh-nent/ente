<?php

namespace App\Services\Interfaces;

interface FileServiceInterface
{
    public function saveFile(string $filename, string $content): string;
}
