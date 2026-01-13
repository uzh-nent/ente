<?php

namespace App\Controller\Administration\Configuration;

use App\Entity\StandardText;
use App\Form\StandardTextType;
use App\Helper\DoctrineHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/configuration')]
class StandardTextController extends AbstractController
{
    #[Route('/standard_texts', name: 'configuration_standard_texts')]
    public function standardTexts(ManagerRegistry $registry): Response
    {
        $standardTexts = $registry->getRepository(StandardText::class)->findAll();

        return $this->render('configuration/standard_text/index.html.twig', ['standardTexts' => $standardTexts]);
    }

    #[Route('/standard_text/new', name: 'configuration_standard_text_new')]
    public function standardTextNew(Request $request, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $standardText = new StandardText();

        $label = $translator->trans('form.buttons.create');
        $form = $this->createForm(StandardTextType::class, $standardText)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $standardText);

            $message = $translator->trans('standard_text_new.success.created', [], 'configuration');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('configuration_standard_texts');
        }

        return $this->render('configuration/standard_text/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/standard_text/{standardText}/edit', name: 'configuration_standard_text_edit')]
    public function standardTextEdit(Request $request, StandardText $standardText, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $label = $translator->trans('form.buttons.store');
        $form = $this->createForm(StandardTextType::class, $standardText)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $standardText);

            $message = $translator->trans('standard_text_edit.success.stored', [], 'configuration');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('configuration_standard_texts');
        }

        return $this->render('configuration/standard_text/edit.html.twig', ['form' => $form->createView()]);
    }
}
