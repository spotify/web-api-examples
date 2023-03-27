# Fetching Information About Tracks

There are a few methods for retrieving information about one or more albums from the Spotify catalog. For example, info about a track's artist or recommendations on similar tracks.

## Getting info about a single track

```php
$track = $api->getTrack('TRACK_ID');

echo '<b>' . $track->name . '</b> by <b>' . $track->artists[0]->name . '</b>';
```

## Getting info about multiple tracks

```php
$tracks = $api->getTracks([
    'TRACK_ID',
    'TRACK_ID',
]);

foreach ($tracks->tracks as $track) {
    echo '<b>' . $track->name . '</b> by <b>' . $track->artists[0]->name . '</b> <br>';
}
```

## Getting the audio analysis of a track

```php
$analysis = $api->getAudioAnalysis('TRACK_ID');

print_r($analysis);
```

## Getting the audio features of a track

```php
$features = $api->getAudioFeatures('TRACK_ID');

print_r($features);
```

## Getting the audio features of multiple tracks

```php
$tracks = [
    'TRACK_ID',
    'TRACK_ID',
];

$features = $api->getMultipleAudioFeatures($tracks);

print_r($features);
```

## Getting recommendations based on tracks

```php
$seedTracks = ['TRACK_ID', 'TRACK_ID'];

$recommendations = $api->getRecommendations([
    'seed_tracks' => $seedTracks,
]);

print_r($recommendations);
```

It's also possible to fetch recommendations based on genres and artists, see the [Spotify docs](https://developer.spotify.com/documentation/web-api/reference/#/operations/get-recommendations) for more info.

Please see the [method reference](/docs/method-reference/SpotifyWebAPI.md) for more available options for each method.
