<?php

namespace App\Controller;

use App\Entity\Probe;
use App\Entity\Report;
use App\Services\Interfaces\FileServiceInterface;
use App\Services\Interfaces\PdfServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReportController extends AbstractController
{
    #[Route('/reports/{report}/download/{filename}', name: 'report_download')]
    public function reportPdf(Report $report, FileServiceInterface $fileService): Response
    {
        $folder = $fileService->getFolderPath(FileServiceInterface::REPORT_FOLDER);
        $filepath = $folder . DIRECTORY_SEPARATOR . $report->getFilename();
        if (!is_file($filepath)) {
            throw $this->createNotFoundException();
        }

        return new BinaryFileResponse($filepath, Response::HTTP_OK, ['Content-Type' => 'application/pdf']);
    }
}
