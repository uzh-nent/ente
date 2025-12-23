<?php

namespace App\Controller\Administration\ReferenceData;

use App\Entity\Organism;
use App\Enum\CodeSystem;
use App\Form\OrganismType;
use App\Helper\DoctrineHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/reference_data')]
class OrganismController extends AbstractController
{
    #[Route('/organisms', name: 'reference_data_organisms')]
    public function organisms(ManagerRegistry $registry): Response
    {
        $organisms = $registry->getRepository(Organism::class)->findAll();

        return $this->render('reference_data/organism/index.html.twig', ['organisms' => $organisms]);
    }

    #[Route('/organism/new', name: 'reference_data_organism_new')]
    public function organismNew(Request $request, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $organism = new Organism();
        $organism->setSystem(CodeSystem::SNOMED);

        $label = $translator->trans('form.buttons.create');
        $form = $this->createForm(OrganismType::class, $organism)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $organism);

            $message = $translator->trans('organism_new.success.created', [], 'reference_data');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('reference_data_organisms');
        }

        return $this->render('reference_data/organism/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/organism/{organism}/edit', name: 'reference_data_organism_edit')]
    public function organismEdit(Request $request, Organism $organism, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $label = $translator->trans('form.buttons.store');
        $form = $this->createForm(OrganismType::class, $organism)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $organism);

            $message = $translator->trans('organism_edit.success.stored', [], 'reference_data');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('reference_data_organisms');
        }

        return $this->render('reference_data/organism/edit.html.twig', ['form' => $form->createView()]);
    }
}
