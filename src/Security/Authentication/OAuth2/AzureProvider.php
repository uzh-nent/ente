<?php

declare(strict_types=1);

namespace App\Security\Authentication\OAuth2;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

class AzureProvider extends AbstractProvider
{
    public const string LOGIN_CALLBACK_ROUTE = 'login_callback';

    /**
     * @var string[]|null
     */
    private ?array $openIdConfiguration = null;

    protected string $tenantId = '';

    public function __construct(
        UrlGeneratorInterface $generator,
        #[Autowire(env: 'AZURE_AD_TENANT_ID')]
        string $azureADTenantId,
        #[Autowire(env: 'AZURE_AD_CLIENT_ID')]
        string $azureADClientId,
        #[Autowire(env: 'AZURE_AD_CLIENT_SECRET')]
        string $azureADClientSecret,
    ) {
        $redirectUri = $generator->generate(self::LOGIN_CALLBACK_ROUTE, [], UrlGeneratorInterface::ABSOLUTE_URL);

        $options = [
            'tenantId' => $azureADTenantId,
            'clientId' => $azureADClientId,
            'clientSecret' => $azureADClientSecret,
            'redirectUri' => $redirectUri,
        ];
        parent::__construct($options);
    }

    /**
     * @return string[]
     * @throws IdentityProviderException
     */
    protected function getOpenIdConfiguration(): array
    {
        if (!$this->openIdConfiguration) {
            $baseUrl = 'https://login.microsoftonline.com/';
            $path = '/v2.0/.well-known/openid-configuration';
            $openIdConfigurationUri = $baseUrl . $this->tenantId . $path;

            $factory = $this->getRequestFactory();
            $request = $factory->getRequest('get', $openIdConfigurationUri);
            /** @var string[] $response */
            $response = $this->getParsedResponse($request);

            $this->openIdConfiguration = $response;
        }

        return $this->openIdConfiguration;
    }

    public function getBaseAuthorizationUrl(): string
    {
        $openIdConfiguration = $this->getOpenIdConfiguration();
        return $openIdConfiguration['authorization_endpoint'];
    }

    /**
     * @param mixed[] $params
     * @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        $openIdConfiguration = $this->getOpenIdConfiguration();
        return $openIdConfiguration['token_endpoint'];
    }

    public function getBaseEndSessionUrl(): string
    {
        $openIdConfiguration = $this->getOpenIdConfiguration();
        return $openIdConfiguration['end_session_endpoint'];
    }

    private function getJWKSUrl(): string
    {
        $openIdConfiguration = $this->getOpenIdConfiguration();
        return $openIdConfiguration['jwks_uri'];
    }

    /**
     * @return Key[]
     */
    private function getJWTKeys(): array
    {
        $keysUri = $this->getJWKSUrl();

        $factory = $this->getRequestFactory();
        $request = $factory->getRequest('get', $keysUri);

        $response = $this->getParsedResponse($request);

        $keys = [];
        foreach ($response['keys'] as $key) {
            foreach ($key['x5c'] as $x5cKey) {
                $certificateString =
                    '-----BEGIN CERTIFICATE-----' . PHP_EOL
                    . chunk_split((string) $x5cKey, 64, PHP_EOL)
                    . '-----END CERTIFICATE-----' . PHP_EOL;

                $certificate = openssl_x509_read($certificateString);
                if ($certificate === false) {
                    throw new RuntimeException('Cannot read ' . $x5cKey . ' as a certificate.');
                }

                $publicKey = openssl_pkey_get_public($certificate);
                if ($publicKey === false) {
                    throw new RuntimeException('Cannot read ' . $x5cKey . ' as a public key.');
                }

                $publicKeyDetails = openssl_pkey_get_details($publicKey);
                if ($publicKeyDetails === false) {
                    throw new RuntimeException('Cannot extract public key details out of ' . $x5cKey . '.');
                }

                $jwtKey = new Key($publicKeyDetails ['key'], 'RS256');
                $keys[$key['kid']] = $jwtKey;
            }
        }

        return $keys;
    }

    /**
     * @return string[]
     */
    protected function getDefaultScopes(): array
    {
        return ['openid', 'email', 'profile'];
    }

    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    /**
     * @phpstan-param array<mixed>|string $data
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (isset($data['error'])) {
            $message = $data['error'];

            if (isset($data['error_description'])) {
                $message .= PHP_EOL . $data['error_description'];
            }

            throw new IdentityProviderException(
                $message,
                $response->getStatusCode(),
                $data,
            );
        }
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        $openIdConfiguration = $this->getOpenIdConfiguration();
        return 'https://' . $openIdConfiguration['msgraph_host'] . '/v1.0/me';
    }

    /**
     * @return array<string, string|int>
     */
    protected function fetchResourceOwnerDetails(AccessToken $token): array
    {
        $values = $token->getValues();
        Assert::keyExists($values, 'id_token');
        $idToken = $values['id_token'];

        $keys = $this->getJWTKeys();
        return (array) JWT::decode($idToken, $keys);
    }

    /**
     * @param array<mixed> $response
     */
    protected function createResourceOwner(array $response, AccessToken $token): AzureResourceOwner
    {
        return new AzureResourceOwner($response);
    }
}
