<?php

namespace App\Controller\Administration\Configuration;

use App\Entity\ReportText;
use App\Form\ReportTextType;
use App\Helper\DoctrineHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/configuration')]
class ReportTextController extends AbstractController
{
    #[Route('/report_texts', name: 'configuration_report_texts')]
    public function reportTexts(ManagerRegistry $registry): Response
    {
        $reportTexts = $registry->getRepository(ReportText::class)->findAll();

        return $this->render('configuration/report_text/index.html.twig', ['reportTexts' => $reportTexts]);
    }

    #[Route('/report_text/new', name: 'configuration_report_text_new')]
    public function reportTextNew(Request $request, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $reportText = new ReportText();

        $label = $translator->trans('form.buttons.create');
        $form = $this->createForm(ReportTextType::class, $reportText)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $reportText);

            $message = $translator->trans('report_text_new.success.created', [], 'configuration');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('configuration_report_texts');
        }

        return $this->render('configuration/report_text/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/report_text/{reportText}/edit', name: 'configuration_report_text_edit')]
    public function reportTextEdit(Request $request, ReportText $reportText, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $label = $translator->trans('form.buttons.store');
        $form = $this->createForm(ReportTextType::class, $reportText)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $reportText);

            $message = $translator->trans('report_text_edit.success.stored', [], 'configuration');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('configuration_report_texts');
        }

        return $this->render('configuration/report_text/edit.html.twig', ['form' => $form->createView()]);
    }
}
