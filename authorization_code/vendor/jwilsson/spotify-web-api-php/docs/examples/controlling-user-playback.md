# Controlling User Playback

Using Spotify Connect, it's possible to control the playback of the currently authenticated user.

## Getting user devices

The `SpotifyWebAPI::getMyDevices()` method can be used to list out a user's devices.

```php
// Get the Devices
$api->getMyDevices();
```

It is worth noting that if all devices have `is_active` set to `false` then using `SpotifyWebApi::play()` will fail.

## Start and stop playback
```php
// With Device ID
$api->play($deviceId, [
    'uris' => ['TRACK_URI'],
]);

// Without Device ID
$api->play(false, [
    'uris' => ['TRACK_URI'],
]);

$api->pause();
```

## Playing the next or previous track
```php
$api->previous();

$api->next();
```

## Adding a track to the queue
```php
$api->queue('TRACK_ID');
```

## Get info about the queue
```php
$api->getMyQueue();
```

## Get the currently playing track
```php
$api->getMyCurrentTrack();
```

## Get info about the current playback
```php
$api->getMyCurrentPlaybackInfo();
```

## Move to a specific position in a track
```php
$api->seek([
    'position_ms' => 60000 + 37000, // Move to the 1.37 minute mark
]);
```

## Set repeat and shuffle mode
```php
$api->repeat([
    'state' => 'track',
]);

$api->shuffle([
    'state' => false,
]);
```

## Control the volume
```php
$api->changeVolume([
    'volume_percent' => 78,
]);
```

## Retrying API calls
Sometimes, a API call might return a `202 Accepted` response code. When this occurs, you should retry the request after a few seconds. For example:

```php
try {
    $wasPaused = $api->pause():

    if (!$wasPaused) {
        $lastResponse = $api->getLastResponse();

        if ($lastResponse['status'] == 202) {
            // Perform some logic to retry the request after a few seconds
        }
    }
} catch (Exception $e) {
    $reason = $e->getReason();

    // Check the reason for the failure and handle the error
}
```

Read more about working with Spotify Connect in the [Spotify API docs](https://developer.spotify.com/documentation/web-api/guides/using-connect-web-api/).
