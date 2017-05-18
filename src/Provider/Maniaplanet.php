<?php

namespace Maniaplanet\OAuth2\Client\Maniaplanet\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Maniaplanet\OAuth2\Client\Maniaplanet\Entity\ManiaplanetUser;
use Psr\Http\Message\ResponseInterface;

class Maniaplanet extends AbstractProvider
{
    public $apiDomain = 'https://www.maniaplanet.com';

    public function getBaseAuthorizationUrl()
    {
        return $this->apiDomain.'/login/oauth2/authorize';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->apiDomain.'/login/oauth2/access_token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->apiDomain.'/webservices/me';
    }

    protected function getDefaultScopes()
    {
        return ['basic'];
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        // TODO: Implement checkResponse() method.
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new ManiaplanetUser($response);
    }

    protected function getDefaultHeaders()
    {
        return ['Accept' => 'application/json'];
    }

    protected function getAuthorizationHeaders($token = null)
    {
        if ($token instanceof AccessToken) {
            return [
                'Authorization' => 'Bearer '.$token->getToken()
            ];
        }
    }
}
