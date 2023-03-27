# Managing a User's Profile

There are lots of operations involving a user's profile that can be performed. Remember to request the correct [scopes](working-with-scopes.md) beforehand.

### Getting the current user's profile

```php
$me = $api->me();

echo $me->display_name;
```

### Getting any user's profile

```php
$user = $api->getUser('USER_ID');

echo $user->display_name;
```

Please see the [method reference](/docs/method-reference/SpotifyWebAPI.md) for more available options for each method.
