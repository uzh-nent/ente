<?php

namespace App\Services;

use App\Services\Interfaces\FileServiceInterface;

class FileService implements FileServiceInterface
{
    private string $persistentFilesDir;

    public function __construct(string $rootDir)
    {
        $this->persistentFilesDir = $rootDir . '/var/persistent';
    }

    public function saveFile(string $filename, string $content): string
    {
        // Sanitize filename: remove path separators and replace non-alphanumeric/dot/dash with underscores
        $safeFilename = preg_replace('/[^a-zA-Z0-9_.-]/', '_', basename($filename));

        $filePath = $this->persistentFilesDir . '/' . $safeFilename;

        // Ensure file does not overwrite an existing file
        if (file_exists($filePath)) {
            $pathInfo = pathinfo($safeFilename);
            $baseName = $pathInfo['filename'];
            $extension = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';
            $counter = 1;

            while (file_exists($this->persistentFilesDir . '/' . $baseName . '_' . $counter . $extension)) {
                $counter++;
            }

            $safeFilename = $baseName . '_' . $counter . $extension;
            $filePath = $this->persistentFilesDir . '/' . $safeFilename;
        }

        file_put_contents($filePath, $content);

        return $safeFilename;
    }
}
