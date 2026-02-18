<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EmptyForm;
use App\Helper\DoctrineHelper;
use App\Security\Authentication\AuthenticationEntryPoint;
use App\Security\Authentication\Authenticator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function login(Request $request, FormFactoryInterface $factory, AuthenticationUtils $authenticationUtils, TranslatorInterface $translator, AuthenticationEntryPoint $authenticationEntryPoint): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $form = $factory->createBuilder(EmptyForm::class)
            ->add('submit', SubmitType::class, ['label' => 'login.form.submit', 'translation_domain' => 'security'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            return $authenticationEntryPoint->start($request);
        }

        $lastError = $authenticationUtils->getLastAuthenticationError();
        if ($lastError) {
            $message = $translator->trans('login.error.login_failed', ['%error%' => $lastError->getMessage()], 'security');
            $this->addFlash('danger', $message);
        }

        return $this->render('security/login.html.twig', ['form' => $form->createView(), 'lastError' => $lastError]);
    }

    #[Route('/login/end_session', name: 'login_end_session')]
    public function endSession(Authenticator $authenticator): Response
    {
        return $authenticator->redirectToEndSession();
    }
}
