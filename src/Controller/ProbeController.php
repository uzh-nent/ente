<?php

namespace App\Controller;

use App\Entity\Probe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\returnArgument;

class ProbeController extends AbstractController
{
    #[Route('/probe/new', name: 'probe_new')]
    public function new(): Response
    {
        return $this->render('probe/new.html.twig');
    }

    #[Route('/probe/new.js', name: 'probe_new_js')]
    public function newJs(): Response
    {
        $response = $this->render('probe/new.js.twig');

        return $this->sendJavascript($response);
    }

    #[Route('/probes/active', name: 'probe_active')]
    public function active(): Response
    {
        return $this->render('probe/active_view.html.twig');
    }

    #[Route('/probes/active/{probe}/view', name: 'probe_active_view')]
    public function activeView(Probe $probe): Response
    {
        return $this->render('probe/active_view.html.twig', ['probe' => $probe]);
    }

    #[Route('/probes/active/{probe}/view', name: 'probe_active_view_js')]
    public function activeJsView(Probe $probe): Response
    {
        $response = $this->render('probe/active_view.js.twig', ['probe' => $probe]);

        return $this->sendJavascript($response);
    }

    #[Route('/probes/all', name: 'probe_all')]
    public function all(): Response
    {
        return $this->render('index.html.twig');
    }

    private function sendJavascript(Response $response): Response
    {
        $response->headers->set('Content-Type', 'text/javascript');
        return $response;
    }
}
