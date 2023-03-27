# Getting Started

## Requirements
* PHP 7.3 or later.
* PHP [cURL extension](http://php.net/manual/en/book.curl.php) (Usually included with PHP).

## Autoloading
The Spotify Web API for PHP is compatible with [PSR-4](http://www.php-fig.org/psr/psr-4/). This means that the code makes heavy use of namespaces and the correct files can be loaded automatically. All examples throughout this documentation will assume the use of a PSR-4 compatible autoloader, for example via [Composer](https://getcomposer.org/).

## Installation

### Installation via Composer
This is the preferred way of installing the Spotify Web API for PHP. Run the following command in the root of your project:

```sh
composer require jwilsson/spotify-web-api-php
```

Then, in every file where you wish to use the Spotify Web API for PHP, include the following line:

```php
require_once 'vendor/autoload.php';
```

### Manual installation

Download the latest release from the [releases page](https://github.com/jwilsson/spotify-web-api-php/releases). Unzip the files somewhere in your project and include a [PSR-4 compatible autoloader](http://www.php-fig.org/psr/psr-4/examples/) in your project.

## Configuration and setup
First off, make sure you've created an app on [Spotify's developer site](https://developer.spotify.com/documentation/web-api/).

*Note: Applications created after 2021-05-27 [might need to perform some extra steps](https://developer.spotify.com/community/news/2021/05/27/improving-the-developer-and-user-experience-for-third-party-apps/).*

Now, before sending requests to Spotify, we need to create a session using your app info:

```php
$session = new SpotifyWebAPI\Session(
    'CLIENT_ID',
    'CLIENT_SECRET',
    'REDIRECT_URI'
);
```

Replace the values here with the ones given to you from Spotify.

## Authentication and authorization
After creating a session it's time to request access to the Spotify Web API. There are three ways to request an access token.

The first method is called *Proof Key for Code Exchange (PKCE)* and requires some interaction from the user, but in turn gives you some access to the user's account. This is the recommended method if you need access to a user's account.

The second method is called the *Authorization Code Flow* and just like the PKCE method it requires some interaction from the user, but will also give you access to the user's account.

The last method is called the *Client Credentials Flow* and doesn't require any user interaction but also doesn't provide any user information. This method is the recommended one if you just need access to Spotify catalog data.

For more info about each authorization method, checkout these examples:
* [Obtaining an access token using the Proof Key for Code Exchange (PKCE) Flow](/docs/examples/access-token-with-pkce-flow.md)
* [Obtaining an access token using the Authorization Code Flow](/docs/examples/access-token-with-authorization-code-flow.md)
* [Obtaining an access token using the Client Credentials Flow](/docs/examples/access-token-with-client-credentials-flow.md)

## Making requests to the Spotify API
Assuming you've followed one of the authorization guides above and successfully requested an access token, now it's time to create a new file called `app.php`. In this file we'll tell the API wrapper about the access token to use and then request some data from Spotify!

```php
require 'vendor/autoload.php';

// Fetch your access token from somewhere. A session for example.

$api = new SpotifyWebAPI\SpotifyWebAPI();
$api->setAccessToken($accessToken);

print_r(
    $api->getTrack('4uLU6hMCjMI75M1A2tKUQC')
);
```

Congratulations! You now know how to use the Spotify Web API for PHP. The next step is to check out [some examples](/docs/examples/) and the [method reference](/docs/method-reference/).
