# Fetching Spotify Featured Content

If you wish to access content that's featured and/or curated by Spotify there are a number of methods available to achieve that.

## Getting a list of new releases

```php
$releases = $api->getNewReleases([
    'country' => 'se',
]);

foreach ($releases->albums->items as $album) {
    echo '<a href="' . $album->external_urls->spotify . '">' . $album->name . '</a> <br>';
}
```

## Getting a list of featured playlists

```php
$playlists = $api->getFeaturedPlaylists([
    'country' => 'se',
    'locale' => 'sv_SE',
    'timestamp' => '2015-01-17T21:00:00', // Saturday night
]);

foreach ($playlists->playlists->items as $playlist) {
    echo '<a href="' . $playlist->external_urls->spotify . '">' . $playlist->name . '</a> <br>';
}
```

## Getting a list of Spotify categories

```php
$categories = $api->getCategoriesList([
    'country' => 'se',
    'locale' => 'sv_SE',
    'limit' => 10,
    'offset' => 0,
]);

foreach ($categories->categories->items as $category) {
    echo '<a href="' . $category->href . '">' . $category->name . '</a><br>';
}

## Getting a single Spotify category

```php
$category = $api->getCategory('dinner', [
    'country' => 'se',
]);

echo '<a href="' . $category->href . '">' . $category->name . '</a>';
```

## Getting a category's playlists

```php
$playlists = $api->getCategoryPlaylists('dinner', [
    'country' => 'se',
    'limit' => 10,
    'offset' => 0
]);

foreach ($playlists->playlists->items as $playlist) {
    echo '<a href="' . $playlist->href . '">' . $playlist->name . '</a><br>';
}
```

Please see the [method reference](/docs/method-reference/SpotifyWebAPI.md) for more available options for each method.
