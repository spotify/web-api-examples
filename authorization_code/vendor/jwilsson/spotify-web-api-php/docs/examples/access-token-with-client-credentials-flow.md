# Authorization Using the Client Credentials Flow

All API methods require authorization. Before using these methods you'll need to create an app at [Spotify's developer site](https://developer.spotify.com/documentation/web-api/).

This method doesn't require any user interaction and no access to user information is therefore granted. This is the recommended method if you only need access to Spotify catalog data.

## Step 1
The first step is to request a access token. Put the following code in its own file, lets call it `auth.php`. Replace `CLIENT_ID` and `CLIENT_SECRET` with the values given to you by Spotify.

__Note:__ The API wrapper does not include any token management. It's up to you to save the access token somewhere (in a database, a PHP session, or wherever appropriate for your application) and request a new access token when the old one has expired.

```php
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    'CLIENT_ID',
    'CLIENT_SECRET'
);

$session->requestCredentialsToken();
$accessToken = $session->getAccessToken();

// Store the access token somewhere. In a database for example.

// Send the user along and fetch some data!
header('Location: app.php');
die();
```

You'll notice the missing redirect URI when initializing the `Session`. When using the Client Credentials Flow, it isn't needed and can simply be omitted from the constructor call.

## Step 2
In a second file, `app.php`, tell the API wrapper which access token to use, and then make some API calls!

```php
require 'vendor/autoload.php';

// Fetch the saved access token from somewhere. A database for example.

$api = new SpotifyWebAPI\SpotifyWebAPI();
$api->setAccessToken($accessToken);

// It's now possible to request data from the Spotify catalog
print_r(
    $api->getTrack('7EjyzZcbLxW7PaaLua9Ksb')
);
```

For more in-depth technical information about the Client Credentials Flow, please refer to the [Spotify Web API documentation](https://developer.spotify.com/documentation/general/guides/authorization/client-credentials/).
