<?php

namespace App\Controller;

use App\Entity\Probe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProbeControllerController extends AbstractController
{
    #[Route('/', name: 'probe_active')]
    public function active(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/probe/all', name: 'probe_all')]
    public function all(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/probe/new', name: 'probe_new')]
    public function new(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/probe/{collection}/view', name: 'probe_view')]
    public function view(Probe $probe): Response
    {
        return $this->render('index.html.twig');
    }
}
