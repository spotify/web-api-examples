# Setting custom cURL options

Sometimes, you need to override the default cURL options. For example increasing the timeout or setting some proxy setting.

In order to set custom cURL options, you'll need to instantiate a `Request` object yourself and passing it to `SpotifyWebAPI` instead of letting it set it up itself.

For example:
```php
$options = [
    'curl_options' => [
        CURLOPT_TIMEOUT => 60,
    ],
];

$request = new SpotifyWebAPI\Request($options);

// You can also call setOptions on an existing Request instance
$request->setOptions($options);

// Then, pass the $request when instantiating Session and SpotifyWebAPI
$session = new SpotifyWebAPI\Session(
    'CLIENT_ID',
    'CLIENT_SECRET',
    'REDIRECT_URI',
    $request
);

$api = new SpotifyWebAPI\SpotifyWebAPI([], null, $request);

// And continue as usual
```

The options you pass in `curl_options` will be merged with the default ones and existing options with the same key will be overwritten by the ones passed by you.

Refer to the [PHP docs](https://www.php.net/manual/en/function.curl-setopt.php) for a complete list of cURL options.
