<?php

namespace App\Controller;

use App\Entity\Interpretation;
use App\Entity\LeadingCode;
use App\Entity\Organism;
use App\Entity\Specimen;
use App\Form\LeadingCodeType;
use App\Helper\DoctrineHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/reference_data')]
class ReferenceDataController extends AbstractController
{
    #[Route('', name: 'reference_data')]
    public function index(ManagerRegistry $registry): Response
    {
        $leadingCodes = $registry->getRepository(LeadingCode::class)->findAll();
        $organisms = $registry->getRepository(Organism::class)->findAll();
        $specimens = $registry->getRepository(Specimen::class)->findAll();
        $interpretation = $registry->getRepository(Interpretation::class)->findAll();

        return $this->render('reference_data/index.html.twig', ['leadingCodes' => $leadingCodes, 'organisms' => $organisms, 'specimens' => $specimens, 'interpretation' => $interpretation]);
    }

    #[Route('/leading_code/new', name: 'reference_data_leading_code_new')]
    public function leadingCodeNew(Request $request, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $leadingCode = new LeadingCode();

        $label = $translator->trans('form.buttons.create');
        $form = $this->createForm(LeadingCodeType::class, $leadingCode)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $leadingCode);

            $message = $translator->trans('leading_code_new.success.created', [], 'reference_data');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('reference_data');
        }

        return $this->render('reference_data/leading_code/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/leading_code/{leadingCode}/edit', name: 'reference_data_leading_code_edit')]
    public function leadingCodeEdit(Request $request, LeadingCode $leadingCode, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $label = $translator->trans('form.buttons.store');
        $form = $this->createForm(LeadingCodeType::class, $leadingCode)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $leadingCode);

            $message = $translator->trans('leading_code_edit.success.stored', [], 'reference_data');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('reference_data');
        }

        return $this->render('reference_data/leading_code/edit.html.twig', ['form' => $form->createView()]);
    }
}
