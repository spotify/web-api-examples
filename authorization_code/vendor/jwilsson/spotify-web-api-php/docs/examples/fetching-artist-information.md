# Fetching Information About Artists

There are a few methods for retrieving information about one or more albums from the Spotify catalog. For example, info about a single artist or an artist's top tracks in a country.

## Getting info about a single artist

```php
$artist = $api->getArtist('ARTIST_ID');

echo '<b>' . $artist->name . '</b>';
```

## Getting info about multiple artists

```php
$artists = $api->getArtists([
    'ARTIST_ID',
    'ARTIST_ID',
]);

foreach ($artists->artists as $artist) {
    echo '<b>' . $artist->name . '</b> <br>';
}
```

## Getting an artist's albums

```php
$albums = $api->getArtistAlbums('ARTIST_ID');

foreach ($albums->items as $album) {
    echo '<b>' . $album->name . '</b> <br>';
}
```

## Getting an artist's related artists

```php
$artists = $api->getArtistRelatedArtists('ARTIST_ID');

foreach ($artists->artists as $artist) {
    echo '<b>' . $artist->name . '</b> <br>';
}
```

## Getting an artist’s top tracks in a country

```php
$tracks = $api->getArtistTopTracks('ARTIST_ID', [
    'country' => 'se',
]);

foreach ($tracks->tracks as $track) {
    echo '<b>' . $track->name . '</b> <br>';
}
```

## Getting recommendations based on artists

```php
$seedArtist = ['ARTIST_ID', 'ARTIST_ID'];

$recommendations = $api->getRecommendations([
    'seed_artists' => $seedArtist,
]);

print_r($recommendations);
```

It's also possible to fetch recommendations based on genres and tracks, see the [Spotify docs](https://developer.spotify.com/documentation/web-api/reference/#/operations/get-recommendations) for more info.

Please see the [method reference](/docs/method-reference/SpotifyWebAPI.md) for more available options for each method.
