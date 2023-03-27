<?php

declare(strict_types=1);

namespace SpotifyWebAPI;

class SpotifyWebAPI
{
    protected $accessToken = '';
    protected $lastResponse = [];
    protected $options = [
        'auto_refresh' => false,
        'auto_retry' => false,
        'return_assoc' => false,
    ];
    protected $request = null;
    protected $session = null;

    /**
     * Constructor
     * Set options and class instances to use.
     *
     * @param array|object $options Optional. Options to set.
     * @param Session $session Optional. The Session object to use.
     * @param Request $request Optional. The Request object to use.
     */
    public function __construct($options = [], $session = null, $request = null)
    {
        $this->setOptions($options);
        $this->setSession($session);

        $this->request = $request ?? new Request();
    }

    /**
     * Add authorization headers.
     *
     * @param $headers array. Optional. Additional headers to merge with the authorization headers.
     *
     * @return array Authorization headers, optionally merged with the passed ones.
     */
    protected function authHeaders($headers = [])
    {
        $accessToken = $this->session ? $this->session->getAccessToken() : $this->accessToken;

        if ($accessToken) {
            $headers = array_merge($headers, [
                'Authorization' => 'Bearer ' . $accessToken,
            ]);
        }

        return $headers;
    }

    /**
     * Try to fetch a snapshot ID from a response.
     *
     * @param object|array $body The parsed response body.
     *
     * @return string|bool A snapshot ID or false if none exists.
     */
    protected function getSnapshotId($body)
    {
        $body = (array) $body;

        return $body['snapshot_id'] ?? null;
    }

    /**
     * Convert Spotify object IDs to URIs.
     *
     * @param array|string $ids ID(s) to convert.
     * @param string $type Spotify object type.
     *
     * @return array|string URI(s).
     */
    protected function idToUri($ids, $type)
    {
        $type = 'spotify:' . $type . ':';

        $ids = array_map(function ($id) use ($type) {
            if (substr($id, 0, strlen($type)) != $type && substr($id, 0, 7) != 'spotify') {
                $id = $type . $id;
            }

            return $id;
        }, (array) $ids);

        return count($ids) == 1 ? $ids[0] : $ids;
    }

    /**
     * Send a request to the Spotify API, automatically refreshing the access token as needed.
     *
     * @param string $method The HTTP method to use.
     * @param string $uri The URI to request.
     * @param array $parameters Optional. Query string parameters or HTTP body, depending on $method.
     * @param array $headers Optional. HTTP headers.
     *
     * @throws SpotifyWebAPIException
     * @throws SpotifyWebAPIAuthException
     *
     * @return array Response data.
     * - array|object body The response body. Type is controlled by the `return_assoc` option.
     * - array headers Response headers.
     * - int status HTTP status code.
     * - string url The requested URL.
     */
    protected function sendRequest($method, $uri, $parameters = [], $headers = [])
    {
        $this->request->setOptions([
            'return_assoc' => $this->options['return_assoc'],
        ]);

        try {
            $headers = $this->authHeaders($headers);

            return $this->request->api($method, $uri, $parameters, $headers);
        } catch (SpotifyWebAPIException $e) {
            if ($this->options['auto_refresh'] && $e->hasExpiredToken()) {
                $result = $this->session->refreshAccessToken();

                if (!$result) {
                    throw new SpotifyWebAPIException('Could not refresh access token.');
                }

                return $this->sendRequest($method, $uri, $parameters, $headers);
            } elseif ($this->options['auto_retry'] && $e->isRateLimited()) {
                $lastResponse = $this->request->getLastResponse();
                $retryAfter = (int) $lastResponse['headers']['retry-after'];

                sleep($retryAfter);

                return $this->sendRequest($method, $uri, $parameters, $headers);
            }

            throw $e;
        }
    }

    /**
     * Convert an array to a comma-separated string. If it's already a string, do nothing.
     *
     * @param array|string The value to convert.
     *
     * @return string A comma-separated string.
     */
    protected function toCommaString($value)
    {
        if (is_array($value)) {
            return implode(',', $value);
        }

        return $value;
    }

    /**
     * Convert URIs to Spotify object IDs.
     *
     * @param array|string $uriIds URI(s) to convert.
     * @param string $type Spotify object type.
     *
     * @return array|string ID(s).
     */
    protected function uriToId($uriIds, $type)
    {
        $type = 'spotify:' . $type . ':';

        $uriIds = array_map(function ($id) use ($type) {
            return str_replace($type, '', $id);
        }, (array) $uriIds);

        return count($uriIds) == 1 ? $uriIds[0] : $uriIds;
    }

    /**
     * Add albums to the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/save-albums-user
     *
     * @param string|array $albums Album IDs or URIs to add.
     *
     * @return bool Whether the albums was successfully added.
     */
    public function addMyAlbums($albums)
    {
        $albums = $this->uriToId($albums, 'album');
        $albums = json_encode((array) $albums);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/albums';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $albums, $headers);

        return $this->lastResponse['status'] == 200;
    }

    /**
     * Add episodes to the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/save-episodes-user
     *
     * @param string|array $episodes Episode IDs or URIs to add.
     *
     * @return bool Whether the episodes was successfully added.
     */
    public function addMyEpisodes($episodes)
    {
        $episodes = $this->uriToId($episodes, 'episode');
        $episodes = json_encode((array) $episodes);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/episodes';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $episodes, $headers);

        return $this->lastResponse['status'] == 200;
    }

    /**
     * Add shows to the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/save-shows-user
     *
     * @param string|array $shows Show IDs or URIs to add.
     *
     * @return bool Whether the shows was successfully added.
     */
    public function addMyShows($shows)
    {
        $shows = $this->uriToId($shows, 'show');
        $shows = json_encode((array) $shows);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/shows';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $shows, $headers);

        return $this->lastResponse['status'] == 200;
    }

    /**
     * Add tracks to the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/save-tracks-user
     *
     * @param string|array $tracks Track IDs or URIs to add.
     *
     * @return bool Whether the tracks was successfully added.
     */
    public function addMyTracks($tracks)
    {
        $tracks = $this->uriToId($tracks, 'track');
        $tracks = json_encode((array) $tracks);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/tracks';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $tracks, $headers);

        return $this->lastResponse['status'] == 200;
    }

