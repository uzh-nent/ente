<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->redirectToRoute('probe_active');
    }

    #[Route('/status', name: 'index_status')]
    public function status(MailerInterface $mailer): Response
    {
        $templatedEmail = new TemplatedEmail();

        $templatedEmail
            ->to("git@famoser.ch")
            ->subject("Test E-Mail")
            ->textTemplate('email/report.txt.twig')
            ->addPart(DataPart::fromPath(
                $this->getParameter('kernel.project_dir') . '/assets/resources/report/logo.png',
                'logo.png',
                'image/png'
            ));

        try {
            $mailer->send($templatedEmail);
            $this->addFlash('success', "Mail sent!");
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('probe_active');
    }
}
