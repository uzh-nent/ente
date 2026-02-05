<?php

declare(strict_types=1);

namespace App\Security\Authentication;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

#[AsEventListener(LogoutEvent::class)]
final readonly class LogoutEventListener
{
    public function __construct(private Authenticator $azureAdAuthenticator)
    {
    }

    public function __invoke(LogoutEvent $event): void
    {
        $token = $event->getToken();
        if (!$token instanceof TokenInterface) {
            return;
        }

        $redirectResponse = $this->azureAdAuthenticator->redirectToEndSession();
        $event->setResponse($redirectResponse);
    }
}
