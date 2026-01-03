<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EmptyForm;
use App\Helper\DoctrineHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/logout', name: 'logout')]
    public function logout(): never
    {
        throw new \LogicException('This code should never be reached');
    }

    #[Route('/login', name: 'login')]
    public function login(FormFactoryInterface $factory, AuthenticationUtils $authenticationUtils, TranslatorInterface $translator, ManagerRegistry $registry, UserPasswordHasherInterface $hasher): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $factory->createBuilder(EmptyForm::class)
            ->add('shortname', TextType::class, ['label' => 'login.form.shortname', 'translation_domain' => 'security', 'data' => $lastUsername])
            ->add('password', PasswordType::class, ['label' => 'login.form.password', 'translation_domain' => 'security'])
            ->add('submit', SubmitType::class, ['label' => 'login.form.submit', 'translation_domain' => 'security'])
            ->getForm();

        $lastError = $authenticationUtils->getLastAuthenticationError();
        if ($lastError instanceof BadCredentialsException) {
            // if super shortname, allow to register
            if (
                $lastUsername === User::SUPER_SHORTNAME &&
                !$registry->getRepository(User::class)->findOneBy(['shortname' => $lastUsername])
            ) {
                $user = new User();
                $user->setShortname($lastUsername);
                $user->setName($lastUsername);
                $user->setAbbreviation(mb_strtoupper(mb_substr($lastUsername, 0, 3)));
                $user->setPassword($hasher->hashPassword($user, $lastUsername));
                DoctrineHelper::persistAndFlush($registry, $user);

                $message = $translator->trans('login.setup.add_initial_user', [], 'security');
                $this->addFlash('info', $message);
            } else {
                $message = $translator->trans('login.error.bad_credentials', [], 'security');
                $this->addFlash('danger', $message);
            }
        } elseif ($lastError) {
            $message = $translator->trans('login.error.login_failed', [], 'security');
            $this->addFlash('danger', $message);
        }

        return $this->render('security/login.html.twig', ['form' => $form->createView()]);
    }
}