    /**
     * Add tracks to a playlist.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/add-tracks-to-playlist
     *
     * @param string $playlistId ID of the playlist to add tracks to.
     * @param string|array $tracks Track IDs, track URIs, and episode URIs to add.
     * @param array|object $options Optional. Options for the new tracks.
     * - int position Optional. Zero-based track position in playlist. Tracks will be appended if omitted or false.
     *
     * @return string|bool A new snapshot ID or false if the tracks weren't successfully added.
     */
    public function addPlaylistTracks($playlistId, $tracks, $options = [])
    {
        $options = array_merge((array) $options, [
            'uris' => (array) $this->idToUri($tracks, 'track')
        ]);

        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/tracks';

        $this->lastResponse = $this->sendRequest('POST', $uri, $options, $headers);

        return $this->getSnapshotId($this->lastResponse['body']);
    }

    /**
     * Change the current user's playback device.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/transfer-a-users-playback
     *
     * @param array|object $options Options for the playback transfer.
     * - string|array device_ids Required. ID of the device to switch to.
     * - bool play Optional. Whether to start playing on the new device
     *
     * @return bool Whether the playback device was successfully changed.
     */
    public function changeMyDevice($options)
    {
        $options = array_merge((array) $options, [
            'device_ids' => (array) $options['device_ids'],
        ]);

        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/player';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $options, $headers);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Change playback volume for the current user.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/set-volume-for-users-playback
     *
     * @param array|object $options Optional. Options for the playback volume.
     * - int volume_percent Required. The volume to set.
     * - string device_id Optional. ID of the device to target.
     *
     * @return bool Whether the playback volume was successfully changed.
     */
    public function changeVolume($options)
    {
        $options = http_build_query($options, '', '&');

        // We need to manually append data to the URI since it's a PUT request
        $uri = '/v1/me/player/volume?' . $options;

        $this->lastResponse = $this->sendRequest('PUT', $uri);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Create a new playlist.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/create-playlist
     *
     * @param array|object $options Options for the new playlist.
     * - string name Required. Name of the playlist.
     * - bool collaborative Optional. Whether the playlist should be collaborative or not.
     * - string description Optional. Description of the playlist.
     * - bool public Optional. Whether the playlist should be public or not.
     *
     * @return array|object The new playlist. Type is controlled by the `return_assoc` option.
     */
    public function createPlaylist($options)
    {
        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/playlists';

        $this->lastResponse = $this->sendRequest('POST', $uri, $options, $headers);

        return $this->lastResponse['body'];
    }

    /**
     * Check to see if the current user is following one or more artists or other Spotify users.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/check-current-user-follows
     *
     * @param string $type The type to check: either 'artist' or 'user'.
     * @param string|array $ids IDs or URIs of the users or artists to check for.
     *
     * @return array Whether each user or artist is followed.
     */
    public function currentUserFollows($type, $ids)
    {
        $ids = $this->uriToId($ids, $type);
        $ids = $this->toCommaString($ids);

        $options = [
            'ids' => $ids,
            'type' => $type,
        ];

        $uri = '/v1/me/following/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Delete albums from the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/remove-albums-user
     *
     * @param string|array $albums Album IDs or URIs to delete.
     *
     * @return bool Whether the albums was successfully deleted.
     */
    public function deleteMyAlbums($albums)
    {
        $albums = $this->uriToId($albums, 'album');
        $albums = json_encode((array) $albums);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/albums';

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $albums, $headers);

        return $this->lastResponse['status'] == 200;
    }

    /**
     * Delete episodes from the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/remove-episodes-user
     *
     * @param string|array $episodes Episode IDs or URIs to delete.
     *
     * @return bool Whether the episodes was successfully deleted.
     */
    public function deleteMyEpisodes($episodes)
    {
        $episodes = $this->uriToId($episodes, 'episode');
        $episodes = json_encode((array) $episodes);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/episodes';

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $episodes, $headers);

        return $this->lastResponse['status'] == 200;
    }

    /**
     * Delete shows from the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/remove-shows-user
     *
     * @param string|array $shows Show IDs or URIs to delete.
     *
     * @return bool Whether the shows was successfully deleted.
     */
    public function deleteMyShows($shows)
    {
        $shows = $this->uriToId($shows, 'show');
        $shows = json_encode((array) $shows);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/shows';

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $shows, $headers);

        return $this->lastResponse['status'] == 200;
    }

    /**
     * Delete tracks from the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/remove-tracks-user
     *
     * @param string|array $tracks Track IDs or URIs to delete.
     *
     * @return bool Whether the tracks was successfully deleted.
     */
    public function deleteMyTracks($tracks)
    {
        $tracks = $this->uriToId($tracks, 'track');
        $tracks = json_encode((array) $tracks);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/tracks';

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $tracks, $headers);

