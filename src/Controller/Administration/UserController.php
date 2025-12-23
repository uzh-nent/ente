<?php

namespace App\Controller\Administration;

use App\Entity\User;
use App\Form\UserType;
use App\Helper\DoctrineHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/users')]
class UserController extends AbstractController
{
    #[Route('/users', name: 'configuration_users')]
    public function users(ManagerRegistry $registry): Response
    {
        $users = $registry->getRepository(User::class)->findAll();

        return $this->render('configuration/user/index.html.twig', ['users' => $users]);
    }

    #[Route('/user/new', name: 'configuration_user_new')]
    public function userNew(Request $request, TranslatorInterface $translator, ManagerRegistry $registry, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();

        $label = $translator->trans('form.buttons.create');
        $form = $this->createForm(UserType::class, $user)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->trySetPassword($form, $user, $hasher);
            DoctrineHelper::persistAndFlush($registry, $user);

            $message = $translator->trans('user_new.success.created', [], 'configuration');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('configuration_users');
        }

        return $this->render('configuration/user/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/user/{user}/edit', name: 'configuration_user_edit')]
    public function userEdit(Request $request, User $user, TranslatorInterface $translator, ManagerRegistry $registry, UserPasswordHasherInterface $hasher): Response
    {
        $label = $translator->trans('form.buttons.store');
        $form = $this->createForm(UserType::class, $user)
            ->add('submit', SubmitType::class, ['label' => $label, 'translation_domain' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->trySetPassword($form, $user, $hasher);
            DoctrineHelper::persistAndFlush($registry, $user);

            $message = $translator->trans('user_edit.success.stored', [], 'configuration');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('configuration_users');
        }

        return $this->render('configuration/user/edit.html.twig', ['form' => $form->createView()]);
    }

    private function trySetPassword(FormInterface $form, User $user, UserPasswordHasherInterface $hasher): void
    {
        $plainPassword = $form->get(UserType::PLAIN_PASSWORD)->getData();
        if ($plainPassword) {
            $hashedPassword = $hasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }
    }
}
