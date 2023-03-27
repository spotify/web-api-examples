# Searching the Spotify Catalog

The whole Spotify catalog, including playlists, can be searched in various ways. Since the Spotify search contains so many features, this page just includes a basic example and one should refer to the
[Spotify documentation](https://developer.spotify.com/documentation/web-api/reference/#/operations/search) and [method reference](/docs/method-reference/SpotifyWebAPI.md) for more information.

```php
$results = $api->search('blur', 'artist');

foreach ($results->artists->items as $artist) {
    echo $artist->name, '<br>';
}
```
