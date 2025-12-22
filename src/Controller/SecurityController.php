<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EmptyForm;
use App\Helper\DoctrineHelper;
use App\Security\Exceptions\AccountDisabledException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkNotification;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/logout', name: 'logout')]
    public function logout(): never
    {
        throw new \LogicException('This code should never be reached');
    }

    #[Route('/login', name: 'login')]
    public function login(Request $request, FormFactoryInterface $factory, AuthenticationUtils $authenticationUtils, TranslatorInterface $translator, ManagerRegistry $registry, UserPasswordHasherInterface $hasher): Response
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
                $lastUsername === User::SUPER_SHORNAME &&
                !$registry->getRepository(User::class)->findOneBy(['shortname' => $lastUsername])
            ) {
                $user = new User();
                $user->setShortname($lastUsername);
                $user->setPassword($hasher->hashPassword($user, $lastUsername));
                DoctrineHelper::persistAndFlush($registry, $user);

                $message = $translator->trans('login.setup.add_initial_user', [], 'security');
                $this->addFlash('info', $message);
            } else {
                $message = $translator->trans('login.error.bad_credentials', [], 'security');
                $this->addFlash('danger', $message);
            }
        } elseif ($lastError instanceof AccountDisabledException) {
            $message = $translator->trans('login.error.account_disabled', [], 'security');
            $this->addFlash('danger', $message);
        } elseif ($lastError) {
            $message = $translator->trans('login.error.login_failed', [], 'security');
            $this->addFlash('danger', $message);
        }

        return $this->render('security/login.html.twig', ['form' => $form->createView()]);
    }

    private function setPasswordAction(Request $request, ManagerRegistry $registry, TranslatorInterface $translator, UserPasswordHasherInterface $hasher, Security $security, ?FormInterface &$form = null): ?Response
    {
        $token = $request->query->get('token', 'invalid');
        $user = $registry->getRepository(User::class)->findOneBy(['authenticationToken' => $token]);
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createFormBuilder()
            ->add('password', PasswordType::class, ['label' => 'set_password.form.password', 'translation_domain' => 'security'])
            ->add('passwordConfirm', PasswordType::class, ['label' => 'set_password.form.password_confirm', 'translation_domain' => 'security'])
            ->add('submit', SubmitType::class, ['label' => 'set_password.form.submit', 'translation_domain' => 'security'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()['password'] !== $form->getData()['passwordConfirm']) {
                $message = $translator->trans('set_password.error.password_mismatch', [], 'security');
                $this->addFlash('danger', $message);
            } else {
                $hashedPassword = $hasher->hashPassword($user, $form->getData()['password']);
                $user->setPassword($hashedPassword);
                DoctrineHelper::persistAndFlush($registry, $user);

                $message = $translator->trans('set_password.success.password_set', [], 'security');
                $this->addFlash('success', $message);

                return $security->login($user);
            }
        }

        return null;
    }
}
