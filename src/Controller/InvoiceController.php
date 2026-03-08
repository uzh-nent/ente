<?php

namespace App\Controller;

use App\Entity\Probe;
use App\Enum\LaboratoryFunction;
use App\Services\Interfaces\FileServiceInterface;
use App\Services\Interfaces\PdfServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InvoiceController extends AbstractController
{
    #[Route('/invoice/new', name: 'invoice_new')]
    public function new(): Response
    {
        return $this->render('invoice/new.html.twig');
    }

    #[Route('/invoices', name: 'invoices')]
    public function all(): Response
    {
        return $this->render('invoice/all.html.twig');
    }
}
