# Refreshing Access Tokens
When requesting access tokens using the [Authorization Code](access-token-with-authorization-code-flow.md) or [Proof Key for Code Exchange (PKCE)](access-token-with-pkce-flow.md) flows, a _refresh token_ will also be included. This token can be used to request a new access token when the previous one has expired, but without any user interaction.

```php
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    'CLIENT_ID',
    'CLIENT_SECRET',
    'REDIRECT_URI'
);

// Fetch the refresh token from somewhere. A database for example.

$session->refreshAccessToken($refreshToken);

$accessToken = $session->getAccessToken();
$refreshToken = $session->getRefreshToken();

// Set our new access token on the API wrapper and continue to use the API as usual
$api->setAccessToken($accessToken);

// Store the new refresh token somewhere for later use
```

## Automatically Refreshing Access Tokens
_Note: This feature is available as of version `2.11.0`._

Start off by requesting an access token as usual. But instead of setting the access token on a `SpotifyWebAPI` instance, pass the complete `Session` instance when initializing a new `SpotifyWebAPI` instance or by using the `setSession()` method. Remember to also set the `auto_refresh` option to `true`. For example:

```php
$session = new SpotifyWebAPI\Session(
    'CLIENT_ID',
    'CLIENT_SECRET',
    'REDIRECT_URI'
);

$options = [
    'auto_refresh' => true,
];

$api = new SpotifyWebAPI\SpotifyWebAPI($options, $session);

// You can also call setSession on an existing SpotifyWebAPI instance
$api->setSession($session);

// Call the API as usual
$api->me();

// Remember to grab the tokens afterwards, they might have been updated
$newAccessToken = $session->getAccessToken();
$newRefreshToken = $session->getRefreshToken();
```

### With an existing refresh token

When you already have existing access and refresh tokens, add them to the `Session` instance and call the API.

```php
$session = new SpotifyWebAPI\Session(
    'CLIENT_ID',
    'CLIENT_SECRET'
);

// Use previously requested tokens fetched from somewhere. A database for example.
if ($accessToken) {
    $session->setAccessToken($accessToken);
    $session->setRefreshToken($refreshToken);
} else {
    // Or request a new access token
    $session->refreshAccessToken($refreshToken);
}

$options = [
    'auto_refresh' => true,
];

$api = new SpotifyWebAPI\SpotifyWebAPI($options, $session);

// You can also call setSession on an existing SpotifyWebAPI instance
$api->setSession($session);

// Call the API as usual
$api->me();

// Remember to grab the tokens afterwards, they might have been updated
$newAccessToken = $session->getAccessToken();
$newRefreshToken = $session->getRefreshToken();
```
