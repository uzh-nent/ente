<?php

namespace App\Services\Interfaces;

use App\Enum\LaboratoryFunction;

interface FileServiceInterface
{
    public const string REPORT_FOLDER = 'reports';
    public function saveFile(string $folder, string $filename, string $content): string;
    public function getFolderPath(string $folder): string;
    public function getProbesExportPre2025(LaboratoryFunction $function): string;
}
