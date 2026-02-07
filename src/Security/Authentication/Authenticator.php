<?php

declare(strict_types=1);

namespace App\Security\Authentication;

use App\Helper\DoctrineHelper;
use App\Security\Authentication\LDAP\LdapServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use App\Entity\User;
use App\Security\Authentication\OAuth2\AzureProvider;
use App\Security\Authentication\OAuth2\AzureResourceOwner;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Webmozart\Assert\Assert;

use function rawurlencode;

class Authenticator extends AbstractAuthenticator
{
    private const SESSION_KEY_OAUTH_STATE = 'oauth.state';

    private const SESSION_KEY_REDIRECT_AFTER_LOGIN = 'redirect_after_login';

    public function __construct(
        private readonly AzureProvider $azureProvider,
        private readonly LdapServiceInterface $LDAPService,
        #[Autowire(env: 'LDAP_USER_WHITELIST_GROUP')]
        private readonly string $ldapUserWhitelistGroup,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly ManagerRegistry $managerRegistry,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === AzureProvider::LOGIN_CALLBACK_ROUTE;
    }

    public function authenticate(Request $request): Passport
    {
        if (!$request->query->has('code') || !$request->query->has('state')) {
            throw new AuthenticationException('Invalid request, please retry.');
        }

        if ($request->query->get('state') !== $request->getSession()->get(self::SESSION_KEY_OAUTH_STATE)) {
            $request->getSession()->remove(self::SESSION_KEY_OAUTH_STATE);
            throw new AuthenticationException('Stale request, please retry.');
        }

        if ($request->query->has('error')) {
            $azureError = $request->query->get('error') . ': ' . $request->query->get('error_description');
            throw new AuthenticationException($azureError);
        }

        /** @var AccessToken $accessToken */
        $accessToken = $this->azureProvider->getAccessToken('authorization_code', [
            'code' => $request->query->get('code'),
        ]);

        if ($accessToken->hasExpired()) {
            throw new AuthenticationException('Access token expired, please retry.');
        }

        /** @var AzureResourceOwner $owner */
        $owner = $this->azureProvider->getResourceOwner($accessToken);

        $shortname = $owner->getShortName();
        $memberOf = $this->LDAPService->loadMemberOf($shortname);
        if (!$memberOf || !in_array($this->ldapUserWhitelistGroup, $memberOf, true)) {
            throw new AuthenticationException('User is not in the LDAP group ' . $this->ldapUserWhitelistGroup);
        }

        // register the super shortname on demand
        if ($shortname === User::SUPER_SHORTNAME) {
            $existingUser = $this->managerRegistry->getRepository(User::class)->findOneBy(['shortname' => $shortname]);
            if (!$existingUser) {
                $user = new User();
                $user->setShortname($shortname);
                $user->setName($owner->getFirstName() . ' ' . $owner->getLastName());
                $abbreviation = mb_substr($owner->getFirstName(), 0, 1) . mb_substr($owner->getLastName(), 0, 1);
                $user->setAbbreviation($abbreviation);
                DoctrineHelper::persistAndFlush($this->managerRegistry, $user);
            }
        }

        $userBadge = new UserBadge($shortname);
        return new SelfValidatingPassport($userBadge);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $redirect = $request->getSession()->get(self::SESSION_KEY_REDIRECT_AFTER_LOGIN);
        if (!$redirect) {
            $redirect = $this->urlGenerator->generate('index');
        }

        return new RedirectResponse($redirect);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        $root = $this->urlGenerator->generate('login');

        return new RedirectResponse($root);
    }

    public function redirectToAuthentication(
        Request $request,
        ?string $redirectAfterSuccessfulLogin = null,
    ): RedirectResponse {
        $authorizationUrl = $this->azureProvider->getAuthorizationUrl();
        $request->getSession()->set(self::SESSION_KEY_OAUTH_STATE, $this->azureProvider->getState());
        $request->getSession()->set(self::SESSION_KEY_REDIRECT_AFTER_LOGIN, $redirectAfterSuccessfulLogin);

        return new RedirectResponse($authorizationUrl);
    }

    public function redirectToEndSession(): RedirectResponse
    {
        $endSessionUrl = $this->azureProvider->getBaseEndSessionUrl();
        $redirectUri = $this->urlGenerator->generate('login', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $endSessionUrl .= '?post_logout_redirect_uri=' . rawurlencode($redirectUri);

        return new RedirectResponse($endSessionUrl);
    }
}
