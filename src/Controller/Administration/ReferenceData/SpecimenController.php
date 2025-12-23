<?php

namespace App\Controller\Administration\ReferenceData;

use App\Entity\Specimen;
use App\Enum\CodeSystem;
use App\Form\SpecimenType;
use App\Helper\DoctrineHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/reference_data')]
class SpecimenController extends AbstractController
{
    #[Route('/specimen', name: 'reference_data_specimen')]
    public function specimens(ManagerRegistry $registry): Response
    {
        $specimens = $registry->getRepository(Specimen::class)->findAll();

        return $this->render('reference_data/specimen/index.html.twig', ['specimens' => $specimens]);
    }

    #[Route('/specimen/new', name: 'reference_data_specimen_new')]
    public function specimenNew(Request $request, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $specimen = new Specimen();
        $specimen->setSystem(CodeSystem::SNOMED);

        $label = $translator->trans('form.buttons.create');
        $form = $this->createForm(SpecimenType::class, $specimen)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $specimen);

            $message = $translator->trans('specimen_new.success.created', [], 'reference_data');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('reference_data_specimen');
        }

        return $this->render('reference_data/specimen/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/specimen/{specimen}/edit', name: 'reference_data_specimen_edit')]
    public function specimenEdit(Request $request, Specimen $specimen, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $label = $translator->trans('form.buttons.store');
        $form = $this->createForm(SpecimenType::class, $specimen)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $specimen);

            $message = $translator->trans('specimen_edit.success.stored', [], 'reference_data');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('reference_data_specimen');
        }

        return $this->render('reference_data/specimen/edit.html.twig', ['form' => $form->createView()]);
    }
}
