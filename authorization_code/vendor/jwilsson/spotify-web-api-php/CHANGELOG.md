# Changelog
## 5.4.0 (2022-10-16)
* Added methods to get info about audiobooks: ([f79b95e](https://github.com/jwilsson/spotify-web-api-php/commit/f79b95ec511e5c940d69569614d198082b7b8ad4)):
    * `SpotifyWebAPI::getAudiobook()`
    * `SpotifyWebAPI::getAudiobooks()`
    * `SpotifyWebAPI::getChapter()`
    * `SpotifyWebAPI::getChapters()`
* Updated CA bundle. ([987e99a](https://github.com/jwilsson/spotify-web-api-php/commit/987e99a865fe1863b38ab631c354a7b430c77338))

## 5.3.0 (2022-09-03)
* Added the `SpotifyWebAPI::getMyQueue()` method to get the current user's queue. ([710b70d](https://github.com/jwilsson/spotify-web-api-php/commit/710b70d13ce9ffabc3e01cfff847fdb1067111bf))
* Updated CA bundle. ([7e4994b](https://github.com/jwilsson/spotify-web-api-php/commit/7e4994bf9ebc6c1efa22414a63068309c0cd5abe))

## 5.2.0 (2022-07-16)
* Added `additional_types` option support to `SpotifyWebAPI::getPlaylist()` and `SpotifyWebAPI::getPlaylistTracks()`. ([9c3d117](https://github.com/jwilsson/spotify-web-api-php/commit/9c3d117a1c4cc86b0fe5ae7f84d490d2bf8d5758))
* Updated inline docs with missing options on multiple methods. ([7b49453](https://github.com/jwilsson/spotify-web-api-php/commit/7b49453c412901bb78bda370e58098779a31d679))

## 5.1.0 (2022-07-04)
* The deprecated way of passing arrays to `SpotifyWebAPI::getAudioFeatures()` will now print run time warnings. ([a5071b0](https://github.com/jwilsson/spotify-web-api-php/commit/a5071b023da105c8628873d564c28f3013533936))
* Replaced legacy `CURLINFO_HTTP_CODE` with `CURLINFO_RESPONSE_CODE` ([cdefdee](https://github.com/jwilsson/spotify-web-api-php/commit/cdefdee8dfadccb0735cc6756daca02d9809e58a))
* Updated inline links to Spotify docs. ([04d2412](https://github.com/jwilsson/spotify-web-api-php/commit/04d2412ea76e78aa55b6904ddc06ec61eacbd071))

## 5.0.3 (2022-01-15)
* `Session::refreshAccessToken()` will no longer send client secret when none exists. ([7e35d41](https://github.com/jwilsson/spotify-web-api-php/commit/7e35d418dd051aeccff2d38d7258c5a8108885fb))

## 5.0.2 (2021-12-01)
* Fixed an issue where errors returned from cURL would not be read properly. ([177a808](https://github.com/jwilsson/spotify-web-api-php/commit/177a8087d7d83f47c9f5c1b9b717adb2e0a17ef2))
* Updated CA bundle. ([44ca81a](https://github.com/jwilsson/spotify-web-api-php/commit/44ca81aa53b9941f662cbb905bdc448dadef7ae8))

## 5.0.1 (2021-10-05)
* Exceptions thrown on failing requests will now try a bit harder to give a failure reason by falling back to the complete response body. ([e69dbea](https://github.com/jwilsson/spotify-web-api-php/commit/e69dbea553722c1ec59fd4fadb4d7a09889b6518))
* Updated CA bundle. ([a43fd12](https://github.com/jwilsson/spotify-web-api-php/commit/a43fd125a331d08d87cefca0d6a7ff9ea4884cf8))

## 5.0.0 (2021-05-14)
* **Breaking** Removed the following, deprecated methods: ([bd08fb8](https://github.com/jwilsson/spotify-web-api-php/commit/bd08fb8c6d4e99ad7898b320b1295a5987f5e566))
    * `Request::getReturnType()` - Use the `return_assoc` option instead.
    * `Request::setReturnType()` - Use the `return_assoc` option instead.
    * `Request::setCurlOptions()` - Use the `curl_options` option instead.
    * `SpotifyWebAPI::followPlaylistForCurrentUser()` - Use `SpotifyWebAPI::followPlaylist()` instead.
    * `SpotifyWebAPI::unfollowPlaylistForCurrentUser()` - Use `SpotifyWebAPI::unfollowPlaylist()` instead.
    * `SpotifyWebAPI::getReturnType()` - Use the `return_assoc` option instead.
    * `SpotifyWebAPI::setReturnType()` - Use the `return_assoc` option instead.
* **Breaking** `SpotifyWebAPI::deletePlaylistTracks()` no longer accepts the `id` key, the `uri` key should be used instead. ([403a8a2](https://github.com/jwilsson/spotify-web-api-php/commit/403a8a21985b4cbe775bfa8e175db3709ff067be))
* **Breaking** `SpotifyWebAPI::getArtistAlbums()` no longer accepts the `album_type` option, the `include_groups` option should be used instead. ([4cf3301](https://github.com/jwilsson/spotify-web-api-php/commit/4cf3301ea0572c219d171cd69782ae406f2b3628))
* **Breaking** `SpotifyWebAPI::__construct()` will no longer accept `Request` objects as the first argument. ([1f08d8](https://github.com/jwilsson/spotify-web-api-php/commit/1f08d8e6ce8d40fe77192d188ac369645a51883f))
* **Breaking** Dropped support for PHP versions less than 7.3. [21776d6](https://github.com/jwilsson/spotify-web-api-php/commit/21776d6f9a00dd07e10b198457e32ab5dfb123b1))
* **Possibly breaking** HTTP response header names are now always lowercased, previously they were returned as-is. ([f0140fa](https://github.com/jwilsson/spotify-web-api-php/commit/f0140faa7ae58d643fd9c7a3a4070be769bf33ba))
* **Possibly breaking** `SpotifyWebAPI::addPlaylistTracks()` will now return snapshot IDs insteadd of a boolean. ([d6167a3](https://github.com/jwilsson/spotify-web-api-php/commit/d6167a3f5b7946b80abb4d5d5d103bd3b1cdf441))
* Updated CA bundle. ([fb0c1a](https://github.com/jwilsson/spotify-web-api-php/commit/fb0c1a767b677e070d636176f4c7c993dc3493dc))

## 4.3.0 (2021-03-28)
* Added methods to control the episodes in a user's library: ([b89299d](https://github.com/jwilsson/spotify-web-api-php/commit/b89299dfb3d60f2002bbe7d89a37618235f565f3)):
    * `SpotifyWebAPI::addMyEpisodes()`
    * `SpotifyWebAPI::deleteMyEpisodes()`
    * `SpotifyWebAPI::getMySavedEpisodes()`
    * `SpotifyWebAPI::myEpisodesContains()`
* Added `SpotifyWebAPI::getMultipleAudioFeatures()` method to get the audio features of multiple tracks. `SpotifyWebAPI::getAudioFeatures()` will now return the audio features of a single track unless passed an array which will maintain the old behaviour. ([d282e9d](https://github.com/jwilsson/spotify-web-api-php/commit/d282e9d6444f636b825a1fce0c6e5fcdd3c28ba5))
* Added `SpotifyWebAPI::getMarkets()` method to get the markets where Spotify is available. ([181cc28](https://github.com/jwilsson/spotify-web-api-php/commit/181cc280c6d5008bd4ba9eebd29f914245f35013))
* Restructured Getting Started guide. ([2291a78](https://github.com/jwilsson/spotify-web-api-php/commit/2291a787c76850a8b4d6c6e3536256bedc4b2760))
* Updated inline PHPDoc Spotify API links to their new format. ([8a2f870](https://github.com/jwilsson/spotify-web-api-php/commit/8a2f8704015474f9879c3c2dbb606f3d8dadea91))

## 4.2.2 (2021-03-02)
* Fixed an issue where empty arrays would be passed to Spotify, causing errors. ([b350526](https://github.com/jwilsson/spotify-web-api-php/commit/b35052695edb7ff517714d48120ce29565036203))

## 4.2.1 (2021-01-31)
* Fixed `http_build_query` calls passing `null` instead of empty strings. ([e94593e](https://github.com/jwilsson/spotify-web-api-php/commit/e94593ecc0e64de4fa60a50ad9f365e3bda38737))
* Updated CA bundle. ([00556f2](https://github.com/jwilsson/spotify-web-api-php/commit/00556f2c5bc3112d18c3a016a4297c0387ee1dc9))

## 4.2.0 (2021-01-06)
* Added a `Session::generateState()` method. ([fa5ea56](https://github.com/jwilsson/spotify-web-api-php/commit/fa5ea561afc166a1ba0e9d555017379e2299af5a))
* `SpotifyWebAPI::getArtistAlbums()` can now also accept the documented `include_groups` parameter. ([dc9a84](https://github.com/jwilsson/spotify-web-api-php/commit/dc9a841f9465bb6519b84b8c062bbb457995cd7e))
* Fixed some cases where objects weren't always properly cast to arrays. ([31eae20](https://github.com/jwilsson/spotify-web-api-php/commit/31eae2091f1f214b900a1455b4ba6271731471c5))
* PSR-12 coding style is now used. ([fed3aa6](https://github.com/jwilsson/spotify-web-api-php/commit/fed3aa611856a5964a8856b1ad80f9b4bfade332))

## 4.1.1 (2020-12-11)
* `HTTP 100 Continue` headers which is sometimes returned are now stripped. ([375c64d](https://github.com/jwilsson/spotify-web-api-php/commit/375c64dac5603199c7234933a83d85da8f0aa816))
* Updated CA bundle. ([ad29a9b](https://github.com/jwilsson/spotify-web-api-php/commit/ad29a9b53430829c4d6c8ef9247bdb8893430318))

## 4.1.0 (2020-12-07)
* Added the `SpotifyWebAPI::getPlaylistImage()` method to get a playlist's cover image. ([7a4f0e6](https://github.com/jwilsson/spotify-web-api-php/commit/7a4f0e67ef8affa2010a429c45da2a51e32e04b1))

## 4.0.0 (2020-11-30)
* **Breaking** Dropped support for PHP versions less than 7.2. ([2bbefc7](https://github.com/jwilsson/spotify-web-api-php/commit/2bbefc731a3cd58669823209014ca657da422d83))
* Added PHP 8 support. ([3e37d76](https://github.com/jwilsson/spotify-web-api-php/commit/3e37d7649f845dc6504fde8917d0789f4758a1c7))

## 3.6.2 (2020-10-19)
* Made sure `&` is always passed to all `http_build_query()` calls. ([e2f0c26](https://github.com/jwilsson/spotify-web-api-php/commit/e2f0c26daef517dd507da80799c8821c677e660d))
* Updated CA bundle. ([4b824c3](https://github.com/jwilsson/spotify-web-api-php/commit/4b824c3b639b1702569b3ec7f2a95e79e96577a6))

## 3.6.1 (2020-10-11)
* Fixed an issue with the auto retry feature sometimes not working correctly due to lowercased header names. ([28c7b1d](https://github.com/jwilsson/spotify-web-api-php/commit/28c7b1da3ac18a82b0b74aadfb7da47024ddb6e5))

## 3.6.0 (2020-09-19)
* Methods deprecated in `3.x` releases will now print deprecation warnings. ([fe67153](https://github.com/jwilsson/spotify-web-api-php/commit/fe6715358cb12e8fbb600c528a1ea2534bd39c2b))
* `SpotifyWebAPI::queue()` can now also accept track IDs. ([a3aee78](https://github.com/jwilsson/spotify-web-api-php/commit/a3aee782e1aa0ee7d88a201a70d00263f31c84f8))
* Updated CA bundle. ([dfa4edc](https://github.com/jwilsson/spotify-web-api-php/commit/dfa4edc0891767799f54b214d9ade77168a4148b))

## 3.5.1 (2020-08-15)
* Fixed an issue where URIs couldn't be passed in `SpotifyWebAPI::deletePlaylistTracks()`. ([f6e61e5](https://github.com/jwilsson/spotify-web-api-php/commit/f6e61e54bbe56548f75cd5ba68c5f4683505c4f7))
* Hardened check before replacing in `SpotifyWebAPI::idToUri()`. ([299062a](https://github.com/jwilsson/spotify-web-api-php/commit/299062ac0aa73c66d319b4d9fdde7295866a6719))

## 3.5.0 (2020-06-23)
* Added support for the PKCE authorization flow. ([88e4cad](https://github.com/jwilsson/spotify-web-api-php/commit/88e4cad251c0155f73093373ed92542ee6ef7266))

## 3.4.0 (2020-04-22)
* Added the `SpotifyWebAPI::queue()` method to add tracks to the user's playback queue. ([d6859a5](https://github.com/jwilsson/spotify-web-api-php/commit/d6859a5ad8509eee2685a3b779ff8cf639b56d4e))

## 3.3.0 (2020-03-28)
* Options and `Session` instance can now be passed to the `SpotifyWebAPI` constructor. ([d178204](https://github.com/jwilsson/spotify-web-api-php/commit/d178204c23de1e5785f70cc70368d9e373999746))
* Options can now be passed to the `Request` constructor. ([065a7fc](https://github.com/jwilsson/spotify-web-api-php/commit/065a7fcba61f472efe0ebb8e17821510c80a647e))

## 3.2.0 (2020-03-21)
* Added support for new Podcast endpoints ([eba5971](https://github.com/jwilsson/spotify-web-api-php/commit/eba597182b0d9a05aec80153da4b152d50c66d41)):
    * `SpotifyWebAPI::addMyShows()`
    * `SpotifyWebAPI::getMySavedShows()`
    * `SpotifyWebAPI::getShow()`
    * `SpotifyWebAPI::getShows()`
    * `SpotifyWebAPI::getShowEpisodes()`
    * `SpotifyWebAPI::getEpisode()`
    * `SpotifyWebAPI::getEpisodes()`
    * `SpotifyWebAPI::myShowsContains()`
    * `SpotifyWebAPI::deleteMyShows()`
* Added support for the new `additional_types` option to ([b04e06e](https://github.com/jwilsson/spotify-web-api-php/commit/b04e06eb72b8876611108eca94b740eeb1156a25)):
    * `SpotifyWebAPI::getMyCurrentTrack()`
    * `SpotifyWebAPI::getMyCurrentPlaybackInfo()`
* Updated CA bundle. ([4359fc3](https://github.com/jwilsson/spotify-web-api-php/commit/4359fc3697e6a7408ed978bb850d1d033ca6753c))

## 3.1.0 (2020-02-21)
* Deprecated the following methods ([e643628](https://github.com/jwilsson/spotify-web-api-php/commit/e643628698e146371d1944099bb107e7a9165a19)):
    * `SpotifyWebAPI::followPlaylistForCurrentUser()` (use `SpotifyWebAPI::followPlaylist()` instead)
    * `SpotifyWebAPI::unfollowPlaylistForCurrentUser()` (use `SpotifyWebAPI::unfollowPlaylist()` instead)
* Updated `SpotifyWebAPI::addPlaylistTracks()` to always send everything in request body. ([ac862ef](https://github.com/jwilsson/spotify-web-api-php/commit/ac862ef66548e49352c88e167b64bbfd86407c62))
* Refactored calls to `SpotifyWebAPI::authHeaders()`. ([7d0badd](https://github.com/jwilsson/spotify-web-api-php/commit/7d0badd842ae9a4a2700dc8a2a29dd70bdad90fe))

## 3.0.0 (2019-12-14)
* **Breaking** The following, deprecated, methods have been removed ([02d5598](https://github.com/jwilsson/spotify-web-api-php/commit/02d5598b9fb8c06615e67e31fc00cbca1a67af36)):
    * `SpotifyWebAPI::addUserPlaylistTracks()` (use `SpotifyWebAPI::addPlaylistTracks()` instead)
    * `SpotifyWebAPI::createUserPlaylist()` (use `SpotifyWebAPI::createPlaylist()` instead)
    * `SpotifyWebAPI::deleteUserPlaylistTracks()` (use `SpotifyWebAPI::deletePlaylistTracks()` instead)
    * `SpotifyWebAPI::followPlaylist()` (use `SpotifyWebAPI::followPlaylistForCurrentUser()` instead)
    * `SpotifyWebAPI::getUserPlaylist()` (use `SpotifyWebAPI::getPlaylist()` instead)
    * `SpotifyWebAPI::getUserPlaylistTracks()` (use `SpotifyWebAPI::getPlaylistTracks()` instead)
    * `SpotifyWebAPI::reorderUserPlaylistTracks()` (use `SpotifyWebAPI::reorderPlaylistTracks()` instead)
    * `SpotifyWebAPI::replaceUserPlaylistTracks()` (use `SpotifyWebAPI::replacePlaylistTracks()` instead)
    * `SpotifyWebAPI::unfollowPlaylist()` (use `SpotifyWebAPI::unfollowPlaylistForCurrentUser()` instead)
    * `SpotifyWebAPI::updateUserPlaylist()` (use `SpotifyWebAPI::updatePlaylist()` instead)
    * `SpotifyWebAPI::updateUserPlaylistImage()` (use `SpotifyWebAPI::updatePlaylistImage()` instead)
    * `SpotifyWebAPI::userFollowsPlaylist()` (use `SpotifyWebAPI::usersFollowPlaylist()` instead)
    * `SpotifyWebAPI::userFollowsPlaylist()` (use `SpotifyWebAPI::usersFollowPlaylist()` instead)
* **Breaking** Removed the possibility to omit `tracks` in calls to `SpotifyWebAPI::deletePlaylistTracks()`. ([3562402](https://github.com/jwilsson/spotify-web-api-php/commit/35624027f82b37ce6f3a32497754dcb26abf9900))
* **Breaking** Dropped support for PHP versions less than 7. ([461a0e4](https://github.com/jwilsson/spotify-web-api-php/commit/461a0e440b7447c98f04cb237bdbd3c44f538928))
* `SpotifyWebAPI::getReturnType()`/`SpotifyWebAPI::setReturnType()` and `Request::getReturnType()`/`Request::setReturnType()` are deprecated. Use the new `return_assoc` option instead. ([2a3d5ee](https://github.com/jwilsson/spotify-web-api-php/commit/2a3d5ee192a39299904e8e0ce46926dae7a99a71))
* `Request::setCurlOptions()` is deprecated. Use the new `curl_options` option instead. ([1615d07](https://github.com/jwilsson/spotify-web-api-php/commit/1615d076733274a01c40ad40a29b6af2a2019f64))

## 2.12.0 (2019-11-01)
* Added support for automatically retrying rate limited requests. ([5fe8903](https://github.com/jwilsson/spotify-web-api-php/commit/5fe8903ea598d558639b35055ce9d32073ded634))
* Updated CA bundle. ([19df152](https://github.com/jwilsson/spotify-web-api-php/commit/19df152302fc6ad8fc7b887d5d909e11745ca035))

## 2.11.1 (2019-10-19)
* Remove unfinished code that accidentally snuck into `2.11.0`. ([eb3f51c](https://github.com/jwilsson/spotify-web-api-php/commit/eb3f51c5cf5fa1b7629a59376ee54294f3aa02b7))

## 2.11.0 (2019-10-19)
* Added support for automatically refreshing access tokens. ([5ec5fd4](https://github.com/jwilsson/spotify-web-api-php/commit/5ec5fd49b0d6c79db54ce78953e0598809350e84))

## 2.10.1 (2019-10-02)
* Fixed an issue where `SpotifyWebAPI::deletePlaylistTracks()` wouldn't return the snapshot ID when return type was set to `SpotifyWebAPI::RETURN_ASSOC`. ([f9d580f](https://github.com/jwilsson/spotify-web-api-php/commit/f9d580f322fb39512c3886872213322bf9dc66b2))
* Fixed an issue where `SpotifyWebAPI::reorderPlaylistTracks()` wouldn't return the snapshot ID when return type was set to `SpotifyWebAPI::RETURN_ASSOC`. ([89fe613](https://github.com/jwilsson/spotify-web-api-php/commit/89fe613bf649533ee173234776cc2c28991ee5c0))

## 2.10.0 (2019-08-27)
* Added a `$options` argument to `SpotifyWebAPI::getAlbum()`. ([e92121f](https://github.com/jwilsson/spotify-web-api-php/commit/e92121fd26da920cade7b8c79ccd1debc6581407))

## 2.9.0 (2019-08-03)
* The following deprecated methods will now emit a run time notice when used ([fc1e535](https://github.com/jwilsson/spotify-web-api-php/commit/fc1e5351bfd1ab395b14bf0981420c199d6cce76)):
    * `SpotifyWebAPI::addUserPlaylistTracks()`
    * `SpotifyWebAPI::createUserPlaylist()`
    * `SpotifyWebAPI::deleteUserPlaylistTracks()`
    * `SpotifyWebAPI::followPlaylistForCurrentUser()`
    * `SpotifyWebAPI::getUserPlaylist()`
    * `SpotifyWebAPI::getUserPlaylistTracks()`
    * `SpotifyWebAPI::reorderUserPlaylistTracks()`
    * `SpotifyWebAPI::replaceUserPlaylistTracks()`
    * `SpotifyWebAPI::unfollowPlaylistForCurrentUser()`
    * `SpotifyWebAPI::updateUserPlaylist()`
    * `SpotifyWebAPI::updateUserPlaylistImage()`
    * `SpotifyWebAPI::usersFollowPlaylist`
* When passing `$tracks` without a `tracks` or `positions` key to `SpotifyWebAPI::deletePlaylistTracks` a run time notice will be emitted. ([2f457bd](https://github.com/jwilsson/spotify-web-api-php/commit/2f457bd8bd1ab86a64e51942a637fdd07a2ce350))

## 2.8.0 (2019-06-29)
* Added a `Request::setCurlOptions()` method to make it possible to set custom cURL options. ([5a8b1e5](https://github.com/jwilsson/spotify-web-api-php/commit/5a8b1e5cd7cc3b4969ea615e55ac3dd66b12a872))

## 2.7.0 (2019-06-19)
* Added a `SpotifyWebAPIException::getReason()` method to retrieve the reason for a player error. ([ce728e1](https://github.com/jwilsson/spotify-web-api-php/commit/ce728e1319996894d2cd1eb90f7bc8e8482fa327))
* Updated CA bundle. ([4732256](https://github.com/jwilsson/spotify-web-api-php/commit/4732256b21a430f7fffe67bec79a915d445fc8ec))

## 2.6.1 (2018-11-07)
* Corrected the authorization URL ([891cd0f](https://github.com/jwilsson/spotify-web-api-php/commit/891cd0f1be525def4bf9f9969bfb6e34bd7b0ffc))

## 2.6.0 (2018-09-19)
* Added new methods for handling following of playlists, no longer requiring `$userId` ([6f1bb6b](https://github.com/jwilsson/spotify-web-api-php/commit/6f1bb6b01e253e5b44e02333d6dbd4815773552b)):
    * `SpotifyWebAPI::followPlaylistForCurrentUser()`
    * `SpotifyWebAPI::unfollowPlaylistForCurrentUser()`
    * `SpotifyWebAPI::usersFollowPlaylist`
* Deprecated the following methods ([6f1bb6b](https://github.com/jwilsson/spotify-web-api-php/commit/6f1bb6b01e253e5b44e02333d6dbd4815773552b)):
    * `SpotifyWebAPI::followPlaylist()`
    * `SpotifyWebAPI::unfollowPlaylist()`
    * `SpotifyWebAPI::userFollowPlaylist`

## 2.5.1 (2018-07-18)
* Corrected the URI in `SpotifyWebAPI::createUserPlaylist()`. ([d8a59eb](https://github.com/jwilsson/spotify-web-api-php/commit/d8a59eb878b5a785bc9c84887f86ba43cf50b13b))

## 2.5.0 (2018-07-03)
* Because of [recent changes](https://developer.spotify.com/community/news/2018/06/12/changes-to-playlist-uris/) in the handling of playlist related calls, the following methods are deprecated ([a05db96](https://github.com/jwilsson/spotify-web-api-php/commit/a05db96755928d914ceae83c20cc8c72fe5f1568)):
    * `SpotifyWebAPI::addUserPlaylistTracks()`
    * `SpotifyWebAPI::createUserPlaylist()`
    * `SpotifyWebAPI::deleteUserPlaylistTracks()`
    * `SpotifyWebAPI::getUserPlaylist()`
    * `SpotifyWebAPI::getUserPlaylistTracks()`
    * `SpotifyWebAPI::reorderUserPlaylistTracks()`
    * `SpotifyWebAPI::replaceUserPlaylistTracks()`
    * `SpotifyWebAPI::updateUserPlaylist()`
    * `SpotifyWebAPI::updateUserPlaylistImage()`
* The following methods should be used instead, accepting the same arguments except for `$userId` ([a05db96](https://github.com/jwilsson/spotify-web-api-php/commit/a05db96755928d914ceae83c20cc8c72fe5f1568)):
    * `SpotifyWebAPI::addPlaylistTracks()`
    * `SpotifyWebAPI::createPlaylist()`
    * `SpotifyWebAPI::deletePlaylistTracks()`
    * `SpotifyWebAPI::getPlaylist()`
    * `SpotifyWebAPI::getPlaylistTracks()`
    * `SpotifyWebAPI::reorderPlaylistTracks()`
    * `SpotifyWebAPI::replacePlaylistTracks()`
    * `SpotifyWebAPI::updatePlaylist()`
    * `SpotifyWebAPI::updatePlaylistImage()`

## 2.4.0 (2018-06-06)
* Authentication errors will now throw an `SpotifyWebAPIAuthException` instead of the regular `SpotifyWebAPIException`. ([4b068d0](https://github.com/jwilsson/spotify-web-api-php/commit/4b068d0f0e9951fbb0f938a5a8e95ead7813f53b))

## 2.3.0 (2018-06-05)
* Restored support for PHP 5.6. It's used by too many at this point and nothing PHP 7 specific is really used. ([ff3de71](https://github.com/jwilsson/spotify-web-api-php/commit/ff3de719dde9604aeb9e3647fd92adbba20f9130))
* Added a `Session::setRefreshToken()` method ([c30ebfe](https://github.com/jwilsson/spotify-web-api-php/commit/c30ebfed76963df046b0cacf1ae3f888f0e44bce))

## 2.2.1 (2018-05-05)
* When running behind a proxy, the first set of proxy response headers are now stripped. ([a9dfa55](https://github.com/jwilsson/spotify-web-api-php/commit/a9dfa559ded1584001b30d4518fee1ce84e21dc2))

## 2.2.0 (2018-03-01)
* Added `positions` support to `SpotifyWebAPI::deleteUserPlaylistTracks()`. ([4dfa494](https://github.com/jwilsson/spotify-web-api-php/commit/4dfa49476ddc6fe8a17435782112e081f5becd5a))
* Updated CA bundle. ([cd63d1a](https://github.com/jwilsson/spotify-web-api-php/commit/cd63d1adbc008d9e3d25360788dd139b5d0e8692))

## 2.1.1 (2018-02-02)
* Fixed an issue where a new refresh token wouldn't be properly updated in the `Session` class when a new one was returned from Spotify. ([2bf18e0](https://github.com/jwilsson/spotify-web-api-php/commit/2bf18e08201464416408c2e94f56e81e7df6553c))

## 2.1.0 (2017-12-06)
* Added the `Session::getScope()` method to check which scopes that are granted by the current user. ([f741511](https://github.com/jwilsson/spotify-web-api-php/commit/f741511fab6856d0f5eec05b4861dc0979c62e03))
* CI tests are now run on PHP `7.2`. ([3649e78](https://github.com/jwilsson/spotify-web-api-php/commit/3649e789ae89340b0de6b3050fca26a9ec6475c1))
* Improved authorization docs. ([14bb6e6](https://github.com/jwilsson/spotify-web-api-php/commit/14bb6e6fbc8bc723af6a18a5e7af53fe9532237d))

## 2.0.1 (2017-09-25)
* Updated CA bundle. ([d846e8c](https://github.com/jwilsson/spotify-web-api-php/commit/d846e8cded5822b87e3e6b3e0eb812aadf163554))

## 2.0.0 (2017-08-15)
* **Breaking** The following, deprecated, methods have been removed ([fdaa1bd](https://github.com/jwilsson/spotify-web-api-php/commit/fdaa1bd2fea4e1831221e3ce5c0cac20d59d7ce2)):
    * `Request::getReturnAssoc()` (use `Request::getReturnType()` instead)
    * `Request::setReturnAssoc()` (use `Request::setReturnType(Request::RETURN_ASSOC)` instead)
    * `SpotifyWebAPI::getReturnAssoc()` (use `SpotifyWebAPI::getReturnType()` instead)
    * `SpotifyWebAPI::setReturnAssoc()` (use `SpotifyWebAPI::setReturnType(SpotifyWebAPI::RETURN_ASSOC)` instead)
* **Breaking** The `$type` parameter in `SpotifyWebAPI::idToUri()` and `SpotifyWebAPI::uriToId()` no longer has a default value. ([fdcba71](https://github.com/jwilsson/spotify-web-api-php/commit/fdcba718b56f9fb2c84c41257bffa3d02680207f))
* **Breaking** Dropped support for PHP versions less than 7. ([71de436](https://github.com/jwilsson/spotify-web-api-php/commit/71de436bab8f1a8f6159eac68b277bcd605aa5c1))
* **Breaking** Tests are no longer run on HHVM, support for it was never documented anyway.  ([7b9ff5d](https://github.com/jwilsson/spotify-web-api-php/commit/7b9ff5d41021ceed509a47afb562018abb25fc93))
* Removed unused `$scope` parameter in `Session::requestCredentialsToken()`. ([582f983](https://github.com/jwilsson/spotify-web-api-php/commit/582f98358996d936102fe238f8b0339b5bdb25d9))
* Updated PHPUnit to `6.x`. ([49829ac](https://github.com/jwilsson/spotify-web-api-php/commit/49829acd6a16f949e59f071ad36eda8467f3f8b6))
* Updated CA bundle. ([ac09f96](https://github.com/jwilsson/spotify-web-api-php/commit/ac09f9698b90570dec018f595f2415c015b4b3cd))

## 1.12.0 (2017-07-26)
* Added the `SpotifyWebAPI::updateUserPlaylistImage()` method. ([14a9631](https://github.com/jwilsson/spotify-web-api-php/commit/14a9631b37ab45f6aaa7c819249e6d5529648940))

## 1.11.1 (2017-06-12)
* Fixed an issue where some URI IDs wouldn't be properly cast to an array. ([713e8e7](https://github.com/jwilsson/spotify-web-api-php/commit/713e8e794cf1a7964ba0055f783516ac6f446715))

## 1.11.0 (2017-06-09)
* All methods accepting Album, Artist, Playlist, Track, or User IDs can now also accept Spotify URIs.
    ([1a47fa1](https://github.com/jwilsson/spotify-web-api-php/commit/1a47fa143771d3148d6cda9b59a2d500ed540a1d),
    [e71daeb](https://github.com/jwilsson/spotify-web-api-php/commit/e71daebdc7204ed9d2c704e2f5bfe0798ae3da60),
    [63dde40](https://github.com/jwilsson/spotify-web-api-php/commit/63dde405829e7894f2c0ce659ac5cc09cfa48bb7),
    [4bf29b1](https://github.com/jwilsson/spotify-web-api-php/commit/4bf29b13f64819513cd573cd86ce19ccd321ac40))
* Corrected `SpotifyWebAPI::getMySavedTracks` example. ([0eedf1c](https://github.com/jwilsson/spotify-web-api-php/commit/0eedf1cfbd6211eb41b99aedd71dabc9901d47b2))
* Updated `PHP_CodeSniffer` to `3.x`. ([60adb2c](https://github.com/jwilsson/spotify-web-api-php/commit/60adb2cb05b7adeccc271faeb8d6cceb6f949288))
* Travis builds now uses Trusty as the distribution. ([011524b](https://github.com/jwilsson/spotify-web-api-php/commit/011524b46c44c98b67bdd5930f534d40cc19804c))

## 1.10.1 (2017-04-29)
* Updated CA bundle. ([ff8d87e](https://github.com/jwilsson/spotify-web-api-php/commit/ff8d87eabbffc3e3c1e4e5d9145faf2ef1ef4932))
* Corrected the name of some Markdown example files. ([d6425f6](https://github.com/jwilsson/spotify-web-api-php/commit/d6425f610bfd377a4156a421f1246b50e57690ae))
* Corrected `SpotifyWebAPI::play()` example. ([ce2c08c](https://github.com/jwilsson/spotify-web-api-php/commit/ce2c08c90ca8d0fa420d15790dceb40ebb9f1297))
* Corrected inline method docs. ([d725d16](https://github.com/jwilsson/spotify-web-api-php/commit/d725d16a8726b19cc51da42557b97d00f4f52395))
* Removed stray `SpotifyWebApi` object in examples. ([7ef922b](https://github.com/jwilsson/spotify-web-api-php/commit/7ef922bf2fca35b0601578c51f870d481f5762d5))

## 1.10.0 (2017-04-12)
* Added Spotify Connect endpoints:
    * `SpotifyWebAPI::changeMyDevice()` ([21dd887](https://github.com/jwilsson/spotify-web-api-php/commit/21dd887271ba7c905fd2df0ea0f600421ef74baf))
    * `SpotifyWebAPI::changeVolume()` ([e9cdd79](https://github.com/jwilsson/spotify-web-api-php/commit/e9cdd797384559f83734626076cddecb15a195db))
    * `SpotifyWebAPI::getMyCurrentPlaybackInfo()` ([61f4cbd](https://github.com/jwilsson/spotify-web-api-php/commit/61f4cbd282cf3d89c8b135ad9a4eef6a07b9d5ff))
    * `SpotifyWebAPI::getMyCurrentTrack()` ([0f30a6b](https://github.com/jwilsson/spotify-web-api-php/commit/0f30a6b725f8f538e5eae8c893904e2045554881))
    * `SpotifyWebAPI::getMyDevices()` ([8b33f9d](https://github.com/jwilsson/spotify-web-api-php/commit/8b33f9d64f29aabb7ffd910ce8e09b46e043e2e4))
    * `SpotifyWebAPI::next()` ([9950c51](https://github.com/jwilsson/spotify-web-api-php/commit/9950c51790ba2ff05d0fb4e7360d6d745fd9ae1b))
    * `SpotifyWebAPI::pause()` ([b724c4a](https://github.com/jwilsson/spotify-web-api-php/commit/b724c4aefa6db88b397d7d19e24dae902f0d287c))
    * `SpotifyWebAPI::play()` ([825a632](https://github.com/jwilsson/spotify-web-api-php/commit/825a632fda5a8dadfe5ce5e782d768f0e7044c08))
    * `SpotifyWebAPI::previous()` ([90a97e1](https://github.com/jwilsson/spotify-web-api-php/commit/90a97e1d1b294d53a3a629febea78ba9eafb373a))
    * `SpotifyWebAPI::repeat()` ([1feebfe](https://github.com/jwilsson/spotify-web-api-php/commit/1feebfe365a140475f12d22151ec9fe5c4a11fe9))
    * `SpotifyWebAPI::seek()` ([0641a07](https://github.com/jwilsson/spotify-web-api-php/commit/0641a07cd79451681882273b630ff6313c570dbe))
    * `SpotifyWebAPI::shuffle()` ([d43268c](https://github.com/jwilsson/spotify-web-api-php/commit/d43268c4178cf1131eeed6de521f7bdf29a4b560))
* Complete documentation revamp. ([82d9fab](https://github.com/jwilsson/spotify-web-api-php/commit/82d9fabfc3f620068a114f55eff3a0e0803ff1a3))
* Made sure empty objects are correctly serialized to JSON objects instead of JSON arrays. ([b17682e](https://github.com/jwilsson/spotify-web-api-php/commit/b17682e6a1cf25c25c87a2a900cf6858a9c038b7))
## 1.9.0 (2017-03-24)
* Added the `SpotifyWebAPI::getMyRecentTracks()` method. ([4df889f](https://github.com/jwilsson/spotify-web-api-php/commit/4df889f2aa44c171d492f2784b45fd1155429b57))

## 1.8.0 (2017-03-05)
* Added the `SpotifyWebAPI::getMyRecentTracks()` method. ([fd8ea0d](https://github.com/jwilsson/spotify-web-api-php/commit/fd8ea0d70d690bbb0072a917530eba2b8c02e2a1))

## 1.7.0 (2017-02-25)
* The following methods can now also accept Spotify URIs:
    * `SpotifyWebAPI::addMyAlbums()` ([eecaea4](https://github.com/jwilsson/spotify-web-api-php/commit/eecaea4f8fe5d6554104da1018ba39002889c873))
    * `SpotifyWebAPI::addMyTracks()` ([1b63d90](https://github.com/jwilsson/spotify-web-api-php/commit/1b63d907d9127bba7ad013ce1fdf0617d894cd95))
    * `SpotifyWebAPI::deleteMyAlbums()` ([eecaea4](https://github.com/jwilsson/spotify-web-api-php/commit/eecaea4f8fe5d6554104da1018ba39002889c873))
    * `SpotifyWebAPI::deleteMyTracks()` ([eecaea4](https://github.com/jwilsson/spotify-web-api-php/commit/eecaea4f8fe5d6554104da1018ba39002889c873))
    * `SpotifyWebAPI::myAlbumsContains()` ([beb48e2](https://github.com/jwilsson/spotify-web-api-php/commit/beb48e2e397391a90129570ee1556347af70a95f))
    * `SpotifyWebAPI::myTracksContains()` ([edd72d7](https://github.com/jwilsson/spotify-web-api-php/commit/edd72d77ea834fff15c716e5f085e0d058966e0d))
* PHPUnit 5 is now used whenever possible. ([9892fe4](https://github.com/jwilsson/spotify-web-api-php/commit/9892fe481dd3193d719a251d43ce429d40202df8))

## 1.6.1 (2017-01-28)
* Bump for bad `1.6.0`.

## 1.6.0 (2017-01-28)
* Deprecated the following methods and replaced them with ([6aac5c6](https://github.com/jwilsson/spotify-web-api-php/commit/6aac5c6880017e0fadf7a48c5ba740dad2d9e617)):
    * `Request::getReturnAssoc()` -> `Request::getReturnType()`
    * `Request::setReturnAssoc()` -> `Request::setReturnType(Request::RETURN_ASSOC)`
    * `SpotifyWebAPI::getReturnAssoc()` -> `SpotifyWebAPI::getReturnType()`
    * `SpotifyWebAPI::setReturnAssoc()` -> `SpotifyWebAPI::setReturnType(SpotifyWebAPI::RETURN_ASSOC)`
* Added the following constants for use with `setReturnType()` ([6aac5c6](https://github.com/jwilsson/spotify-web-api-php/commit/6aac5c6880017e0fadf7a48c5ba740dad2d9e617)):
    * `Request::RETURN_ASSOC`
    * `Request::RETURN_OBJECT`
    * `SpotifyWebAPI::RETURN_ASSOC`
    * `SpotifyWebAPI::RETURN_OBJECT`
* Added docs on how to change the return type. ([10b47b5](https://github.com/jwilsson/spotify-web-api-php/commit/10b47b5cb4662ba53d45590cf39f9482a6dcb51e))

## 1.5.0 (2016-12-11)
* Added a `Request::getLastResponse()` method. ([21b72b0](https://github.com/jwilsson/spotify-web-api-php/commit/21b72b040ec10550649ded9050a431f890081f08))
* Added a `SpotifyWebAPI::getRequest` method.
([bab8924](https://github.com/jwilsson/spotify-web-api-php/commit/bab8924b1636e7d19f45722add8a0b769818983d))
* The `$tracks` option for `SpotifyWebAPI::deleteUserPlaylistTracks()` now also supports objects. ([ce230e7](https://github.com/jwilsson/spotify-web-api-php/commit/ce230e7c9c850ebe2837924bf0808ae5bb7a26af))
* Response compression will now be automatically negotiated by the client and server. ([3f4a643](https://github.com/jwilsson/spotify-web-api-php/commit/3f4a6434acb6bbcafe20d85bf09a74e0af2c403f))
* Made sure `SpotifyWebAPI::getAlbums()` can handle objects for the `$options` argument properly. ([42cf5d0](https://github.com/jwilsson/spotify-web-api-php/commit/42cf5d0345be270431156d270239d7538f0d2c82))
* Replaced `for`-loops with `array_map()`. ([cfc32b7](https://github.com/jwilsson/spotify-web-api-php/commit/cfc32b75226678274d39f631c27d80bcfd4941ec))
* CI tests are run on PHP 7.1. ([74cb084](https://github.com/jwilsson/spotify-web-api-php/commit/74cb084a24195ca24461587aa8977dda92f63dd2))
* Added documentation on error handling. ([57ba164](https://github.com/jwilsson/spotify-web-api-php/commit/57ba164ee15b6289358eec4998dff7796e7162f0))
* Fixed a typo in the `SpotifyWebAPI::reorderUserPlaylistTracks()` docs. ([b25dec4](https://github.com/jwilsson/spotify-web-api-php/commit/b25dec43039abbb57144a0ab6a2c45f5ac722c02))
* Fixed a typo in the `SpotifyWebAPI::getLastResponse()` docs. ([bdd3ecc](https://github.com/jwilsson/spotify-web-api-php/commit/bdd3ecc393ff83bc2d4af983c363cdaddb1b544b))

## 1.4.2 (2016-10-27)
* Array indexes in `SpotifyWebAPI::idToUri()` are now always reset to prevent undefined offset errors. ([ae8bd96](https://github.com/jwilsson/spotify-web-api-php/commit/ae8bd9673795747fad40ff4caf6b12f17c045fc5))

## 1.4.1 (2016-10-25)
* All requests will now be compressed using gzip. ([5eeabde](https://github.com/jwilsson/spotify-web-api-php/commit/5eeabde90d1c21832384f42d96c1208ce6fda287))

## 1.4.0 (2016-10-06)
* Marked `SpotifyWebAPI` class properties as `protected` instead of `private` to allow extending. ([f52468a](https://github.com/jwilsson/spotify-web-api-php/commit/f52468a7f68895dfad264675bf4274b0c272cfb2))
* Marked `Session` class properties as `protected` instead of `private` to allow extending. ([13e6d53](https://github.com/jwilsson/spotify-web-api-php/commit/13e6d536416717f999346caa96510183a5b82020))
* Marked `Request` class properties as `protected` instead of `private` to allow extending. ([be2b3f6](https://github.com/jwilsson/spotify-web-api-php/commit/be2b3f618b3a4aab7e6b12fa329c87d936675bb8))
* Moved docs from the `gh-pages` branch into `master`. ([7f638a1](https://github.com/jwilsson/spotify-web-api-php/commit/7f638a107c214c8b30319a230f7e16d2ac2a64a3))

## 1.3.4 (2016-09-23)
* Fixed a typo in the `Request::parseBody()` method added in `1.3.3`. ([13d3b94](https://github.com/jwilsson/spotify-web-api-php/commit/13d3b9417f0dc6de959867281ae0e4c9392f9c8d))

## 1.3.3 (2016-09-06)
* Moved the `Request` body parsing to its own method. ([ef60829](https://github.com/jwilsson/spotify-web-api-php/commit/ef608297271f0734a3a18b7a5e6ba40c1f41aa7a))
* All arrays are now using the short array syntax. ([Full diff](https://github.com/jwilsson/spotify-web-api-php/compare/5aa7ad833cf3bb7f0632e4cbe31d1d7898e6ca55...edfb711ec51ec9e76665f3e1bd53259ab9ea5a0e))
* Travis tests are now running on PHP nightlies as well. ([0cb8420](https://github.com/jwilsson/spotify-web-api-php/commit/0cb84209f0a7168392ace79db9ca68770f3f8c6d))
* Updated the inline `Request` docs for consistency. ([cf09e09](https://github.com/jwilsson/spotify-web-api-php/commit/cf09e0914aea66f6be192e1bc5fd3639dafcc399))

## 1.3.2 (2016-05-30)
* Improved the handling of `seed_*` parameters in `SpotifyWebAPI::getRecommendations()`. ([e6603dc](https://github.com/jwilsson/spotify-web-api-php/commit/e6603dc700c1105d10a25b3496b4e95f7238213f))
* Specified better Composer PHP version ranges so we don't break when a new major PHP versions is released. ([8dd7749](https://github.com/jwilsson/spotify-web-api-php/commit/8dd7749c331e0f035bbd9c5b7a2231875a0d6266))
* Fixed some minor code style issues in the tests. ([de5f7a8](https://github.com/jwilsson/spotify-web-api-php/commit/de5f7a897ae6755640317f834a8a19cd309524f5))

## 1.3.1 (2016-04-03)
* Fixed an issue where empty error responses weren't correctly handled. ([5f87cc5](https://github.com/jwilsson/spotify-web-api-php/commit/5f87cc56e4d6ae0c722c514423af5ee2c9c42b26))
* Fixed an issue where auth call exceptions would sometimes use the wrong message value. ([1b7951c](https://github.com/jwilsson/spotify-web-api-php/commit/1b7951c3aeb56dc83b84b0aac95a8ea0598ea8ec))

## 1.3.0 (2016-03-29)
* The following methods have been added:
    * `SpotifyWebAPI::getGenreSeeds()` ([88b750d](https://github.com/jwilsson/spotify-web-api-php/commit/88b750d7ec0879e54c37020f93310dccbdeec421))
    * `SpotifyWebAPI::getRecommendations()` ([28b7897](https://github.com/jwilsson/spotify-web-api-php/commit/28b7897820dd360a682a60b15426290137b9719f))
    * `SpotifyWebAPI::getMyTop()` ([edcafff](https://github.com/jwilsson/spotify-web-api-php/commit/edcafff3e1e465be5cccde477e97a4c4da49c643))
    * `SpotifyWebAPI::getAudioFeatures()` ([0759b29](https://github.com/jwilsson/spotify-web-api-php/commit/0759b2942b515d56dff5ab674e5564cae018234d))
* Minor inline docs updates ([745f117](https://github.com/jwilsson/spotify-web-api-php/commit/745f117e9163ee04634e0d5e1fa065cc1102108e), [35e9f57](https://github.com/jwilsson/spotify-web-api-php/commit/35e9f5755ffb889f00bf2ce414c14c8e077aee23), [50f040c](https://github.com/jwilsson/spotify-web-api-php/commit/50f040ce73644896578a698e4a968f8b5494949f))

## 1.2.0 (2015-12-01)
* The following methods have been added:
    * `SpotifyWebAPI::getMyPlaylists()` ([ea8f0a2](https://github.com/jwilsson/spotify-web-api-php/commit/ea8f0a2c23fb6bc4e496b6fb6885b5517626860f))
* Updated CA bundle. ([e6161fd](https://github.com/jwilsson/spotify-web-api-php/commit/e6161fd81d9851799315eb175a95ca8c001f31d3))

## 1.1.0 (2015-11-24)
* The following methods have been added:
    * `SpotifyWebAPI::addMyAlbums()` ([0027122](https://github.com/jwilsson/spotify-web-api-php/commit/0027122fe543ec9c3df9db3543be86683c7cd0d1))
    * `SpotifyWebAPI::deleteMyAlbums()` ([1d52172](https://github.com/jwilsson/spotify-web-api-php/commit/1d5217219095e0dded3f3afe300f72b91443d510))
    * `SpotifyWebAPI::getMySavedAlbums()` ([1bea486](https://github.com/jwilsson/spotify-web-api-php/commit/1bea4865d8323fa49d5b9f4ba4edc4cb68299115))
    * `SpotifyWebAPI::myAlbumsContains()` ([6f4ecfc](https://github.com/jwilsson/spotify-web-api-php/commit/6f4ecfc5ae929768f235367cf6deb259c8e75561))

## 1.0.0 (2015-10-13)
* **This release contains breaking changes, read through this list before updating.**
* The following, deprecated, methods have been removed:
    * `Session::refreshToken()` ([4d46e8c](https://github.com/jwilsson/spotify-web-api-php/commit/4d46e8ce5cda30924fb7afaa9886434a9a6e5c3c))
    * `Session::requestToken()` ([4d46e8c](https://github.com/jwilsson/spotify-web-api-php/commit/4d46e8ce5cda30924fb7afaa9886434a9a6e5c3c))
    * `SpotifyWebAPI::deletePlaylistTracks()` ([4d46e8c](https://github.com/jwilsson/spotify-web-api-php/commit/4d46e8ce5cda30924fb7afaa9886434a9a6e5c3c))
    * `SpotifyWebAPI::reorderPlaylistTracks()` ([4d46e8c](https://github.com/jwilsson/spotify-web-api-php/commit/4d46e8ce5cda30924fb7afaa9886434a9a6e5c3c))
    * `SpotifyWebAPI::replacePlaylistTracks()` ([4d46e8c](https://github.com/jwilsson/spotify-web-api-php/commit/4d46e8ce5cda30924fb7afaa9886434a9a6e5c3c))
* Added docs for the `market` parameter to the following methods:
    * `SpotifyWebAPI::getAlbums()` ([b83a131](https://github.com/jwilsson/spotify-web-api-php/commit/b83a1312a18039ba097c631194a01cef074f5f38))
    * `SpotifyWebAPI::getAlbumTracks()` ([c0a24d5](https://github.com/jwilsson/spotify-web-api-php/commit/c0a24d57cd15176df725ae8ea4217204a89c7ff8))
    * `SpotifyWebAPI::getMySavedTracks()` ([06ef152](https://github.com/jwilsson/spotify-web-api-php/commit/06ef15289c9533ce0d1a40e58821ae55aa4078da))
    * `SpotifyWebAPI::getTrack()` ([b48c2ff](https://github.com/jwilsson/spotify-web-api-php/commit/b48c2ff0e82603fefa37451cd83b317d78c2f11b))
    * `SpotifyWebAPI::getTracks()` ([ad7430a](https://github.com/jwilsson/spotify-web-api-php/commit/ad7430a6d91aa58eaace67e761623dffc43b6cdb))
    * `SpotifyWebAPI::getUserPlaylist()` ([a32ee7c](https://github.com/jwilsson/spotify-web-api-php/commit/a32ee7c2de48546f6a1b964ee7b379735e252cf2))
    * `SpotifyWebAPI::getUserPlaylistTracks()` ([0c104e8](https://github.com/jwilsson/spotify-web-api-php/commit/0c104e87db7076cbb363cd35ac8a307655c1c1c2))
* `Session::setRefreshToken()` has been removed, a refresh token is now passed directly to `Session::refreshAccessToken()` instead. ([62e7383](https://github.com/jwilsson/spotify-web-api-php/commit/62e7383d6cf732ff6c0fc4393711e29f1b12c69f))
* `Session::getExpires()` has been removed and `Session::getTokenExpiration()` has been added instead, returning the exact token expiration time. ([62e7383](https://github.com/jwilsson/spotify-web-api-php/commit/62e7383d6cf732ff6c0fc4393711e29f1b12c69f))
* The minimum required PHP version has been increased to 5.5 and support for PHP 7 has been added. ([b68ae3b](https://github.com/jwilsson/spotify-web-api-php/commit/b68ae3b524f462f3d3f0435617dd0cb21555a693), [6a8ac8d](https://github.com/jwilsson/spotify-web-api-php/commit/6a8ac8d309c4e6fbc076cb85897681fdb00f7a20))
* HTTP response headers returned by `Request::send()` and `SpotifyWebAPI::getLastResponse()` are now parsed to an array. ([9075bd3](https://github.com/jwilsson/spotify-web-api-php/commit/9075bd3289f02cee9b23ad596e308ad33dae0076))
* In `SpotifyWebAPI::deleteUserPlaylistTracks()`, `position` has been renamed to `positions` (note the extra "s"). This change was made to better align with the official Spotify docs. ([09f2636](https://github.com/jwilsson/spotify-web-api-php/commit/09f26369dc4c5f22ba8aee81cd858b9eb3584209))
* The `positions` argument to `SpotifyWebAPI::deleteUserPlaylistTracks()` now also accept `int`s. ([09f2636](https://github.com/jwilsson/spotify-web-api-php/commit/09f26369dc4c5f22ba8aee81cd858b9eb3584209))
* `SpotifyWebAPI::getArtistTopTracks()` now accepts an array of options. ([79543ac](https://github.com/jwilsson/spotify-web-api-php/commit/79543ac51850b91b4bf90a92c3482575524d0505))
* `Session::getAuthorizeUrl()` no longer sends empty query strings. ([c3e83e8](https://github.com/jwilsson/spotify-web-api-php/commit/c3e83e857560a299480ba7a41940835a0543c758))
* Stopped `SpotifyWebAPI::deleteUserPlaylistTracks()` from sending internal, leftover data. ([09f2636](https://github.com/jwilsson/spotify-web-api-php/commit/09f26369dc4c5f22ba8aee81cd858b9eb3584209))
* Clarified docs for `SpotifyWebAPI::followPlaylist()` and `SpotifyWebAPI::reorderUserPlaylistTracks()`. ([09f2636](https://github.com/jwilsson/spotify-web-api-php/commit/09f26369dc4c5f22ba8aee81cd858b9eb3584209))
* Fixed an issue where `SpotifyWebAPI::reorderUserPlaylistTracks()` couldn't reorder the first track. ([748592e](https://github.com/jwilsson/spotify-web-api-php/commit/748592ee7cc5a59f992d0ed0d49c1937931643cd))
* Better tests and coverage. ([09f2636](https://github.com/jwilsson/spotify-web-api-php/commit/09f26369dc4c5f22ba8aee81cd858b9eb3584209))

## 0.10.0 (2015-09-05)
* The following methods have been added:
    * `SpotifyWebAPI::getUserFollowedArtists()` ([b7142fa](https://github.com/jwilsson/spotify-web-api-php/commit/b7142fa466c307b56f285ab2aef546ecb8f998e2))

## 0.9.0 (2015-07-06)
* **This release contains breaking changes, read through this list before updating.**
* As we're moving closer to 1.0 the work to make the API more consistent and stable is continuing. This time with an effort to make method names and signatures more consistent.
* Thus, the following methods have been renamed and the old names are deprecated:
    * `SpotifyWebAPI::deletePlaylistTracks()` -> `SpotifyWebAPI::deleteUserPlaylistTracks()` ([8768328](https://github.com/jwilsson/spotify-web-api-php/commit/8768328aeeca1a82ebf652ad0ee557329ded6783))
    * `SpotifyWebAPI::reorderPlaylistTracks` -> `SpotifyWebAPI::reorderUserPlaylistTracks()` ([2ce8fc5](https://github.com/jwilsson/spotify-web-api-php/commit/2ce8fc51cc2a42d6b9055bc6ced1a0f777400486))
    * `SpotifyWebAPI::replacePlaylistTracks()` -> `SpotifyWebAPI::replaceUserPlaylistTracks()` ([6362510](https://github.com/jwilsson/spotify-web-api-php/commit/6362510344f746a37a75612d3f41030a60d81f2d))
* The following method arguments now also accepts strings:
    * `fields` in `SpotifyWebAPI::getUserPlaylistTracks()`. ([7a3c200](https://github.com/jwilsson/spotify-web-api-php/commit/7a3c200fb07ebcf11b60c5d778bbc4792855a5b9))
    * `fields` in `SpotifyWebAPI::getUserPlaylist()`. ([80cd7d0](https://github.com/jwilsson/spotify-web-api-php/commit/80cd7d08a8983a0519510445f122846d4939893d))
    * `album_type` in `SpotifyWebAPI::getArtistAlbums()`. ([4af0a53](https://github.com/jwilsson/spotify-web-api-php/commit/4af0a539df9b18550f6a7df337a07038775a5bed))
    * `ids` in `SpotifyWebAPI::userFollowsPlaylist()`. ([9cc11bb](https://github.com/jwilsson/spotify-web-api-php/commit/9cc11bba082e4accea0364d97a1c8486a9634971))
* A new method, `SpotifyWebAPI::getLastResponse()` has been introduced which allows for retrieval of the latest full response from the Spotify API. ([9b54074](https://github.com/jwilsson/spotify-web-api-php/commit/9b54074eb7ff3e223c1015580fb2dd975351975b))
* Lots of internal changes to increase code consistency and ensure full PSR-2 compatibility. ([2b8fda3](https://github.com/jwilsson/spotify-web-api-php/commit/2b8fda341176dddb8c9d4ef8ec808071efc54f49))
* Better handling of errors from cURL. ([c7b5529](https://github.com/jwilsson/spotify-web-api-php/commit/c7b5529cdac854de81fe87c79da5b318af15ca6a))

## 0.8.2 (2015-05-02)
* CA Root Certificates are now included with the library, allowing cURL to always find it. ([4ebee9b](https://github.com/jwilsson/spotify-web-api-php/commit/4ebee9b1b2ce53e622ace071f319e882d7c94cef))

## 0.8.1 (2015-03-29)
* Fixed an issue where `SpotifyWebAPI::updateUserPlaylist()` would fail without `name` set. ([39232f5](https://github.com/jwilsson/spotify-web-api-php/commit/39232f52c7efe090695dbf26e7dff1e1841db035))

## 0.8.0 (2015-03-22)
* **This release contains breaking changes, read through this list before updating.**
* The following methods have been renamed:
    * `Session::refreshToken()` -> `Session::refreshAccessToken()` ([7b6f31a](https://github.com/jwilsson/spotify-web-api-php/commit/7b6f31af4db435f1d3a94bef5758bdf3e864c65a))
    * `Session::requestToken()` -> `Session::requestAccessToken()` ([98c4a2a](https://github.com/jwilsson/spotify-web-api-php/commit/98c4a2a5b58e939bcfeba6ed72d07776c717698a))
* The following methods have been added:
    * `SpotifyWebAPI::currentUserFollows()` ([6dbab19](https://github.com/jwilsson/spotify-web-api-php/commit/6dbab19c39713126fa5172e959e157506a067f6d))
    * `SpotifyWebAPI::followArtistsOrUsers()` ([6dbab19](https://github.com/jwilsson/spotify-web-api-php/commit/6dbab19c39713126fa5172e959e157506a067f6d))
    * `SpotifyWebAPI::followPlaylist()` ([12ff351](https://github.com/jwilsson/spotify-web-api-php/commit/12ff3511deb732dbda11d547164eec34c5f47243))
    * `SpotifyWebAPI::getCategoriesList()` ([f09b4b8](https://github.com/jwilsson/spotify-web-api-php/commit/f09b4b8e9edcfe43cfad082123d49c5e2bbae873))
    * `SpotifyWebAPI::getCategory()` ([f09b4b8](https://github.com/jwilsson/spotify-web-api-php/commit/f09b4b8e9edcfe43cfad082123d49c5e2bbae873))
    * `SpotifyWebAPI::getCategoryPlaylists()` ([f09b4b8](https://github.com/jwilsson/spotify-web-api-php/commit/f09b4b8e9edcfe43cfad082123d49c5e2bbae873))
    * `SpotifyWebAPI::reorderPlaylistTracks()` ([0744904](https://github.com/jwilsson/spotify-web-api-php/commit/07449042143a87a5f8b0d73086c803bc4073407d))
    * `SpotifyWebAPI::unfollowArtistsOrUsers()` ([6dbab19](https://github.com/jwilsson/spotify-web-api-php/commit/6dbab19c39713126fa5172e959e157506a067f6d))
    * `SpotifyWebAPI::unfollowPlaylist()` ([12ff351](https://github.com/jwilsson/spotify-web-api-php/commit/12ff3511deb732dbda11d547164eec34c5f47243))
    * `SpotifyWebAPI::userFollowsPlaylist()` ([4293919](https://github.com/jwilsson/spotify-web-api-php/commit/42939192801bf69f915093f5d997ceab7599f8f9))
* The `$redirectUri` argument in `Session::__construct()` is now optional. ([8591ea8](https://github.com/jwilsson/spotify-web-api-php/commit/8591ea8f60373be953dceb41949bfc70aa1663c3))

## 0.7.0 (2014-12-06)
* The following methods to control the return type of all API methods were added:
    * `Request::getReturnAssoc()` ([b95bf3f](https://github.com/jwilsson/spotify-web-api-php/commit/b95bf3f3e4f702486e1de36633b131531b4a0546))
    * `Request::setReturnAssoc()` ([b95bf3f](https://github.com/jwilsson/spotify-web-api-php/commit/b95bf3f3e4f702486e1de36633b131531b4a0546))
    * `SpotifyWebAPI::getReturnAssoc()` ([b95bf3f](https://github.com/jwilsson/spotify-web-api-php/commit/b95bf3f3e4f702486e1de36633b131531b4a0546))
    * `SpotifyWebAPI::setReturnAssoc()` ([b95bf3f](https://github.com/jwilsson/spotify-web-api-php/commit/b95bf3f3e4f702486e1de36633b131531b4a0546))
* Added `fields` option to `SpotifyWebAPI::getUserPlaylist()`. ([c35e44d](https://github.com/jwilsson/spotify-web-api-php/commit/c35e44db2151e246a8b847653a2210d284125f7b))
* All methods now automatically send authorization headers (if a access token is supplied), increasing rate limits. ([a5e95a9](https://github.com/jwilsson/spotify-web-api-php/commit/a5e95a9015c076bfb30ca14336b6ca7f3a764e41))
* Lots of inline documentation improvements.

## 0.6.0 (2014-10-26)
* **This release contains breaking changes, read through this list before updating.**
* All static methods on `Request` have been removed. `Request` now needs to be instantiated before using. ([59207ac](https://github.com/jwilsson/spotify-web-api-php/commit/59207ac5705e8b43c1687b2e371e8133ddcf02fe))
* All methods that accepted the `limit` option now uses the correct Spotify default value if nothing has been specified. ([a291018](https://github.com/jwilsson/spotify-web-api-php/commit/a29101830b019e6acee0d03e1f11813a4a4a7a1b))
* It's now possible to specify your own `Request` object in `SpotifyWebAPI` and `Session` constructors. ([59207ac](https://github.com/jwilsson/spotify-web-api-php/commit/59207ac5705e8b43c1687b2e371e8133ddcf02fe))
* `SpotifyWebAPI::getArtistAlbums()` now supports the `album_type` option. ([1bd7014](https://github.com/jwilsson/spotify-web-api-php/commit/1bd7014f4d27d836e90128bf1c72dedcd7814645))
* `Request::send()` will only modify URLs when needed. ([0241f3b](https://github.com/jwilsson/spotify-web-api-php/commit/0241f3bf5c06dfb7a8ea0cd17f89d3ea06bb0688))

## 0.5.0 (2014-10-25)
* The following methods have been added:
    * `Session::getExpires()` ([c9c6da6](https://github.com/jwilsson/spotify-web-api-php/commit/c9c6da69333e74d8c8ae755998be8076e5e2deee))
    * `Session::getRefreshToken()` ([0d21147](https://github.com/jwilsson/spotify-web-api-php/commit/0d21147376196ab794d534197bc20227d67b6d14))
    * `Session::setRefreshToken()` ([ff83455](https://github.com/jwilsson/spotify-web-api-php/commit/ff83455439200f806eadc20d28e51b9d34502d78))
    * `SpotifyWebAPI::getFeaturedPlaylists()` ([c99537a](https://github.com/jwilsson/spotify-web-api-php/commit/c99537a907b802cfa5ee70b976ffe2f6e8135e6b))
    * `SpotifyWebAPI::getNewReleases()` ([7a8533c](https://github.com/jwilsson/spotify-web-api-php/commit/7a8533c0b0f8012cc84e360c8d472fce20a2fc48))
* The following options has been added:
    * `offset` and `limit` to `SpotifyWebAPI::getUserPlaylists()` ([3346857](https://github.com/jwilsson/spotify-web-api-php/commit/3346857ae82e8895741621d283ea57749ec9da48))
    * `offset` and `limit` to `SpotifyWebAPI::getUserPlaylistTracks()` ([1660600](https://github.com/jwilsson/spotify-web-api-php/commit/1660600fb35481e86a2ea8bd4bb915c0942b452a))
    * `fields` to `SpotifyWebAPI::getUserPlaylistTracks()` ([9a61003](https://github.com/jwilsson/spotify-web-api-php/commit/9a61003e904ec4b906487c28c91f1c0306d6ae0a))
    * `market` to `SpotifyWebAPI::getArtistAlbums()` ([98194dd](https://github.com/jwilsson/spotify-web-api-php/commit/98194dddd0e2e7f88f9b98429845c3d251afcbed))
    * `market` to `SpotifyWebAPI::search()` ([8883e79](https://github.com/jwilsson/spotify-web-api-php/commit/8883e799f997d477aa1b1c7ea44451c9087fb90b))
* Better handling of HTTP response codes in `Request::send()`. ([351be62](https://github.com/jwilsson/spotify-web-api-php/commit/351be62d3246dbd3beee2015a767d95ae6330e0a))
* Fixed a bug where `SpotifyWebAPIException` messages weren't correctly set. ([c764894](https://github.com/jwilsson/spotify-web-api-php/commit/c764894c4ab1e2fe7e872bcb1dc9670fdcde9135))
* Fixed various issues related to user playlists. ([9929d45](https://github.com/jwilsson/spotify-web-api-php/commit/9929d45c4dba49b3f76aa6ca0fde61ed4857a223))

## 0.4.0 (2014-09-01)
* **This release contains lots of breaking changes, read through this list before updating.**
* All methods which previously required a Spotify URI now just needs an ID. ([f1f14bd](https://github.com/jwilsson/spotify-web-api-php/commit/f1f14bd2ed0a77e1a6fdbee7091319c33cbfc634))
* `deletePlaylistTrack()` has been renamed to `deletePlaylistTracks()`. ([e54d703](https://github.com/jwilsson/spotify-web-api-php/commit/e54d703bd94d62a64058898e7d6cddf096b5a86a))
* When something goes wrong, a `SpotifyWebAPIException` is thrown. ([d98bb8a](https://github.com/jwilsson/spotify-web-api-php/commit/d98bb8aca96a73eb3495c3d84f5884117599d648))
* The `SpotifyWebAPI` methods are no longer static, you'll need to instantiate the class now. ([67c4e8b](https://github.com/jwilsson/spotify-web-api-php/commit/67c4e8ba1ce9e7f3bdd2d7acd6785e40a0949a4e))

## 0.3.0 (2014-08-23)
* The following methods have been added:
    * `SpotifyWebAPI::getMySavedTracks()` ([30c865d](https://github.com/jwilsson/spotify-web-api-php/commit/30c865d40771417646391bdd843dc1c7f5494c15))
    * `SpotifyWebAPI::myTracksContains()` ([3f99367](https://github.com/jwilsson/spotify-web-api-php/commit/3f9936710f1f1bdd11ea1cb36c87f101f94e0781))
    * `SpotifyWebAPI::addMyTracks()` ([20d80ef](https://github.com/jwilsson/spotify-web-api-php/commit/20d80efe183e5c484642d821eb37a6a53443f660))
    * `SpotifyWebAPI::deleteMyTracks()` ([ee17c69](https://github.com/jwilsson/spotify-web-api-php/commit/ee17c69b8d56c9466cfaac22d2243487dd3eff8c))
    * `SpotifyWebAPI::updateUserPlaylist()` ([5d5874d](https://github.com/jwilsson/spotify-web-api-php/commit/5d5874dd565e8156e123aed94f607eace3f28fb4))
    * `SpotifyWebAPI::deletePlaylistTrack()` ([3b17104](https://github.com/jwilsson/spotify-web-api-php/commit/3b1710494ce04ddae69b6edbccddc1b3530ca0fb))
    * `SpotifyWebAPI::deletePlaylistTrack()` ([3b5e23a](https://github.com/jwilsson/spotify-web-api-php/commit/3b5e23a30460ed4235259b23ff20eb1d0a87a43b))
* Added support for the Client Credentials Authorization Flow. ([0892e59](https://github.com/jwilsson/spotify-web-api-php/commit/0892e59022a15c79f6222ec82f596ca24af8fca3))
* Added support for more HTTP methods in `Request::send()`. ([d4df8c1](https://github.com/jwilsson/spotify-web-api-php/commit/d4df8c10f4f9f94ad4e0f2241bcbf0be0a81dede))

## 0.2.0 (2014-07-26)
* The following methods have been added:
    * `SpotifyWebAPI::getArtistRelatedArtists()` ([5a3ea0e](https://github.com/jwilsson/spotify-web-api-php/commit/5a3ea0e203d9b0285b1a671533aa64f81172eb49))
* Added `offset` and `limit` options for `SpotifyWebAPI::getAlbumTracks()` and `SpotifyWebAPI::getArtistAlbums()`. ([21c98ec](https://github.com/jwilsson/spotify-web-api-php/commit/21c98ec57f1714192d40b3f0736b3974cf1432f5), [8b0c417](https://github.com/jwilsson/spotify-web-api-php/commit/8b0c4170be46dcb6db25f942f264fa6fc77ac7fe))
* Replaced PSR-0 autoloading with PSR-4 autoloading. ([40878a9](https://github.com/jwilsson/spotify-web-api-php/commit/40878a93fcf158971d4d3674eeed7c44e44d1b97))
* Changed method signature of `Session::getAuthorizeUrl()` and added `show_dialog` option. ([8fe7a6e](https://github.com/jwilsson/spotify-web-api-php/commit/8fe7a6e5ada1c2195fdedfd560cb98cf7a422355), [57c36af](https://github.com/jwilsson/spotify-web-api-php/commit/57c36af84644393c801c86ca6542f4ab71d1eaf7))
* Added missing returns for `SpotifyWebAPI::getUserPlaylist()` and `SpotifyWebAPI::getUserPlaylistTracks()`. ([b8c87d7](https://github.com/jwilsson/spotify-web-api-php/commit/b8c87d7dfc830f6b4549ae564c1e3d78a6b6359c))
* Fixed a bug where search terms were double encoded. ([9f1eec6](https://github.com/jwilsson/spotify-web-api-php/commit/9f1eec6f4eeceb43a29f5f2748b88b1a1390b058))

## 0.1.0 (2014-06-28)
* Initial release
