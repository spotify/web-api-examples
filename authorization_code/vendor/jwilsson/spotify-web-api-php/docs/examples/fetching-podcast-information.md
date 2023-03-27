# Fetching Information About Podcasts

There are a few methods for retrieving information about one or more podcasts from the Spotify catalog. For example, the description of a podcast or all of a podcast's episodes.

## Getting info about a single podcast show

```php
$show = $api->getShow('SHOW_ID');

echo '<b>' . $show->name . '</b>';
```

## Getting info about multiple podcast shows

```php
$shows = $api->getShows([
    'SHOW_ID',
    'SHOW_ID',
]);

foreach ($shows->shows as $show) {
    echo '<b>' . $show->name . '</b> <br>';
}
```

## Getting info about a single podcast episode

```php
$episode = $api->getEpisode('EPISODE_ID');

echo '<b>' . $episode->name . '</b>';
```

## Getting info about multiple podcast episodes

```php
$episodes = $api->getEpisodeS([
    'EPISODE_ID',
    'EPISODE_ID',
]);

foreach ($episodes->episodes as $episode) {
    echo '<b>' . $episode->name . '</b> <br>';
}
```

## Getting a podcast show's episodes

```php
$episodes = $api->getShowEpisodes('SHOW_ID');

foreach ($episodes->items as $episode) {
    echo '<b>' . $episode->name . '</b> <br>';
}
```

Please see the [method reference](/docs/method-reference/SpotifyWebAPI.md) for more available options for each method.
