<?php

require __DIR__.'/../vendor/autoload.php';

session_start();

$provider = new \Maniaplanet\OAuth2\Client\Maniaplanet\Provider\Maniaplanet([
    'clientId'                => 'e2af459cf3',    // The client ID assigned to you by the provider
    'clientSecret'            => '2c0e9d2ece04b8e201334ef8da614d05dfc6aa09',   // The client password assigned to you by the provider
    'redirectUri'             => 'http://127.0.0.1/lab/github/oauth2-maniaplanet/example/',
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl(
        [
            'scope' => 'basic maps'
        ]
    );

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
}
else {

    try {

        echo "Code:" . $_GET['code'] . "<br /><br />\n";

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo "Token:" . $accessToken->getToken() . "<br /><br />\n";
        echo "Refresh token:" . $accessToken->getRefreshToken() . "<br /><br />\n";
        echo "Expires: " . $accessToken->getExpires() . "<br /><br />\n";
        echo "Expired ? ".($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br /><br />\n";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_dump($resourceOwner);

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        $request = $provider->getAuthenticatedRequest(
            'GET',
            'https://www.maniaplanet.com/webservices/me/maps',
            $accessToken
        );

        $response = $provider->getHttpClient()->send($request);

        var_dump($response->getBody()->getContents());

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}
