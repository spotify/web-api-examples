# Fetching Information About Albums

There are a few methods for retrieving information about one or more albums from the Spotify catalog. For example, info about a albums's artist or all the tracks on an album.

## Getting info about a single album

```php
$album = $api->getAlbum('ALBUM_ID');

echo '<b>' . $album->name . '</b>';
```

## Getting info about multiple albums

```php
$albums = $api->getAlbums([
    'ALBUM_ID',
    'ALBUM_ID',
]);

foreach ($albums->albums as $album) {
    echo '<b>' . $album->name . '</b> <br>';
}
```

## Getting all tracks on an album

```php
$tracks = $api->getAlbumTracks('ALBUM_ID');

foreach ($tracks->items as $track) {
    echo '<b>' . $track->name . '</b> <br>';
}
```

Please see the [method reference](/docs/method-reference/SpotifyWebAPI.md) for more available options for each method.
