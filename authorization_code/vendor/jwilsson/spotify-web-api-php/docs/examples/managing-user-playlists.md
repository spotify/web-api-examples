# Managing a User's Playlists

There are lots of operations involving user's playlists that can be performed. Remember to request the correct [scopes](working-with-scopes.md) beforehand.

## Listing a user's playlists

```php
$playlists = $api->getUserPlaylists('USER_ID', [
    'limit' => 5
]);

foreach ($playlists->items as $playlist) {
    echo '<a href="' . $playlist->external_urls->spotify . '">' . $playlist->name . '</a> <br>';
}
```

## Getting info about a specific playlist

```php
$playlist = $api->getPlaylist('PLAYLIST_ID');

echo $playlist->name;
```

## Getting the image of a user's playlist
```php
$playlistImage = $api->getPlaylistImage('PLAYLIST_ID');
```

## Getting all tracks in a playlist

```php
$playlistTracks = $api->getPlaylistTracks('PLAYLIST_ID');

foreach ($playlistTracks->items as $track) {
    $track = $track->track;

    echo '<a href="' . $track->external_urls->spotify . '">' . $track->name . '</a> <br>';
}
```

## Creating a new playlist

```php
$api->createPlaylist([
    'name' => 'My shiny playlist'
]);
```

## Updating the details of a user's playlist

```php
$api->updatePlaylist('PLAYLIST_ID', [
    'name' => 'New name'
]);
```

## Updating the image of a user's playlist
```php
$imageData = base64_encode(file_get_contents('image.jpg'));

$api->updatePlaylistImage('PLAYLIST_ID', $imageData);
```

## Adding tracks to a user's playlist

```php
$api->addPlaylistTracks('PLAYLIST_ID', [
    'TRACK_ID',
    'EPISODE_URI'
]);
```

## Delete tracks from a user's playlist based on IDs

```php
$tracks = [
    'tracks' => [
        ['uri' => 'TRACK_ID'],
        ['uri' => 'EPISODE_URI'],
    ],
];

$api->deletePlaylistTracks('PLAYLIST_ID', $tracks, 'SNAPSHOT_ID');
```

## Delete tracks from a user's playlist based on positions

```php
$trackOptions = [
    'positions' => [
        5,
        12,
    ],
];

$api->deletePlaylistTracks('PLAYLIST_ID', $trackOptions, 'SNAPSHOT_ID');
```

## Replacing all tracks in a user's playlist with new ones

```php
$api->replacePlaylistTracks('PLAYLIST_ID', [
    'TRACK_ID',
    'EPISODE_URI'
]);
```

## Reorder the tracks in a user's playlist

```php
$api->reorderPlaylistTracks('PLAYLIST_ID', [
    'range_start' => 1,
    'range_length' => 5,
    'insert_before' => 10,
    'snapshot_id' => 'SNAPSHOT_ID'
]);
```

Please see the [method reference](/docs/method-reference/SpotifyWebAPI.md) for more available options for each method.
