# SpotifyWebAPIException

## Table of Contents
* [getReason](#getreason)
* [hasExpiredToken](#hasexpiredtoken)
* [isRateLimited](#isratelimited)
* [setReason](#setreason)

## Constants
* **TOKEN_EXPIRED**
* **RATE_LIMIT_STATUS**

## Methods
### getReason


```php
SpotifyWebAPIException::getReason()
```

Returns the reason string from a player request's error object.


#### Return values
* **string** 

---
### hasExpiredToken


```php
SpotifyWebAPIException::hasExpiredToken()
```

Returns whether the exception was thrown because of an expired access token.


#### Return values
* **bool** 

---
### isRateLimited


```php
SpotifyWebAPIException::isRateLimited()
```

Returns whether the exception was thrown because of rate limiting.


#### Return values
* **bool** 

---
### setReason


```php
SpotifyWebAPIException::setReason($reason)
```

Set the reason string.

#### Arguments
* `$reason` **string**

#### Return values
* **void** 

---
