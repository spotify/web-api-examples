# Handling Errors

Whenever the API returns a error of some sort, a `SpotifyWebAPIException` extending from the native [PHP Exception](http://php.net/manual/en/language.exceptions.php) will be thrown.

The `message` property will be set to the error message returned by the Spotify API and the `code` property will be set to the HTTP status code returned by the Spotify API.

```php
try {
    $track = $api->getTrack('non-existing-track');
} catch (SpotifyWebAPI\SpotifyWebAPIException $e) {
    echo 'Spotify API Error: ' . $e->getCode(); // Will be 404
}
```

When an authentication error occurs, a `SpotifyWebAPIAuthException` will be thrown. This will contain the same properties as above.

## Handling expired access tokens
_As of version `2.11.0` it's possible to automatically refresh expired access tokens. [Read more here](refreshing-access-tokens.md#automatically-refreshing-access-tokens)._

When the access token has expired you'll get an error back. The `SpotifyWebAPIException` class supplies a helper method to easily check if an expired access token is the issue.

```php
try {
    $track = $api->me();
} catch (SpotifyWebAPI\SpotifyWebAPIException $e) {
    if ($e->hasExpiredToken()) {
        // Refresh the access token
    } else {
        // Some other kind of error
    }
}
```

Read more about how to [refresh access tokens](refreshing-access-tokens.md).

## Handling rate limit errors
_As of version `2.12.0` it's possible to automatically retry rate limited requests by setting the `auto_retry` option to `true`._

If your application should hit the Spotify API rate limit, you will get an error back and the number of seconds you need to wait before sending another request.

Here's an example of how to handle this:

```php
try {
    $track = $api->getTrack('7EjyzZcbLxW7PaaLua9Ksb');
} catch (SpotifyWebAPI\SpotifyWebAPIException $e) {
    if ($e->getCode() == 429) { // 429 is Too Many Requests
        $lastResponse = $api->getRequest()->getLastResponse();

        $retryAfter = $lastResponse['headers']['retry-after']; // Number of seconds to wait before sending another request
    } else {
        // Some other kind of error
    }
}
```

Read more about the exact mechanics of rate limiting in the [Spotify API docs](https://developer.spotify.com/documentation/web-api/guides/rate-limits/).
