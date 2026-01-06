<?php

namespace App\Services\Interfaces;

interface FileServiceInterface
{
    public const string REPORT_FOLDER = 'reports';
    public function saveFile(string $folder, string $filename, string $content): string;
    public function getFolderPath(string $folder): string;
}
