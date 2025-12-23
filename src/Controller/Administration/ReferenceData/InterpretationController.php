<?php

namespace App\Controller\Administration\ReferenceData;

use App\Entity\Interpretation;
use App\Form\InterpretationType;
use App\Helper\DoctrineHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/reference_data')]
class InterpretationController extends AbstractController
{
    #[Route('/interpretations', name: 'reference_data_interpretations')]
    public function interpretations(ManagerRegistry $registry): Response
    {
        $interpretations = $registry->getRepository(Interpretation::class)->findAll();

        return $this->render('reference_data/interpretation/index.html.twig', ['interpretations' => $interpretations]);
    }

    #[Route('/interpretation/new', name: 'reference_data_interpretation_new')]
    public function interpretationNew(Request $request, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $interpretation = new Interpretation();

        $label = $translator->trans('form.buttons.create');
        $form = $this->createForm(InterpretationType::class, $interpretation)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $interpretation);

            $message = $translator->trans('interpretation_new.success.created', [], 'reference_data');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('reference_data_interpretations');
        }

        return $this->render('reference_data/interpretation/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/interpretation/{interpretation}/edit', name: 'reference_data_interpretation_edit')]
    public function interpretationEdit(Request $request, Interpretation $interpretation, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $label = $translator->trans('form.buttons.store');
        $form = $this->createForm(InterpretationType::class, $interpretation)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $interpretation);

            $message = $translator->trans('interpretation_edit.success.stored', [], 'reference_data');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('reference_data_interpretations');
        }

        return $this->render('reference_data/interpretation/edit.html.twig', ['form' => $form->createView()]);
    }
}
