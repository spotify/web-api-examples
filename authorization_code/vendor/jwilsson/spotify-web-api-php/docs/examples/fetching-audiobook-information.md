# Fetching Information About Audiobooks

There are a few methods for retrieving information about one or more audiobooks from the Spotify catalog. For example, the description of a audiobook or all of a audiobook's chapters.

## Getting info about a single audiobook

```php
$audiobook = $api->getAudiobook('AUDIOBOOK_ID');

echo '<b>' . $audiobook->name . '</b>';
```

## Getting info about multiple audiobooks

```php
$audiobooks = $api->getAudiobooks([
    'AUDIOBOOK_ID',
    'AUDIOBOOK_ID',
]);

foreach ($audiobooks->audiobooks as $audiobook) {
    echo '<b>' . $audiobook->name . '</b> <br>';
}
```

## Getting info about a single audiobook chapter

```php
$chapter = $api->getChapter('CHAPTER_ID');

echo '<b>' . $chapter->name . '</b>';
```

## Getting info about multiple audiobook chapters

```php
$chapters = $api->getChapters([
    'CHAPTER_ID',
    'CHAPTER_ID',
]);

foreach ($chapters->chapters as $chapter) {
    echo '<b>' . $chapter->name . '</b> <br>';
}
```

Please see the [method reference](/docs/method-reference/SpotifyWebAPI.md) for more available options for each method.