        return $this->lastResponse['status'] == 200;
    }

    /**
     * Delete tracks from a playlist and retrieve a new snapshot ID.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/remove-tracks-playlist
     *
     * @param string $playlistId ID or URI of the playlist to delete tracks from.
     * @param array $tracks An array with the key "tracks" containing arrays or objects with tracks to delete.
     * Or an array with the key "positions" containing integer positions of the tracks to delete.
     * If the "tracks" key is used, the following fields are also available:
     * - string uri Required. Track ID, track URI, or episode URI.
     * - int|array positions Optional. The track's positions in the playlist.
     * @param string $snapshotId Required when `$tracks['positions']` is used, optional otherwise.
     * The playlist's snapshot ID.
     *
     * @return string|bool A new snapshot ID or false if the tracks weren't successfully deleted.
     */
    public function deletePlaylistTracks($playlistId, $tracks, $snapshotId = '')
    {
        $options = [];

        if ($snapshotId) {
            $options['snapshot_id'] = $snapshotId;
        }

        if (isset($tracks['positions'])) {
            $options['positions'] = $tracks['positions'];
        } else {
            $options['tracks'] = array_map(function ($track) {
                $track = (array) $track;

                if (isset($track['positions'])) {
                    $track['positions'] = (array) $track['positions'];
                }

                $track['uri'] = $this->idToUri($track['uri'], 'track');

                return $track;
            }, $tracks['tracks']);
        }

        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/tracks';

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $options, $headers);

        return $this->getSnapshotId($this->lastResponse['body']);
    }

    /**
     * Add the current user as a follower of one or more artists or other Spotify users.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/follow-artists-users
     *
     * @param string $type The type of ID to follow: either 'artist' or 'user'.
     * @param string|array $ids IDs or URIs of the users or artists to follow.
     *
     * @return bool Whether the artist or user was successfully followed.
     */
    public function followArtistsOrUsers($type, $ids)
    {
        $ids = $this->uriToId($ids, $type);
        $ids = json_encode([
            'ids' => (array) $ids,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        // We need to manually append data to the URI since it's a PUT request
        $uri = '/v1/me/following?type=' . $type;

        $this->lastResponse = $this->sendRequest('PUT', $uri, $ids, $headers);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Add the current user as a follower of a playlist.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/follow-playlist
     *
     * @param string $playlistId ID or URI of the playlist to follow.
     * @param array|object $options Optional. Options for the followed playlist.
     * - bool public Optional. Whether the playlist should be followed publicly or not.
     *
     * @return bool Whether the playlist was successfully followed.
     */
    public function followPlaylist($playlistId, $options = [])
    {
        $options = $options ? json_encode($options) : null;

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/followers';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $options, $headers);

        return $this->lastResponse['status'] == 200;
    }

    /**
     * Get an album.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-an-album
     *
     * @param string $albumId ID or URI of the album.
     * @param array|object $options Optional. Options for the album.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     *
     * @return array|object The requested album. Type is controlled by the `return_assoc` option.
     */
    public function getAlbum($albumId, $options = [])
    {
        $albumId = $this->uriToId($albumId, 'album');
        $uri = '/v1/albums/' . $albumId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get multiple albums.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-multiple-albums
     *
     * @param array $albumIds IDs or URIs of the albums.
     * @param array|object $options Optional. Options for the albums.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     *
     * @return array|object The requested albums. Type is controlled by the `return_assoc` option.
     */
    public function getAlbums($albumIds, $options = [])
    {
        $albumIds = $this->uriToId($albumIds, 'album');
        $options = array_merge((array) $options, [
            'ids' => $this->toCommaString($albumIds),
        ]);

        $uri = '/v1/albums/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get an album's tracks.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-an-albums-tracks
     *
     * @param string $albumId ID or URI of the album.
     * @param array|object $options Optional. Options for the tracks.
     * - int limit Optional. Limit the number of tracks.
     * - int offset Optional. Number of tracks to skip.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     *
     * @return array|object The requested album tracks. Type is controlled by the `return_assoc` option.
     */
    public function getAlbumTracks($albumId, $options = [])
    {
        $albumId = $this->uriToId($albumId, 'album');
        $uri = '/v1/albums/' . $albumId . '/tracks';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get an artist.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-an-artist
     *
     * @param string $artistId ID or URI of the artist.
     *
     * @return array|object The requested artist. Type is controlled by the `return_assoc` option.
     */
    public function getArtist($artistId)
    {
        $artistId = $this->uriToId($artistId, 'artist');
        $uri = '/v1/artists/' . $artistId;

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    /**
     * Get multiple artists.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-multiple-artists
     *
     * @param array $artistIds IDs or URIs of the artists.
     *
     * @return array|object The requested artists. Type is controlled by the `return_assoc` option.
     */
    public function getArtists($artistIds)
    {
        $artistIds = $this->uriToId($artistIds, 'artist');
        $artistIds = $this->toCommaString($artistIds);

        $options = [
            'ids' => $artistIds,
        ];

        $uri = '/v1/artists/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get an artist's related artists.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-an-artists-related-artists
     *
     * @param string $artistId ID or URI of the artist.
     *
     * @return array|object The artist's related artists. Type is controlled by the `return_assoc` option.
     */
    public function getArtistRelatedArtists($artistId)
    {
        $artistId = $this->uriToId($artistId, 'artist');
        $uri = '/v1/artists/' . $artistId . '/related-artists';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    /**
     * Get an artist's albums.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-an-artists-albums
     *
     * @param string $artistId ID or URI of the artist.
     * @param array|object $options Optional. Options for the albums.
     * - string country Optional. Limit the results to items that are playable in this country, for example SE.
     * - string|array include_groups Optional. Album types to return. If omitted, all album types will be returned.
     * - int limit Optional. Limit the number of albums.
     * - int offset Optional. Number of albums to skip.
     *
     * @return array|object The artist's albums. Type is controlled by the `return_assoc` option.
     */
    public function getArtistAlbums($artistId, $options = [])
    {
        $options = (array) $options;

        if (isset($options['include_groups'])) {
            $options['include_groups'] = $this->toCommaString($options['include_groups']);
        }

        $artistId = $this->uriToId($artistId, 'artist');
        $uri = '/v1/artists/' . $artistId . '/albums';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get an artist's top tracks in a country.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-an-artists-top-tracks
     *
     * @param string $artistId ID or URI of the artist.
     * @param array|object $options Options for the tracks.
     * - string country Required. ISO 3166-1 alpha-2 country code specifying the country to get the top tracks for.
     *
     * @return array|object The artist's top tracks. Type is controlled by the `return_assoc` option.
     */
    public function getArtistTopTracks($artistId, $options)
    {
        $artistId = $this->uriToId($artistId, 'artist');
        $uri = '/v1/artists/' . $artistId . '/top-tracks';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get audio analysis for track.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-audio-analysis
     *
     * @param string $trackId ID or URI of the track.
     *
     * @return object The track's audio analysis. Type is controlled by the `return_assoc` option.
     */
    public function getAudioAnalysis($trackId)
    {
        $trackId = $this->uriToId($trackId, 'track');
        $uri = '/v1/audio-analysis/' . $trackId;

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    /**
     * Get an audiobook.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-an-audiobook
     *
     * @param string $audiobookId ID or URI of the audiobook.
     * @param array|object $options Optional. Options for the audiobook.
     * - string market Optional. ISO 3166-1 alpha-2 country code, limit results to audiobooks available in that market.
     *
     * @return array|object The requested audiobook. Type is controlled by the `return_assoc` option.
     */
    public function getAudiobook($audiobookId)
    {
        $audiobookId = $this->uriToId($audiobookId, 'show');
        $uri = '/v1/audiobooks/' . $audiobookId;

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    /**
     * Get multiple audiobooks.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-multiple-audiobooks
     *
     * @param array $audiobookIds IDs or URIs of the audiobooks.
     * @param array|object $options Optional. Options for the audiobooks.
     * - string market Optional. ISO 3166-1 alpha-2 country code, limit results to audiobooks available in that market.
     *
     * @return array|object The requested audiobooks. Type is controlled by the `return_assoc` option.
     */
    public function getAudiobooks($audiobookIds)
    {
        $audiobookIds = $this->uriToId($audiobookIds, 'show');
        $audiobookIds = $this->toCommaString($audiobookIds);

        $options = [
            'ids' => $audiobookIds,
        ];

        $uri = '/v1/audiobooks/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get audio features of a single track.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-audio-features
     *
     * @param string $trackId ID or URI of the track.
     *
     * @return array|object The track's audio features. Type is controlled by the `return_assoc` option.
     */
    public function getAudioFeatures($trackId)
    {
        // Deprecated, but kept for legacy reasons for now
        if (is_array($trackId)) {
            trigger_error(
                'Passing an array to getAudioFeatures() is deprecated, use getMultipleAudioFeatures() instead.',
                E_USER_DEPRECATED
            );

            return $this->getMultipleAudioFeatures($trackId);
        }

        $trackId = $this->uriToId($trackId, 'track');
        $uri = '/v1/audio-features/' . $trackId;

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    /**
     * Get a list of categories used to tag items in Spotify (on, for example, the Spotify player’s "Discover" tab).
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-categories
     *
     * @param array|object $options Optional. Options for the categories.
     * - string locale Optional. Language to show categories in, for example 'sv_SE'.
     * - string country Optional. ISO 3166-1 alpha-2 country code. Show categories from this country.
     * - int limit Optional. Limit the number of categories.
     * - int offset Optional. Number of categories to skip.
     *
     * @return array|object The list of categories. Type is controlled by the `return_assoc` option.
     */
    public function getCategoriesList($options = [])
    {
        $uri = '/v1/browse/categories';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get a single category used to tag items in Spotify (on, for example, the Spotify player’s "Discover" tab).
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-a-category
     *
     * @param string $categoryId ID of the category.
     *
     * @param array|object $options Optional. Options for the category.
     * - string locale Optional. Language to show category in, for example 'sv_SE'.
     * - string country Optional. ISO 3166-1 alpha-2 country code. Show category from this country.
     *
     * @return array|object The category. Type is controlled by the `return_assoc` option.
     */
    public function getCategory($categoryId, $options = [])
    {
        $uri = '/v1/browse/categories/' . $categoryId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get a list of Spotify playlists tagged with a particular category.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-a-categories-playlists
     *
     * @param string $categoryId ID of the category.
     *
     * @param array|object $options Optional. Options for the category's playlists.
     * - string country Optional. ISO 3166-1 alpha-2 country code. Show category playlists from this country.
     * - int limit Optional. Limit the number of playlists.
     * - int offset Optional. Number of playlists to skip.
     *
     * @return array|object The list of playlists. Type is controlled by the `return_assoc` option.
     */
    public function getCategoryPlaylists($categoryId, $options = [])
    {
        $uri = '/v1/browse/categories/' . $categoryId . '/playlists';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get a chapter.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-chapter
     *
     * @param string $chapterId ID or URI of the chapter.
     * @param array|object $options Optional. Options for the chapter.
     * - string market Optional. ISO 3166-1 alpha-2 country code, limit results to episodes available in that market.
     *
     * @return array|object The requested chapter. Type is controlled by the `return_assoc` option.
     */
    public function getChapter($chapterId, $options = [])
    {
        $chapterId = $this->uriToId($chapterId, 'episode');
        $uri = '/v1/chapters/' . $chapterId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get multiple chapters.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-several-chapters
     *
     * @param array $chapterIds IDs or URIs of the chapters.
     * @param array|object $options Optional. Options for the chapters.
     * - string market Optional. ISO 3166-1 alpha-2 country code, limit results to episodes available in that market.
     *
     * @return array|object The requested chapters. Type is controlled by the `return_assoc` option.
     */
    public function getChapters($chapterIds, $options = [])
    {
        $chapterIds = $this->uriToId($chapterIds, 'episode');
        $options = array_merge((array) $options, [
            'ids' => $this->toCommaString($chapterIds),
        ]);

        $uri = '/v1/chapters/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get an episode.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-an-episode
     *
     * @param string $episodeId ID or URI of the episode.
     * @param array|object $options Optional. Options for the episode.
     * - string market Optional. ISO 3166-1 alpha-2 country code, limit results to episodes available in that market.
     *
     * @return array|object The requested episode. Type is controlled by the `return_assoc` option.
     */
    public function getEpisode($episodeId, $options = [])
    {
        $episodeId = $this->uriToId($episodeId, 'episode');
        $uri = '/v1/episodes/' . $episodeId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get multiple episodes.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-multiple-episodes
     *
     * @param array $episodeIds IDs or URIs of the episodes.
     * @param array|object $options Optional. Options for the episodes.
     * - string market Optional. ISO 3166-1 alpha-2 country code, limit results to episodes available in that market.
     *
     * @return array|object The requested episodes. Type is controlled by the `return_assoc` option.
     */
    public function getEpisodes($episodeIds, $options = [])
    {
        $episodeIds = $this->uriToId($episodeIds, 'episode');
        $options = array_merge((array) $options, [
            'ids' => $this->toCommaString($episodeIds),
        ]);

        $uri = '/v1/episodes/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get Spotify featured playlists.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-featured-playlists
     *
     * @param array|object $options Optional. Options for the playlists.
     * - string locale Optional. Language to show playlists in, for example 'sv_SE'.
     * - string country Optional. ISO 3166-1 alpha-2 country code. Show playlists from this country.
     * - string timestamp Optional. A ISO 8601 timestamp. Show playlists relevant to this date and time.
     * - int limit Optional. Limit the number of playlists.
     * - int offset Optional. Number of playlists to skip.
     *
     * @return array|object The featured playlists. Type is controlled by the `return_assoc` option.
     */
    public function getFeaturedPlaylists($options = [])
    {
        $uri = '/v1/browse/featured-playlists';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get a list of possible seed genres.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-recommendation-genres
     *
     * @return array|object All possible seed genres. Type is controlled by the `return_assoc` option.
     */
    public function getGenreSeeds()
    {
        $uri = '/v1/recommendations/available-genre-seeds';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    /**
     * Get the latest full response from the Spotify API.
     *
     * @return array Response data.
     * - array|object body The response body. Type is controlled by the `return_assoc` option.
     * - array headers Response headers.
     * - int status HTTP status code.
     * - string url The requested URL.
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Get all markets where Spotify is available.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-available-markets
     *
     * @return array|object All markets where Spotify is available. Type is controlled by the `return_assoc` option.
     */
    public function getMarkets()
    {
        $uri = '/v1/markets';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    /**
     * Get audio features of multiple tracks.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-several-audio-features
     *
     * @param array $trackIds IDs or URIs of the tracks.
     *
     * @return array|object The tracks' audio features. Type is controlled by the `return_assoc` option.
     */
    public function getMultipleAudioFeatures($trackIds)
    {
        $trackIds = $this->uriToId($trackIds, 'track');
        $options = [
            'ids' => $this->toCommaString($trackIds),
        ];

        $uri = '/v1/audio-features';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get the current user’s currently playing track.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-the-users-currently-playing-track
     *
     * @param array|object $options Optional. Options for the track.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     * - string|array additional_types Optional. Types of media to return info about.
     *
     * @return array|object The user's currently playing track. Type is controlled by the `return_assoc` option.
     */
    public function getMyCurrentTrack($options = [])
    {
        $uri = '/v1/me/player/currently-playing';
        $options = (array) $options;

        if (isset($options['additional_types'])) {
            $options['additional_types'] = $this->toCommaString($options['additional_types']);
        }

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get the current user’s devices.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-a-users-available-devices
     *
     * @return array|object The user's devices. Type is controlled by the `return_assoc` option.
     */
    public function getMyDevices()
    {
        $uri = '/v1/me/player/devices';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    /**
     * Get the current user’s current playback information.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-information-about-the-users-current-playback
     *
     * @param array|object $options Optional. Options for the info.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     * - string|array additional_types Optional. Types of media to return info about.
     *
     * @return array|object The user's playback information. Type is controlled by the `return_assoc` option.
     */
    public function getMyCurrentPlaybackInfo($options = [])
    {
        $uri = '/v1/me/player';
        $options = (array) $options;

        if (isset($options['additional_types'])) {
            $options['additional_types'] = $this->toCommaString($options['additional_types']);
        }

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }


    /**
     * Get the current user’s playlists.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-a-list-of-current-users-playlists
     *
     * @param array|object $options Optional. Options for the playlists.
     * - int limit Optional. Limit the number of playlists.
     * - int offset Optional. Number of playlists to skip.
     *
     * @return array|object The user's playlists. Type is controlled by the `return_assoc` option.
     */
    public function getMyPlaylists($options = [])
    {
        $uri = '/v1/me/playlists';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get the current user’s queue.
     *
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-queue
     *
     * @return array|object The currently playing song and queue. Type is controlled by the `return_assoc` option.
     */
    public function getMyQueue()
    {
        $uri = '/v1/me/player/queue';

        $this->lastResponse = $this->sendRequest('GET', $uri, []);

        return $this->lastResponse['body'];
    }

    /**
      * Get the current user’s recently played tracks.
      * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-recently-played
      *
      * @param array|object $options Optional. Options for the tracks.
      * - int limit Optional. Number of tracks to return.
      * - string after Optional. Unix timestamp in ms (13 digits). Returns all items after this position.
      * - string before Optional. Unix timestamp in ms (13 digits). Returns all items before this position.
      *
      * @return array|object The most recently played tracks. Type is controlled by the `return_assoc` option.
      */
    public function getMyRecentTracks($options = [])
    {
        $uri = '/v1/me/player/recently-played';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get the current user’s saved albums.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-users-saved-albums
     *
     * @param array|object $options Optional. Options for the albums.
     * - int limit Optional. Number of albums to return.
     * - int offset Optional. Number of albums to skip.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     *
     * @return array|object The user's saved albums. Type is controlled by the `return_assoc` option.
     */
    public function getMySavedAlbums($options = [])
    {
        $uri = '/v1/me/albums';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get the current user’s saved episodes.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-users-saved-episodes
     *
     * @param array|object $options Optional. Options for the episodes.
     * - int limit Optional. Number of episodes to return.
     * - int offset Optional. Number of episodes to skip.
     * - string market Optional. ISO 3166-1 alpha-2 country code, limit results to episodes available in that market.
     *
     * @return array|object The user's saved episodes. Type is controlled by the `return_assoc` option.
     */
    public function getMySavedEpisodes($options = [])
    {
        $uri = '/v1/me/episodes';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get the current user’s saved tracks.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-users-saved-tracks
     *
     * @param array|object $options Optional. Options for the tracks.
     * - int limit Optional. Limit the number of tracks.
     * - int offset Optional. Number of tracks to skip.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     *
     * @return array|object The user's saved tracks. Type is controlled by the `return_assoc` option.
     */
    public function getMySavedTracks($options = [])
    {
        $uri = '/v1/me/tracks';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get the current user’s saved shows.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-users-saved-shows
     *
     * @param array|object $options Optional. Options for the shows.
     * - int limit Optional. Limit the number of shows.
     * - int offset Optional. Number of shows to skip.
     *
     * @return array|object The user's saved shows. Type is controlled by the `return_assoc` option.
     */
    public function getMySavedShows($options = [])
    {
        $uri = '/v1/me/shows';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get the current user's top tracks or artists.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-users-top-artists-and-tracks
     *
     * @param string $type The type to fetch, either 'artists' or 'tracks'.
     * @param array $options Optional. Options for the results.
     * - int limit Optional. Limit the number of results.
     * - int offset Optional. Number of results to skip.
     * - string time_range Optional. Over what time frame the data is calculated. See Spotify API docs for more info.
     *
     * @return array|object A list of the requested top entity. Type is controlled by the `return_assoc` option.
     */
    public function getMyTop($type, $options = [])
    {
        $uri = '/v1/me/top/' . $type;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get new releases.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-new-releases
     *
     * @param array|object $options Optional. Options for the items.
     * - string country Optional. ISO 3166-1 alpha-2 country code. Show items relevant to this country.
     * - int limit Optional. Limit the number of items.
     * - int offset Optional. Number of items to skip.
     *
     * @return array|object The new releases. Type is controlled by the `return_assoc` option.
     */
    public function getNewReleases($options = [])
    {
        $uri = '/v1/browse/new-releases';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get a specific playlist.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-playlist
     *
     * @param string $playlistId ID or URI of the playlist.
     * @param array|object $options Optional. Options for the playlist.
     * - string|array fields Optional. A list of fields to return. See Spotify docs for more info.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     * - string|array additional_types Optional. Types of media to return info about.
     *
     * @return array|object The user's playlist. Type is controlled by the `return_assoc` option.
     */
    public function getPlaylist($playlistId, $options = [])
    {
        $options = (array) $options;

        if (isset($options['fields'])) {
            $options['fields'] = $this->toCommaString($options['fields']);
        }

        if (isset($options['additional_types'])) {
            $options['additional_types'] = $this->toCommaString($options['additional_types']);
        }

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get a playlist's cover image.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-playlist-cover
     *
     * @param string $playlistId ID or URI of the playlist.
     *
     * @return array|object The playlist cover image. Type is controlled by the `return_assoc` option.
     */
    public function getPlaylistImage($playlistId)
    {
        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/images';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    /**
     * Get the tracks in a playlist.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-playlists-tracks
     *
     * @param string $playlistId ID or URI of the playlist.
     * @param array|object $options Optional. Options for the tracks.
     * - string|array fields Optional. A list of fields to return. See Spotify docs for more info.
     * - int limit Optional. Limit the number of tracks.
     * - int offset Optional. Number of tracks to skip.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     * - string|array additional_types Optional. Types of media to return info about.
     *
     * @return array|object The tracks in the playlist. Type is controlled by the `return_assoc` option.
     */
    public function getPlaylistTracks($playlistId, $options = [])
    {
        $options = (array) $options;

        if (isset($options['fields'])) {
            $options['fields'] = $this->toCommaString($options['fields']);
        }

        if (isset($options['additional_types'])) {
            $options['additional_types'] = $this->toCommaString($options['additional_types']);
        }

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/tracks';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get recommendations based on artists, tracks, or genres.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-recommendations
     *
     * @param array|object $options Optional. Options for the recommendations.
     * - int limit Optional. Limit the number of recommendations.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     * - mixed max_* Optional. Max value for one of the tunable track attributes.
     * - mixed min_* Optional. Min value for one of the tunable track attributes.
     * - array seed_artists Artist IDs to seed by.
     * - array seed_genres Genres to seed by. Call SpotifyWebAPI::getGenreSeeds() for a complete list.
     * - array seed_tracks Track IDs to seed by.
     * - mixed target_* Optional. Target value for one of the tunable track attributes.
     *
     * @return array|object The requested recommendations. Type is controlled by the `return_assoc` option.
     */
    public function getRecommendations($options = [])
    {
        $options = (array) $options;

        array_walk($options, function (&$value, $key) {
            if (substr($key, 0, 5) == 'seed_') {
                $value = $this->toCommaString($value);
            }
        });

        $uri = '/v1/recommendations';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get the Request object in use.
     *
     * @return Request The Request object in use.
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get a show.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-a-show
     *
     * @param string $showId ID or URI of the show.
     * @param array|object $options Optional. Options for the show.
     * - string market Optional. ISO 3166-1 alpha-2 country code, limit results to shows available in that market.
     *
     * @return array|object The requested show. Type is controlled by the `return_assoc` option.
     */
    public function getShow($showId, $options = [])
    {
        $showId = $this->uriToId($showId, 'show');
        $uri = '/v1/shows/' . $showId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get a show's episodes.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-a-shows-episodes
     *
     * @param string $albumId ID or URI of the album.
     * @param array|object $options Optional. Options for the episodes.
     * - int limit Optional. Limit the number of episodes.
     * - int offset Optional. Number of episodes to skip.
     * - string market Optional. ISO 3166-1 alpha-2 country code, limit results to episodes available in that market.
     *
     * @return array|object The requested show episodes. Type is controlled by the `return_assoc` option.
     */
    public function getShowEpisodes($showId, $options = [])
    {
        $showId = $this->uriToId($showId, 'show');
        $uri = '/v1/shows/' . $showId . '/episodes';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get multiple shows.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-multiple-shows
     *
     * @param array $showIds IDs or URIs of the shows.
     * @param array|object $options Optional. Options for the shows.
     * - string market Optional. ISO 3166-1 alpha-2 country code, limit results to shows available in that market.
     *
     * @return array|object The requested shows. Type is controlled by the `return_assoc` option.
     */
    public function getShows($showIds, $options = [])
    {
        $showIds = $this->uriToId($showIds, 'show');
        $options = array_merge((array) $options, [
            'ids' => $this->toCommaString($showIds),
        ]);

        $uri = '/v1/shows/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get a track.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-track
     *
     * @param string $trackId ID or URI of the track.
     * @param array|object $options Optional. Options for the track.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     *
     * @return array|object The requested track. Type is controlled by the `return_assoc` option.
     */
    public function getTrack($trackId, $options = [])
    {
        $trackId = $this->uriToId($trackId, 'track');
        $uri = '/v1/tracks/' . $trackId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get multiple tracks.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-several-tracks
     *
     * @param array $trackIds IDs or URIs of the tracks.
     * @param array|object $options Optional. Options for the tracks.
     * - string market Optional. ISO 3166-1 alpha-2 country code, provide this if you wish to apply Track Relinking.
     *
     * @return array|object The requested tracks. Type is controlled by the `return_assoc` option.
     */
    public function getTracks($trackIds, $options = [])
    {
        $trackIds = $this->uriToId($trackIds, 'track');
        $options = array_merge((array) $options, [
            'ids' => $this->toCommaString($trackIds),
        ]);

        $uri = '/v1/tracks/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get a user.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-users-profile
     *
     * @param string $userId ID or URI of the user.
     *
     * @return array|object The requested user. Type is controlled by the `return_assoc` option.
     */
    public function getUser($userId)
    {
        $userId = $this->uriToId($userId, 'user');
        $uri = '/v1/users/' . $userId;

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    /**
     * Get the artists followed by the current user.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-followed
     *
     * @param array|object $options Optional. Options for the artists.
     * - int limit Optional. Limit the number of artists returned.
     * - string after Optional. The last artist ID retrieved from the previous request.
     *
     * @return array|object A list of artists. Type is controlled by the `return_assoc` option.
     */
    public function getUserFollowedArtists($options = [])
    {
        $options = (array) $options;

        if (!isset($options['type'])) {
            $options['type'] = 'artist'; // Undocumented until more values are supported.
        }

        $uri = '/v1/me/following';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get a user's playlists.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-list-users-playlists
     *
     * @param string $userId ID or URI of the user.
     * @param array|object $options Optional. Options for the tracks.
     * - int limit Optional. Limit the number of tracks.
     * - int offset Optional. Number of tracks to skip.
     *
     * @return array|object The user's playlists. Type is controlled by the `return_assoc` option.
     */
    public function getUserPlaylists($userId, $options = [])
    {
        $userId = $this->uriToId($userId, 'user');
        $uri = '/v1/users/' . $userId . '/playlists';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get the currently authenticated user.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-current-users-profile
     *
     * @return array|object The currently authenticated user. Type is controlled by the `return_assoc` option.
     */
    public function me()
    {
        $uri = '/v1/me';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    /**
     * Check if albums are saved in the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/check-users-saved-albums
     *
     * @param string|array $albums Album IDs or URIs to check for.
     *
     * @return array Whether each album is saved.
     */
    public function myAlbumsContains($albums)
    {
        $albums = $this->uriToId($albums, 'album');
        $albums = $this->toCommaString($albums);

        $options = [
            'ids' => $albums,
        ];

        $uri = '/v1/me/albums/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Check if episodes are saved in the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/check-users-saved-episodes
     *
     * @param string|array $episodes Episode IDs or URIs to check for.
     *
     * @return array Whether each episode is saved.
     */
    public function myEpisodesContains($episodes)
    {
        $episodes = $this->uriToId($episodes, 'episode');
        $episodes = $this->toCommaString($episodes);

        $options = [
            'ids' => $episodes,
        ];

        $uri = '/v1/me/episodes/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Check if shows are saved in the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/check-users-saved-shows
     *
     * @param string|array $shows Show IDs or URIs to check for.
     *
     * @return array Whether each show is saved.
     */
    public function myShowsContains($shows)
    {
        $shows = $this->uriToId($shows, 'show');
        $shows = $this->toCommaString($shows);

        $options = [
            'ids' => $shows,
        ];

        $uri = '/v1/me/shows/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Check if tracks are saved in the current user's Spotify library.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/check-users-saved-tracks
     *
     * @param string|array $tracks Track IDs or URIs to check for.
     *
     * @return array Whether each track is saved.
     */
    public function myTracksContains($tracks)
    {
        $tracks = $this->uriToId($tracks, 'track');
        $tracks = $this->toCommaString($tracks);

        $options = [
            'ids' => $tracks,
        ];

        $uri = '/v1/me/tracks/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Play the next track in the current users's queue.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/skip-users-playback-to-next-track
     *
     * @param string $deviceId Optional. ID of the device to target.
     *
     * @return bool Whether the track was successfully skipped.
     */
    public function next($deviceId = '')
    {
        $uri = '/v1/me/player/next';

        // We need to manually append data to the URI since it's a POST request
        if ($deviceId) {
            $uri = $uri . '?device_id=' . $deviceId;
        }

        $this->lastResponse = $this->sendRequest('POST', $uri);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Pause playback for the current user.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/pause-a-users-playback
     *
     * @param string $deviceId Optional. ID of the device to pause on.
     *
     * @return bool Whether the playback was successfully paused.
     */
    public function pause($deviceId = '')
    {
        $uri = '/v1/me/player/pause';

        // We need to manually append data to the URI since it's a PUT request
        if ($deviceId) {
            $uri = $uri . '?device_id=' . $deviceId;
        }

        $this->lastResponse = $this->sendRequest('PUT', $uri);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Start playback for the current user.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/start-a-users-playback
     *
     * @param string $deviceId Optional. ID of the device to play on.
     * @param array|object $options Optional. Options for the playback.
     * - string context_uri Optional. URI of the context to play, for example an album.
     * - array uris Optional. Spotify track URIs to play.
     * - object offset Optional. Indicates from where in the context playback should start.
     * - int position_ms. Optional. Indicates the position to start playback from.
     *
     * @return bool Whether the playback was successfully started.
     */
    public function play($deviceId = '', $options = [])
    {
        $options = $options ? json_encode($options) : null;

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/player/play';

        // We need to manually append data to the URI since it's a PUT request
        if ($deviceId) {
            $uri = $uri . '?device_id=' . $deviceId;
        }

        $this->lastResponse = $this->sendRequest('PUT', $uri, $options, $headers);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Play the previous track in the current users's queue.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/skip-users-playback-to-previous-track
     *
     * @param string $deviceId Optional. ID of the device to target.
     *
     * @return bool Whether the track was successfully skipped.
     */
    public function previous($deviceId = '')
    {
        $uri = '/v1/me/player/previous';

        // We need to manually append data to the URI since it's a POST request
        if ($deviceId) {
            $uri = $uri . '?device_id=' . $deviceId;
        }

        $this->lastResponse = $this->sendRequest('POST', $uri);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Add an item to the queue.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/add-to-queue
     *
     * @param string $trackUri Required. Track ID, track URI or episode URI to queue.
     * @param string $deviceId Optional. ID of the device to target.
     *
     * @return bool Whether the track was successfully queued.
     */
    public function queue($trackUri, $deviceId = '')
    {
        $uri = '/v1/me/player/queue?uri=' . $this->idToUri($trackUri, 'track');

        // We need to manually append data to the URI since it's a POST request
        if ($deviceId) {
            $uri = $uri . '&device_id=' . $deviceId;
        }

        $this->lastResponse = $this->sendRequest('POST', $uri);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Reorder the tracks in a playlist.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/reorder-or-replace-playlists-tracks
     *
     * @param string $playlistId ID or URI of the playlist.
     * @param array|object $options Options for the new tracks.
     * - int range_start Required. Position of the first track to be reordered.
     * - int range_length Optional. The amount of tracks to be reordered.
     * - int insert_before Required. Position where the tracks should be inserted.
     * - string snapshot_id Optional. The playlist's snapshot ID.
     *
     * @return string|bool A new snapshot ID or false if the tracks weren't successfully reordered.
     */
    public function reorderPlaylistTracks($playlistId, $options)
    {
        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/tracks';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $options, $headers);

        return $this->getSnapshotId($this->lastResponse['body']);
    }

    /**
     * Set repeat mode for the current user’s playback.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/set-repeat-mode-on-users-playback
     *
     * @param array|object $options Optional. Options for the playback repeat mode.
     * - string state Required. The repeat mode. See Spotify docs for possible values.
     * - string device_id Optional. ID of the device to target.
     *
     * @return bool Whether the playback repeat mode was successfully changed.
     */
    public function repeat($options)
    {
        $options = http_build_query($options, '', '&');

        // We need to manually append data to the URI since it's a PUT request
        $uri = '/v1/me/player/repeat?' . $options;

        $this->lastResponse = $this->sendRequest('PUT', $uri);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Replace all tracks in a playlist with new ones.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/reorder-or-replace-playlists-tracks
     *
     * @param string $playlistId ID or URI of the playlist.
     * @param string|array $tracks IDs, track URIs, or episode URIs to replace with.
     *
     * @return bool Whether the tracks was successfully replaced.
     */
    public function replacePlaylistTracks($playlistId, $tracks)
    {
        $tracks = $this->idToUri($tracks, 'track');
        $tracks = json_encode([
            'uris' => (array) $tracks,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/tracks';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $tracks, $headers);

        return $this->lastResponse['status'] == 201;
    }

    /**
     * Search for an item.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/search
     *
     * @param string $query The term to search for.
     * @param string|array $type The type of item to search for.
     * @param array|object $options Optional. Options for the search.
     * - string market Optional. Limit the results to items that are playable in this market, for example SE.
     * - int limit Optional. Limit the number of items.
     * - int offset Optional. Number of items to skip.
     * - string include_external Optional. Whether or not to mark externally hosted content as playable.
     *
     * @return array|object The search results. Type is controlled by the `return_assoc` option.
     */
    public function search($query, $type, $options = [])
    {
        $options = array_merge((array) $options, [
            'q' => $query,
            'type' => $this->toCommaString($type),
        ]);

        $uri = '/v1/search';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Change playback position for the current user.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/seek-to-position-in-currently-playing-track
     *
     * @param array|object $options Optional. Options for the playback seeking.
     * - string position_ms Required. The position in milliseconds to seek to.
     * - string device_id Optional. ID of the device to target.
     *
     * @return bool Whether the playback position was successfully changed.
     */
    public function seek($options)
    {
        $options = http_build_query($options, '', '&');

        // We need to manually append data to the URI since it's a PUT request
        $uri = '/v1/me/player/seek?' . $options;

        $this->lastResponse = $this->sendRequest('PUT', $uri);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Set the access token to use.
     *
     * @param string $accessToken The access token.
     *
     * @return void
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Set options
     *
     * @param array|object $options Options to set.
     *
     * @return void
     */
    public function setOptions($options)
    {
        $this->options = array_merge($this->options, (array) $options);
    }

    /**
     * Set the Session object to use.
     *
     * @param Session $session The Session object.
     *
     * @return void
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * Set shuffle mode for the current user’s playback.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/toggle-shuffle-for-users-playback
     *
     * @param array|object $options Optional. Options for the playback shuffle mode.
     * - bool state Required. The shuffle mode. See Spotify docs for possible values.
     * - string device_id Optional. ID of the device to target.
     *
     * @return bool Whether the playback shuffle mode was successfully changed.
     */
    public function shuffle($options)
    {
        $options = array_merge((array) $options, [
            'state' => $options['state'] ? 'true' : 'false',
        ]);

        $options = http_build_query($options, '', '&');

        // We need to manually append data to the URI since it's a PUT request
        $uri = '/v1/me/player/shuffle?' . $options;

        $this->lastResponse = $this->sendRequest('PUT', $uri);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Remove the current user as a follower of one or more artists or other Spotify users.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/unfollow-artists-users
     *
     * @param string $type The type to check: either 'artist' or 'user'.
     * @param string|array $ids IDs or URIs of the users or artists to unfollow.
     *
     * @return bool Whether the artists or users were successfully unfollowed.
     */
    public function unfollowArtistsOrUsers($type, $ids)
    {
        $ids = $this->uriToId($ids, $type);
        $ids = json_encode([
            'ids' => (array) $ids,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        // We need to manually append data to the URI since it's a DELETE request
        $uri = '/v1/me/following?type=' . $type;

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $ids, $headers);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Remove the current user as a follower of a playlist.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/unfollow-playlist
     *
     * @param string $playlistId ID or URI of the playlist to unfollow.
     *
     * @return bool Whether the playlist was successfully unfollowed.
     */
    public function unfollowPlaylist($playlistId)
    {
        $playlistId = $this->uriToId($playlistId, 'playlist');
        $uri = '/v1/playlists/' . $playlistId . '/followers';

        $this->lastResponse = $this->sendRequest('DELETE', $uri);

        return $this->lastResponse['status'] == 200;
    }

    /**
     * Update the details of a playlist.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/change-playlist-details
     *
     * @param string $playlistId ID or URI of the playlist to update.
     * @param array|object $options Options for the playlist.
     * - bool collaborative Optional. Whether the playlist should be collaborative or not.
     * - string description Optional. Description of the playlist.
     * - string name Optional. Name of the playlist.
     * - bool public Optional. Whether the playlist should be public or not.
     *
     * @return bool Whether the playlist was successfully updated.
     */
    public function updatePlaylist($playlistId, $options)
    {
        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId;

        $this->lastResponse = $this->sendRequest('PUT', $uri, $options, $headers);

        return $this->lastResponse['status'] == 200;
    }

    /**
     * Update the image of a playlist.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/upload-custom-playlist-cover
     *
     * @param string $playlistId ID or URI of the playlist to update.
     * @param string $imageData Base64 encoded JPEG image data, maximum 256 KB in size.
     *
     * @return bool Whether the playlist was successfully updated.
     */
    public function updatePlaylistImage($playlistId, $imageData)
    {
        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/images';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $imageData);

        return $this->lastResponse['status'] == 202;
    }

    /**
     * Check if a set of users are following a playlist.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/check-if-user-follows-playlist
     *
     * @param string $playlistId ID or URI of the playlist.
     * @param array|object $options Options for the check.
     * - ids string|array Required. IDs or URIs of the users to check for.
     *
     * @return array Whether each user is following the playlist.
     */
    public function usersFollowPlaylist($playlistId, $options)
    {
        $options = (array) $options;

        if (isset($options['ids'])) {
            $options['ids'] = $this->uriToId($options['ids'], 'user');
            $options['ids'] = $this->toCommaString($options['ids']);
        }

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/followers/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }
}
