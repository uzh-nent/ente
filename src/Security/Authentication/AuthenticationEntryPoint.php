<?php

declare(strict_types=1);

namespace App\Security\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

final readonly class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(private Authenticator $azureADAuthenticator)
    {
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return $this->azureADAuthenticator->redirectToAuthentication($request, $request->getRequestUri());
    }
}
