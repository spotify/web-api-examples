# Authorization Using the Proof Key for Code Exchange (PKCE) Flow

All API methods require authorization. Before using these methods you'll need to create an app at [Spotify's developer site](https://developer.spotify.com/documentation/web-api/).

The Proof Key for Code Exchange Flow is very similar to the [Authorization Code flow](access-token-with-authorization-code-flow.md), but instead of using a client secret which might not always be viable it uses a code challenge flow.

Just like the Authorization Code flow, this method requires some interaction from the user but in turn allows access to user information. There are two steps required to authenticate the user. The first step is to request access to the user's account and data (known as *scopes*) and redirecting them to your app's authorize URL (also known as the callback URL).

### Step 1
Put the following code in its own file, lets call it `auth.php`. Replace `CLIENT_ID` with the value given to you by Spotify. The `REDIRECT_URI` is the one you entered when creating the Spotify app, make sure it's an exact match. You'll also need to create a *code verifier* and store it somewhere between requests. It will be used again in the second step.

```php
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    'CLIENT_ID',
    '', // Normally the client secret, but this value can be omitted when using the PKCE flow
    'REDIRECT_URI'
);

$verifier = $session->generateCodeVerifier(); // Store this value somewhere, a session for example
$challenge = $session->generateCodeChallenge($verifier);
$state = $session->generateState();

$options = [
    'code_challenge' => $challenge,
    'scope' => [
        'playlist-read-private',
        'user-read-private',
    ],
    'state' => $state,
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();
```

__Note:__ The `state` parameter is optional but highly recommended to prevent CSRF attacks. The value will need to be stored between requests and verfied when the user is redirected back to your application from Spotify.

To read more about scopes, see [Working with Scopes](/docs/examples/working-with-scopes.md). To see all of the available options for `getAuthorizeUrl()`, refer to the [method reference](/docs/method-reference/Session.md#getauthorizeurl).

### Step 2
When the user has approved your app, Spotify will redirect the user together with a `code` to the specifed redirect URI. You'll need to use this code to request a access token from Spotify. The *code verifier* created in the previous step will also be needed.

__Note:__ The API wrapper does not include any token management. It's up to you to save the access token somewhere (in a database, a PHP session, or wherever appropriate for your application) and request a new access token when the old one has expired.

Lets put this code in a new file called `callback.php`:

```php
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    'CLIENT_ID',
    'CLIENT_SECRET',
    'REDIRECT_URI'
);

$state = $_GET['state'];

// Fetch the stored state value from somewhere. A session for example

if ($state !== $storedState) {
    // The state returned isn't the same as the one we've stored, we shouldn't continue
    die('State mismatch');
}

// Request a access token using the code from Spotify and the previously created code verifier
$session->requestAccessToken($_GET['code'], $verifier);

$accessToken = $session->getAccessToken();
$refreshToken = $session->getRefreshToken();

// Store the access and refresh tokens somewhere. In a session for example

// Send the user along and fetch some data!
header('Location: app.php');
die();
```

When requesting a access token, a **refresh token** will also be included. This can be used to extend the validity of access tokens. It's recommended to also store this somewhere persistent, in a database for example. [Read more about refresh tokens here](refreshing-access-tokens.md).

### Step 3
In a third file, `app.php`, tell the API wrapper which access token to use, and then make some API calls!

```php
require 'vendor/autoload.php';

$api = new SpotifyWebAPI\SpotifyWebAPI();

// Fetch the saved access token from somewhere. A session for example.
$api->setAccessToken($accessToken);

// It's now possible to request data about the currently authenticated user
print_r(
    $api->me()
);

// Getting Spotify catalog data is of course also possible
print_r(
    $api->getTrack('7EjyzZcbLxW7PaaLua9Ksb')
);
```

For more in-depth technical information about the Proof Key for Code Exchange flow, please refer to the [Spotify Web API documentation](https://developer.spotify.com/documentation/general/guides/authorization/code-flow/).
