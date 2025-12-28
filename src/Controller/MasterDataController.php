<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/master_data')]
class MasterDataController extends AbstractController
{
    #[Route('/organizations', name: 'master_data_organizations')]
    public function organizations(): Response
    {
        return $this->render('master_data/organizations.html.twig');
    }

    #[Route('/practitioners', name: 'master_data_practitioners')]
    public function practitioners(): Response
    {
        return $this->render('master_data/practitioners.html.twig');
    }

    #[Route('/patients', name: 'master_data_patients')]
    public function patients(): Response
    {
        return $this->render('master_data/patients.html.twig');
    }

    #[Route('/animal_keepers', name: 'master_data_animal_keepers')]
    public function animalKeepers(): Response
    {
        return $this->render('master_data/animal_keepers.html.twig');
    }
}
