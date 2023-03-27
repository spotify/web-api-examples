# Session

## Table of Contents
* [__construct](#__construct)
* [generateCodeChallenge](#generatecodechallenge)
* [generateCodeVerifier](#generatecodeverifier)
* [generateState](#generatestate)
* [getAuthorizeUrl](#getauthorizeurl)
* [getAccessToken](#getaccesstoken)
* [getClientId](#getclientid)
* [getClientSecret](#getclientsecret)
* [getTokenExpiration](#gettokenexpiration)
* [getRedirectUri](#getredirecturi)
* [getRefreshToken](#getrefreshtoken)
* [getScope](#getscope)
* [refreshAccessToken](#refreshaccesstoken)
* [requestAccessToken](#requestaccesstoken)
* [requestCredentialsToken](#requestcredentialstoken)
* [setAccessToken](#setaccesstoken)
* [setClientId](#setclientid)
* [setClientSecret](#setclientsecret)
* [setRedirectUri](#setredirecturi)
* [setRefreshToken](#setrefreshtoken)

## Constants

## Methods
### __construct


```php
Session::__construct($clientId, $clientSecret, $redirectUri, $request)
```

Constructor<br>
Set up client credentials.

#### Arguments
* `$clientId` **string** - The client ID.
* `$clientSecret` **string** - Optional. The client secret.
* `$redirectUri` **string** - Optional. The redirect URI.
* `$request` **\SpotifyWebAPI\Request** - Optional. The Request object to use.


---
### generateCodeChallenge


```php
Session::generateCodeChallenge($codeVerifier, $hashAlgo)
```

Generate a code challenge from a code verifier for use with the PKCE flow.

#### Arguments
* `$codeVerifier` **string** - The code verifier to create a challenge from.
* `$hashAlgo` **string** - Optional. The hash algorithm to use. Defaults to "sha256".

#### Return values
* **string** The code challenge.

---
### generateCodeVerifier


```php
Session::generateCodeVerifier($length)
```

Generate a code verifier for use with the PKCE flow.

#### Arguments
* `$length` **int** - Optional. Code verifier length. Must be between 43 and 128 characters long, default is 128.

#### Return values
* **string** A code verifier string.

---
### generateState


```php
Session::generateState($length)
```

Generate a random state value.

#### Arguments
* `$length` **int** - Optional. Length of the state. Default is 16 characters.

#### Return values
* **string** A random state value.

---
### getAuthorizeUrl


```php
Session::getAuthorizeUrl($options)
```

Get the authorization URL.

#### Arguments
* `$options` **array\|object** - Optional. Options for the authorization URL.
    * string code_challenge Optional. A PKCE code challenge.
    * array scope Optional. Scope(s) to request from the user.
    * boolean show_dialog Optional. Whether or not to force the user to always approve the app. Default is false.
    * string state Optional. A CSRF token.

#### Return values
* **string** The authorization URL.

---
### getAccessToken


```php
Session::getAccessToken()
```

Get the access token.


#### Return values
* **string** The access token.

---
### getClientId


```php
Session::getClientId()
```

Get the client ID.


#### Return values
* **string** The client ID.

---
### getClientSecret


```php
Session::getClientSecret()
```

Get the client secret.


#### Return values
* **string** The client secret.

---
### getTokenExpiration


```php
Session::getTokenExpiration()
```

Get the access token expiration time.


#### Return values
* **int** A Unix timestamp indicating the token expiration time.

---
### getRedirectUri


```php
Session::getRedirectUri()
```

Get the client's redirect URI.


#### Return values
* **string** The redirect URI.

---
### getRefreshToken


```php
Session::getRefreshToken()
```

Get the refresh token.


#### Return values
* **string** The refresh token.

---
### getScope


```php
Session::getScope()
```

Get the scope for the current access token


#### Return values
* **array** The scope for the current access token

---
### refreshAccessToken


```php
Session::refreshAccessToken($refreshToken)
```

Refresh an access token.

#### Arguments
* `$refreshToken` **string** - Optional. The refresh token to use.

#### Return values
* **bool** Whether the access token was successfully refreshed.

---
### requestAccessToken


```php
Session::requestAccessToken($authorizationCode, $codeVerifier)
```

Request an access token given an authorization code.

#### Arguments
* `$authorizationCode` **string** - The authorization code from Spotify.
* `$codeVerifier` **string** - Optional. A previously generated code verifier. Will assume a PKCE flow if passed.

#### Return values
* **bool** True when the access token was successfully granted, false otherwise.

---
### requestCredentialsToken


```php
Session::requestCredentialsToken()
```

Request an access token using the Client Credentials Flow.


#### Return values
* **bool** True when an access token was successfully granted, false otherwise.

---
### setAccessToken


```php
Session::setAccessToken($accessToken)
```

Set the access token.

#### Arguments
* `$accessToken` **string** - The access token

#### Return values
* **void** 

---
### setClientId


```php
Session::setClientId($clientId)
```

Set the client ID.

#### Arguments
* `$clientId` **string** - The client ID.

#### Return values
* **void** 

---
### setClientSecret


```php
Session::setClientSecret($clientSecret)
```

Set the client secret.

#### Arguments
* `$clientSecret` **string** - The client secret.

#### Return values
* **void** 

---
### setRedirectUri


```php
Session::setRedirectUri($redirectUri)
```

Set the client's redirect URI.

#### Arguments
* `$redirectUri` **string** - The redirect URI.

#### Return values
* **void** 

---
### setRefreshToken


```php
Session::setRefreshToken($refreshToken)
```

Set the session's refresh token.

#### Arguments
* `$refreshToken` **string** - The refresh token.

#### Return values
* **void** 

---
