<?php

namespace App\Controller\Administration\Configuration;

use App\Entity\InterpretationText;
use App\Form\InterpretationTextType;
use App\Helper\DoctrineHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/configuration')]
class InterpretationTextController extends AbstractController
{
    #[Route('/interpretation_texts', name: 'configuration_interpretation_texts')]
    public function interpretationTexts(ManagerRegistry $registry): Response
    {
        $interpretationTexts = $registry->getRepository(InterpretationText::class)->findAll();

        return $this->render('configuration/interpretation_text/index.html.twig', ['interpretationTexts' => $interpretationTexts]);
    }

    #[Route('/interpretation_text/new', name: 'configuration_interpretation_text_new')]
    public function interpretationTextNew(Request $request, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $interpretationText = new InterpretationText();

        $label = $translator->trans('form.buttons.create');
        $form = $this->createForm(InterpretationTextType::class, $interpretationText)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $interpretationText);

            $message = $translator->trans('interpretation_text_new.success.created', [], 'configuration');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('configuration_interpretation_texts');
        }

        return $this->render('configuration/interpretation_text/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/interpretation_text/{interpretationText}/edit', name: 'configuration_interpretation_text_edit')]
    public function interpretationTextEdit(Request $request, InterpretationText $interpretationText, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $label = $translator->trans('form.buttons.store');
        $form = $this->createForm(InterpretationTextType::class, $interpretationText)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $interpretationText);

            $message = $translator->trans('interpretation_text_edit.success.stored', [], 'configuration');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('configuration_interpretation_texts');
        }

        return $this->render('configuration/interpretation_text/edit.html.twig', ['form' => $form->createView()]);
    }
}
