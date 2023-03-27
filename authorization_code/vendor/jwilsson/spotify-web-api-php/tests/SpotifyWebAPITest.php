<?php

declare(strict_types=1);

class SpotifyWebAPITest extends PHPUnit\Framework\TestCase
{
    private $accessToken = 'access_token';

    private function setupStub($expectedMethod, $expectedUri, $expectedParameters, $expectedHeaders, $expectedReturn)
    {
        $stub = $this->createPartialMock(SpotifyWebAPI\Request::class, ['api', 'getLastResponse']);

        $stub->expects($this->any())
            ->method('api')
            ->with(
                $this->equalTo($expectedMethod),
                $this->equalTo($expectedUri),
                $this->equalTo($expectedParameters),
                $this->equalTo($expectedHeaders)
            )
            ->willReturn($expectedReturn);

        $stub->expects($this->any())
                ->method('getLastResponse')
                ->willReturn($expectedReturn);

        return $stub;
    }

    private function setupSessionStub()
    {
        $stub = $this->createPartialMock(SpotifyWebAPI\Session::class, ['getAccessToken', 'refreshAccessToken']);

        $stub->method('getAccessToken')
            ->willReturn($this->accessToken);

        $stub->method('refreshAccessToken')
            ->willReturn(true);

        return $stub;
    }

    private function setupApi($expectedMethod, $expectedUri, $expectedParameters, $expectedHeaders, $expectedReturn)
    {
        $stub = $this->setupStub(
            $expectedMethod,
            $expectedUri,
            $expectedParameters,
            $expectedHeaders,
            $expectedReturn
        );

        return new SpotifyWebAPI\SpotifyWebAPI([], null, $stub);
    }

    public function testAutoRefreshOption()
    {
        $options = ['auto_refresh' => true];

        $headers = ['Authorization' => 'Bearer ' . $this->accessToken];
        $return = ['body' => get_fixture('track')];
        $sessionStub = $this->setupSessionStub();
        $stub = $this->setupStub(
            'GET',
            '/v1/tracks/0eGsygTp906u18L0Oimnem',
            [],
            $headers,
            $return
        );

        $stub->method('api')
            ->will(
                $this->onConsecutiveCalls(
                    $this->throwException(
                        new SpotifyWebAPI\SpotifyWebAPIException('The access token expired', 401)
                    ),
                    $this->returnValue($return)
                )
            );

        $api = new SpotifyWebAPI\SpotifyWebAPI($options, $sessionStub, $stub);
        $response = $api->getTrack('0eGsygTp906u18L0Oimnem');

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testAutoRetryOption()
    {
        $options = ['auto_retry' => true];

        $headers = ['Authorization' => 'Bearer ' . $this->accessToken];
        $return = [
            'body' => get_fixture('track'),
            'headers' => [
                'retry-after' => 3,
            ],
            'status' => 429,
        ];

        $stub = $this->setupStub(
            'GET',
            '/v1/tracks/0eGsygTp906u18L0Oimnem',
            [],
            $headers,
            $return
        );

        $stub->method('api')
            ->will(
                $this->onConsecutiveCalls(
                    $this->throwException(
                        new SpotifyWebAPI\SpotifyWebAPIException('API rate limit exceeded', 429)
                    ),
                    $this->returnValue($return)
                )
            );

        $api = new SpotifyWebAPI\SpotifyWebAPI($options, null, $stub);
        $api->setAccessToken($this->accessToken);

        $response = $api->getTrack('0eGsygTp906u18L0Oimnem');

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testAddMyAlbums()
    {
        $albums = [
            '1oR3KrPIp4CbagPa3PhtPp',
            '6lPb7Eoon6QPbscWbMsk6a',
            'spotify:album:1oR3KrPIp4CbagPa3PhtPp',
        ];

        $expected = json_encode([
            '1oR3KrPIp4CbagPa3PhtPp',
            '6lPb7Eoon6QPbscWbMsk6a',
            '1oR3KrPIp4CbagPa3PhtPp',
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 200];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/albums',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->addMyAlbums($albums)
        );
    }

    public function testAddMyEpisodes()
    {
        $episodes = [
            '0zov0kd6MA3BqT1FKpOeYO',
            '3pLx6LaVQbWl5IfW8nxq56',
            'spotify:episode:6kSGLgKWhBg8AoCzylVfc2',
        ];

        $expected = json_encode([
            '0zov0kd6MA3BqT1FKpOeYO',
            '3pLx6LaVQbWl5IfW8nxq56',
            '6kSGLgKWhBg8AoCzylVfc2',
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 200];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/episodes',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->addMyEpisodes($episodes)
        );
    }

    public function testAddMyShows()
    {
        $shows = [
            '2C6ups0LMt1G8n81XLlkbsPo',
            'spotify:show:5AvwZVawapvyhJUIx71pdJ',
        ];

        $expected = json_encode([
            '2C6ups0LMt1G8n81XLlkbsPo',
            '5AvwZVawapvyhJUIx71pdJ',
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 200];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/shows',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->addMyShows($shows)
        );
    }

    public function testAddMyTracks()
    {
        $tracks = [
            '1id6H6vcwSB9GGv9NXh5cl',
            '3mqRLlD9j92BBv1ueFhJ1l',
            'spotify:track:1id6H6vcwSB9GGv9NXh5cl',
        ];

        $expected = json_encode([
            '1id6H6vcwSB9GGv9NXh5cl',
            '3mqRLlD9j92BBv1ueFhJ1l',
            '1id6H6vcwSB9GGv9NXh5cl',
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 200];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/tracks',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->addMyTracks($tracks)
        );
    }

    public function testAddPlaylistTracks()
    {
        $tracks = [
            'spotify:track:1id6H6vcwSB9GGv9NXh5cl',
            '3mqRLlD9j92BBv1ueFhJ1l',
        ];

        $options = [
            'position' => 0,
        ];

        $expected = json_encode([
            'position' => 0,
            'uris' => [
                'spotify:track:1id6H6vcwSB9GGv9NXh5cl',
                'spotify:track:3mqRLlD9j92BBv1ueFhJ1l',
            ],
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['body' => get_fixture('snapshot-id')];
        $api = $this->setupApi(
            'POST',
            '/v1/playlists/0UZ0Ll4HJHR7yvURYbHJe9/tracks',
            $expected,
            $headers,
            $return
        );

        $this->assertNotFalse(
            $api->addPlaylistTracks(
                'spotify:playlist:0UZ0Ll4HJHR7yvURYbHJe9',
                $tracks,
                $options
            )
        );
    }

    public function testChangeMyDevice()
    {
        $options = ['device_ids' => 'abc123'];

        $expected = json_encode([
            'device_ids' => ['abc123'],
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 204];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/player',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->changeMyDevice($options)
        );
    }

    public function testChangeVolume()
    {
        $options = ['volume_percent' => 100];

        $return = ['status' => 204];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/player/volume?volume_percent=100',
            [],
            [],
            $return
        );

        $this->assertTrue(
            $api->changeVolume($options)
        );
    }

    public function testCreatePlaylist()
    {
        $options = [
            'name' => 'Test playlist',
            'public' => false,
        ];

        $expected = json_encode($options);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['body' => get_fixture('user-playlist')];
        $api = $this->setupApi(
            'POST',
            '/v1/me/playlists',
            $expected,
            $headers,
            $return
        );

        $response = $api->createPlaylist($options);

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testCurrentUserFollows()
    {
        $options = [
            '74ASZWbe4lXaubB36ztrGX',
            'spotify:artist:36QJpDe2go2KgaRleHCDTp',
        ];

        $expected = [
            'ids' => '74ASZWbe4lXaubB36ztrGX,36QJpDe2go2KgaRleHCDTp',
            'type' => 'artist',
        ];

        $return = ['body' => get_fixture('user-follows')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/following/contains',
            $expected,
            [],
            $return
        );

        $response = $api->currentUserFollows('artist', $options);

        $this->assertTrue($response[0]);
    }

    public function testDeleteMyAlbums()
    {
        $albums = [
            '1oR3KrPIp4CbagPa3PhtPp',
            '6lPb7Eoon6QPbscWbMsk6a',
            'spotify:album:1oR3KrPIp4CbagPa3PhtPp'
        ];

        $expected = json_encode([
            '1oR3KrPIp4CbagPa3PhtPp',
            '6lPb7Eoon6QPbscWbMsk6a',
            '1oR3KrPIp4CbagPa3PhtPp'
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 200];
        $api = $this->setupApi(
            'DELETE',
            '/v1/me/albums',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->deleteMyAlbums($albums)
        );
    }

    public function testDeleteMyEpisodes()
    {
        $episodes = [
            '0zov0kd6MA3BqT1FKpOeYO',
            '3pLx6LaVQbWl5IfW8nxq56',
            'spotify:episode:6kSGLgKWhBg8AoCzylVfc2',
        ];

        $expected = json_encode([
            '0zov0kd6MA3BqT1FKpOeYO',
            '3pLx6LaVQbWl5IfW8nxq56',
            '6kSGLgKWhBg8AoCzylVfc2',
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 200];
        $api = $this->setupApi(
            'DELETE',
            '/v1/me/episodes',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->deleteMyEpisodes($episodes)
        );
    }

    public function testDeleteMyShows()
    {
        $shows = [
            '1oR3KrPIp4CbagPa3PhtPp',
            '6lPb7Eoon6QPbscWbMsk6a',
            'spotify:show:1oR3KrPIp4CbagPa3PhtPp'
        ];

        $expected = json_encode([
            '1oR3KrPIp4CbagPa3PhtPp',
            '6lPb7Eoon6QPbscWbMsk6a',
            '1oR3KrPIp4CbagPa3PhtPp'
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 200];
        $api = $this->setupApi(
            'DELETE',
            '/v1/me/shows',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->deleteMyShows($shows)
        );
    }

    public function testDeleteMyTracks()
    {
        $tracks = [
            '1id6H6vcwSB9GGv9NXh5cl',
            '3mqRLlD9j92BBv1ueFhJ1l',
            'spotify:track:1id6H6vcwSB9GGv9NXh5cl',
        ];

        $expected = json_encode([
            '1id6H6vcwSB9GGv9NXh5cl',
            '3mqRLlD9j92BBv1ueFhJ1l',
            '1id6H6vcwSB9GGv9NXh5cl',
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 200];
        $api = $this->setupApi(
            'DELETE',
            '/v1/me/tracks',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->deleteMyTracks($tracks)
        );
    }

    public function testDeletePlaylistTracksTracks()
    {
        $tracks = [
            'tracks' => [
                [
                    'uri' => '1id6H6vcwSB9GGv9NXh5cl',
                    'positions' => 0,
                ],
                [
                    'uri' => '3mqRLlD9j92BBv1ueFhJ1l',
                    'positions' => [1, 2],
                ],
                [
                    'uri' => '4iV5W9uYEdYUVa79Axb7Rh',
                ],
                [
                    'uri' => 'spotify:track:1hChLdk0hBQbapbpVUVlNa',
                ],
                [
                    'uri' => 'spotify:episode:0Q86acNRm6V9GYx55SXKwf',
                ],
            ],
        ];

        $expected = json_encode([
            'snapshot_id' => 'snapshot_id',
            'tracks' => [
                [
                    'uri' => 'spotify:track:1id6H6vcwSB9GGv9NXh5cl',
                    'positions' => [0],
                ],
                [
                    'uri' => 'spotify:track:3mqRLlD9j92BBv1ueFhJ1l',
                    'positions' => [1, 2],
                ],
                [
                    'uri' => 'spotify:track:4iV5W9uYEdYUVa79Axb7Rh',
                ],
                [
                    'uri' => 'spotify:track:1hChLdk0hBQbapbpVUVlNa',
                ],
                [
                    'uri' => 'spotify:episode:0Q86acNRm6V9GYx55SXKwf',
                ],
            ],
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['body' => get_fixture('snapshot-id')];
        $api = $this->setupApi(
            'DELETE',
            '/v1/playlists/0UZ0Ll4HJHR7yvURYbHJe9/tracks',
            $expected,
            $headers,
            $return
        );

        $this->assertNotFalse(
            $api->deletePlaylistTracks(
                'spotify:playlist:0UZ0Ll4HJHR7yvURYbHJe9',
                $tracks,
                'snapshot_id'
            )
        );
    }

    public function testDeletePlaylistTracksPositions()
    {
        $trackPositions = [
            'positions' => [
                0,
                1,
            ],
        ];

        $expected = json_encode([
            'snapshot_id' => 'snapshot_id',
            'positions' => [
                0,
                1,
            ],
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['body' => get_fixture('snapshot-id')];
        $api = $this->setupApi(
            'DELETE',
            '/v1/playlists/0UZ0Ll4HJHR7yvURYbHJe9/tracks',
            $expected,
            $headers,
            $return
        );

        $this->assertNotFalse(
            $api->deletePlaylistTracks(
                'spotify:playlist:0UZ0Ll4HJHR7yvURYbHJe9',
                $trackPositions,
                'snapshot_id'
            )
        );
    }

    public function testFollowArtistsOrUsers()
    {
        $options = [
            'spotify:artist:74ASZWbe4lXaubB36ztrGX',
            '36QJpDe2go2KgaRleHCDTp'
        ];

        $expected = json_encode([
            'ids' => [
                '74ASZWbe4lXaubB36ztrGX',
                '36QJpDe2go2KgaRleHCDTp',
            ],
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 204];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/following?type=artist',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue($api->followArtistsOrUsers(
            'artist',
            $options
        ));
    }

    public function testGetAlbum()
    {
        $options = ['market' => 'SE'];
        $expected = ['market' => 'SE'];

        $return = ['body' => get_fixture('album')];
        $api = $this->setupApi(
            'GET',
            '/v1/albums/7u6zL7kqpgLPISZYXNTgYk',
            $expected,
            [],
            $return
        );

        $response = $api->getAlbum('spotify:album:7u6zL7kqpgLPISZYXNTgYk', $options);

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testGetAlbums()
    {
        $albums = [
            '1oR3KrPIp4CbagPa3PhtPp',
            'spotify:album:6lPb7Eoon6QPbscWbMsk6a',
        ];

        $options = [
            'market' => 'SE'
        ];

        $expected = [
            'ids' => '1oR3KrPIp4CbagPa3PhtPp,6lPb7Eoon6QPbscWbMsk6a',
            'market' => 'SE',
        ];

        $return = ['body' => get_fixture('albums')];
        $api = $this->setupApi(
            'GET',
            '/v1/albums/',
            $expected,
            [],
            $return
        );

        $response = $api->getAlbums($albums, $options);

        $this->assertObjectHasAttribute('albums', $response);
    }

    public function testGetAlbumTracks()
    {
        $options = [
            'limit' => 10,
            'market' => 'SE',
        ];

        $expected = [
            'limit' => 10,
            'market' => 'SE',
        ];

        $return = ['body' => get_fixture('album-tracks')];
        $api = $this->setupApi(
            'GET',
            '/v1/albums/1oR3KrPIp4CbagPa3PhtPp/tracks',
            $expected,
            [],
            $return
        );

        $response = $api->getAlbumTracks('spotify:album:1oR3KrPIp4CbagPa3PhtPp', $options);

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testGetArtist()
    {
        $return = ['body' => get_fixture('artist')];
        $api = $this->setupApi(
            'GET',
            '/v1/artists/36QJpDe2go2KgaRleHCDTp',
            [],
            [],
            $return
        );

        $response = $api->getArtist('spotify:artist:36QJpDe2go2KgaRleHCDTp');

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testGetArtistRelatedArtists()
    {
        $return = ['body' => get_fixture('artist-related-artists')];
        $api = $this->setupApi(
            'GET',
            '/v1/artists/36QJpDe2go2KgaRleHCDTp/related-artists',
            [],
            [],
            $return
        );

        $response = $api->getArtistRelatedArtists('spotify:artist:36QJpDe2go2KgaRleHCDTp');

        $this->assertObjectHasAttribute('artists', $response);
    }

    public function testGetArtists()
    {
        $artists = [
            '6v8FB84lnmJs434UJf2Mrm',
            'spotify:artist:6olE6TJLqED3rqDCT0FyPh',
        ];

        $expected = [
            'ids' => '6v8FB84lnmJs434UJf2Mrm,6olE6TJLqED3rqDCT0FyPh',
        ];

        $return = ['body' => get_fixture('artists')];
        $api = $this->setupApi(
            'GET',
            '/v1/artists/',
            $expected,
            [],
            $return
        );

        $response = $api->getArtists($artists);

        $this->assertObjectHasAttribute('artists', $response);
    }

    public function testGetArtistAlbums()
    {
        $options = [
            'include_groups' => ['album', 'single'],
            'limit' => 10,
            'market' => 'SE',
        ];

        $expected = [
            'include_groups' => 'album,single',
            'market' => 'SE',
            'limit' => 10,
        ];

        $return = ['body' => get_fixture('artist-albums')];
        $api = $this->setupApi(
            'GET',
            '/v1/artists/36QJpDe2go2KgaRleHCDTp/albums',
            $expected,
            [],
            $return
        );

        $response = $api->getArtistAlbums('spotify:artist:36QJpDe2go2KgaRleHCDTp', $options);

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testGetArtistTopTracks()
    {
        $options = ['country' => 'SE'];
        $expected = ['country' => 'SE'];

        $return = ['body' => get_fixture('artist-top-tracks')];
        $api = $this->setupApi(
            'GET',
            '/v1/artists/36QJpDe2go2KgaRleHCDTp/top-tracks',
            $expected,
            [],
            $return
        );

        $response = $api->getArtistTopTracks('spotify:artist:36QJpDe2go2KgaRleHCDTp', $options);

        $this->assertObjectHasAttribute('tracks', $response);
    }

    public function testGetAudioAnalysis()
    {
        $return = ['body' => get_fixture('audio-analysis')];
        $api = $this->setupApi(
            'GET',
            '/v1/audio-analysis/0eGsygTp906u18L0Oimnem',
            [],
            [],
            $return
        );

        $response = $api->getAudioAnalysis('spotify:track:0eGsygTp906u18L0Oimnem');

        $this->assertObjectHasAttribute('audio_analysis', $response);
    }

    public function testGetAudiobook()
    {
        $return = ['body' => get_fixture('audiobook')];
        $api = $this->setupApi(
            'GET',
            '/v1/audiobooks/6QYoIxxar5q4AfdTOGsZqE',
            [],
            [],
            $return
        );

        $response = $api->getAudiobook('spotify:show:6QYoIxxar5q4AfdTOGsZqE');

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testGetAudiobooks()
    {
        $audiobooks = [
            '6QYoIxxar5q4AfdTOGsZqE',
            'spotify:show:4VqPOruhp5EdPBeR92t6lQ',
        ];

        $expected = [
            'ids' => '6QYoIxxar5q4AfdTOGsZqE,4VqPOruhp5EdPBeR92t6lQ',
        ];

        $return = ['body' => get_fixture('audiobooks')];
        $api = $this->setupApi(
            'GET',
            '/v1/audiobooks/',
            $expected,
            [],
            $return
        );

        $response = $api->getAudiobooks($audiobooks);

        $this->assertObjectHasAttribute('audiobooks', $response);
    }

    public function testGetAudioFeatures()
    {
        $track = '0eGsygTp906u18L0Oimnem';

        $return = ['body' => get_fixture('audio-features')];
        $api = $this->setupApi(
            'GET',
            '/v1/audio-features/0eGsygTp906u18L0Oimnem',
            [],
            [],
            $return
        );

        $response = $api->getAudioFeatures($track);

        $this->assertObjectHasAttribute('danceability', $response);
    }

    public function testGetAudioFeaturesArray()
    {
        $tracks = [
            '0eGsygTp906u18L0Oimnem',
        ];

        $expected = [
            'ids' => '0eGsygTp906u18L0Oimnem',
        ];

        $return = ['body' => get_fixture('multiple-audio-features')];
        $api = $this->setupApi(
            'GET',
            '/v1/audio-features',
            $expected,
            [],
            $return
        );

        $response = $api->getAudioFeatures($tracks);

        $this->assertObjectHasAttribute('audio_features', $response);
    }

    public function testGetCategoriesList()
    {
        $options = [
            'country' => 'SE',
            'limit' => 10,
        ];

        $expected = [
            'country' => 'SE',
            'limit' => 10,
        ];

        $return = ['body' => get_fixture('categories-list')];
        $api = $this->setupApi(
            'GET',
            '/v1/browse/categories',
            $expected,
            [],
            $return
        );

        $response = $api->getCategoriesList($options);

        $this->assertObjectHasAttribute('categories', $response);
    }

    public function testGetCategory()
    {
        $options = [
            'country' => 'SE',
            'locale' => 'sv-SE',
        ];

        $return = ['body' => get_fixture('category')];
        $api = $this->setupApi(
            'GET',
            '/v1/browse/categories/party',
            $options,
            [],
            $return
        );

        $response = $api->getCategory('party', $options);

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testGetCategoryPlaylists()
    {
        $options = [
            'country' => 'SE',
            'limit' => 10,
        ];

        $expected = [
            'country' => 'SE',
            'limit' => 10,
        ];

        $return = ['body' => get_fixture('category-playlists')];
        $api = $this->setupApi(
            'GET',
            '/v1/browse/categories/party/playlists',
            $expected,
            [],
            $return
        );

        $response = $api->getCategoryPlaylists('party', $options);

        $this->assertObjectHasAttribute('playlists', $response);
    }

    public function testGetChapter()
    {
        $return = ['body' => get_fixture('chapter')];
        $api = $this->setupApi(
            'GET',
            '/v1/chapters/2IEBhnu61ieYGFRPEJIO40',
            [],
            [],
            $return
        );

        $response = $api->getChapter('spotify:episode:2IEBhnu61ieYGFRPEJIO40');

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testGetChapters()
    {
        $chapters = [
            '2IEBhnu61ieYGFRPEJIO40',
            'spotify:episode:7ouMYWpwJ422jRcDASZB7P',
        ];

        $expected = [
            'ids' => '2IEBhnu61ieYGFRPEJIO40,7ouMYWpwJ422jRcDASZB7P',
        ];

        $return = ['body' => get_fixture('chapters')];
        $api = $this->setupApi(
            'GET',
            '/v1/chapters/',
            $expected,
            [],
            $return
        );

        $response = $api->getChapters($chapters);

        $this->assertObjectHasAttribute('chapters', $response);
    }

    public function testGetEpisode()
    {
        $options = ['market' => 'SE'];
        $expected = ['market' => 'SE'];

        $return = ['body' => get_fixture('episode')];
        $api = $this->setupApi(
            'GET',
            '/v1/episodes/38bS44xjbVVZ3No3ByF1dJ',
            $expected,
            [],
            $return
        );

        $response = $api->getEpisode('spotify:episode:38bS44xjbVVZ3No3ByF1dJ', $options);

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testGetEpisodes()
    {
        $episodes = [
            '0eGsygTp906u18L0Oimnem',
            'spotify:episode:1lDWb6b6ieDQ2xT7ewTC3G',
        ];

        $options = [
            'market' => 'SE',
        ];

        $expected = [
            'ids' => '0eGsygTp906u18L0Oimnem,1lDWb6b6ieDQ2xT7ewTC3G',
            'market' => 'SE',
        ];

        $return = ['body' => get_fixture('episodes')];
        $api = $this->setupApi(
            'GET',
            '/v1/episodes/',
            $expected,
            [],
            $return
        );

        $response = $api->getEpisodes($episodes, $options);

        $this->assertObjectHasAttribute('episodes', $response);
    }

    public function testGetFeaturedPlaylists()
    {
        $options = [
            'country' => 'SE',
            'limit' => 10,
        ];

        $expected = [
            'country' => 'SE',
            'limit' => 10,
        ];

        $return = ['body' => get_fixture('featured-playlists')];
        $api = $this->setupApi(
            'GET',
            '/v1/browse/featured-playlists',
            $expected,
            [],
            $return
        );

        $response = $api->getFeaturedPlaylists($options);

        $this->assertObjectHasAttribute('playlists', $response);
    }

    public function testGetGenreSeeds()
    {
        $return = ['body' => get_fixture('available-genre-seeds')];
        $api = $this->setupApi(
            'GET',
            '/v1/recommendations/available-genre-seeds',
            [],
            [],
            $return
        );

        $response = $api->getGenreSeeds();

        $this->assertObjectHasAttribute('genres', $response);
    }

    public function testGetLastResponse()
    {
        $return = ['body' => get_fixture('track')];
        $api = $this->setupApi(
            'GET',
            '/v1/tracks/7EjyzZcbLxW7PaaLua9Ksb',
            [],
            [],
            $return
        );

        $api->getTrack('7EjyzZcbLxW7PaaLua9Ksb');

        $response = $api->getLastResponse();

        $this->assertArrayHasKey('body', $response);
    }

    public function testGetMarkets()
    {
        $return = ['body' => get_fixture('markets')];
        $api = $this->setupApi(
            'GET',
            '/v1/markets',
            [],
            [],
            $return
        );

        $response = $api->getMarkets();

        $this->assertObjectHasAttribute('markets', $response);
    }

    public function testGetMultipleAudioFeatures()
    {
        $tracks = [
            '0eGsygTp906u18L0Oimnem',
            'spotify:track:1lDWb6b6ieDQ2xT7ewTC3G',
        ];

        $expected = [
            'ids' => '0eGsygTp906u18L0Oimnem,1lDWb6b6ieDQ2xT7ewTC3G',
        ];

        $return = ['body' => get_fixture('multiple-audio-features')];
        $api = $this->setupApi(
            'GET',
            '/v1/audio-features',
            $expected,
            [],
            $return
        );

        $response = $api->getMultipleAudioFeatures($tracks);

        $this->assertObjectHasAttribute('audio_features', $response);
    }

    public function testGetMyCurrentTrack()
    {
        $options = [
            'market' => 'SE',
            'additional_types' => ['track', 'episode'],
        ];

        $expected = [
            'market' => 'SE',
            'additional_types' => 'track,episode',
        ];

        $return = ['body' => get_fixture('user-current-track')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/player/currently-playing',
            $expected,
            [],
            $return
        );

        $response = $api->getMyCurrentTrack($options);

        $this->assertObjectHasAttribute('item', $response);
    }

    public function testGetMyDevices()
    {
        $return = ['body' => get_fixture('user-devices')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/player/devices',
            [],
            [],
            $return
        );

        $response = $api->getMyDevices();

        $this->assertObjectHasAttribute('devices', $response);
    }

    public function testGetMyCurrentPlaybackInfo()
    {
        $options = [
            'market' => 'SE',
            'additional_types' => ['track', 'episode'],
        ];

        $expected = [
            'market' => 'SE',
            'additional_types' => 'track,episode',
        ];

        $return = ['body' => get_fixture('user-current-playback-info')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/player',
            $expected,
            [],
            $return
        );

        $response = $api->getMyCurrentPlaybackInfo($options);

        $this->assertObjectHasAttribute('item', $response);
    }

    public function testGetMyPlaylists()
    {
        $options = ['limit' => 10];
        $expected = ['limit' => 10];

        $return = ['body' => get_fixture('my-playlists')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/playlists',
            $expected,
            [],
            $return
        );

        $response = $api->getMyPlaylists($options);

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testGetMyQueue()
    {
        $return = ['body' => get_fixture('my-queue')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/player/queue',
            [],
            [],
            $return
        );

        $response = $api->getMyQueue();

        $this->assertObjectHasAttribute('currently_playing', $response);
        $this->assertObjectHasAttribute('queue', $response);
    }

    public function testGetMyRecentTracks()
    {
        $options = ['limit' => '2'];
        $expected = ['limit' => '2'];

        $return = ['body' => get_fixture('recently-played')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/player/recently-played',
            $expected,
            [],
            $return
        );

        $response = $api->getMyRecentTracks($options);

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testGetMySavedAlbums()
    {
        $options = [
            'limit' => 10,
            'market' => 'SE',
        ];

        $expected = [
            'limit' => 10,
            'market' => 'SE',
        ];

        $return = ['body' => get_fixture('user-albums')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/albums',
            $expected,
            [],
            $return
        );

        $response = $api->getMySavedAlbums($options);

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testGetMySavedEpisodes()
    {
        $options = [
            'limit' => 10,
            'market' => 'SE',
        ];

        $expected = [
            'limit' => 10,
            'market' => 'SE',
        ];

        $return = ['body' => get_fixture('user-episodes')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/episodes',
            $expected,
            [],
            $return
        );

        $response = $api->getMySavedEpisodes($options);

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testGetMySavedShows()
    {
        $options = ['limit' => 10];
        $expected = ['limit' => 10];

        $return = ['body' => get_fixture('user-shows')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/shows',
            $expected,
            [],
            $return
        );

        $response = $api->getMySavedShows($options);

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testGetMySavedTracks()
    {
        $options = [
            'limit' => 10,
            'market' => 'SE',
        ];

        $expected = [
            'limit' => 10,
            'market' => 'SE',
        ];

        $return = ['body' => get_fixture('user-tracks')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/tracks',
            $expected,
            [],
            $return
        );

        $response = $api->getMySavedTracks($options);

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testGetMyTop()
    {
        $options = [
            'limit' => 10,
            'time_range' => 'long_term',
        ];

        $expected = [
            'limit' => 10,
            'time_range' => 'long_term',
        ];

        $return = ['body' => get_fixture('top-artists-and-tracks')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/top/artists',
            $expected,
            [],
            $return
        );

        $response = $api->getMyTop('artists', $options);

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testGetNewReleases()
    {
        $options = [
            'country' => 'SE',
            'limit' => 10,
        ];

        $expected = [
            'country' => 'SE',
            'limit' => 10,
        ];

        $return = ['body' => get_fixture('albums')];
        $api = $this->setupApi(
            'GET',
            '/v1/browse/new-releases',
            $expected,
            [],
            $return
        );

        $response = $api->getNewReleases($options);

        $this->assertObjectHasAttribute('albums', $response);
    }

    public function testGetRecommendations()
    {
        $options = [
            'limit' => 10,
            'seed_tracks' => ['0eGsygTp906u18L0Oimnem', '1lDWb6b6ieDQ2xT7ewTC3G'],
        ];

        $expected = [
            'limit' => 10,
            'seed_tracks' => '0eGsygTp906u18L0Oimnem,1lDWb6b6ieDQ2xT7ewTC3G',
        ];

        $return = ['body' => get_fixture('recommendations')];
        $api = $this->setupApi(
            'GET',
            '/v1/recommendations',
            $expected,
            [],
            $return
        );

        $response = $api->getRecommendations($options);

        $this->assertObjectHasAttribute('seeds', $response);
    }

    public function testGetRequest()
    {
        $api = new SpotifyWebAPI\SpotifyWebAPI();

        $this->assertInstanceOf(SpotifyWebAPI\Request::class, $api->getRequest());
    }

    public function testGetShow()
    {
        $options = ['market' => 'SE'];
        $expected = ['market' => 'SE'];

        $return = ['body' => get_fixture('show')];
        $api = $this->setupApi(
            'GET',
            '/v1/shows/38bS44xjbVVZ3No3ByF1dJ',
            $expected,
            [],
            $return
        );

        $response = $api->getShow('spotify:show:38bS44xjbVVZ3No3ByF1dJ', $options);

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testGetShowEpisodes()
    {
        $options = [
            'limit' => 10,
            'market' => 'SE',
        ];

        $expected = [
            'limit' => 10,
            'market' => 'SE',
        ];

        $return = ['body' => get_fixture('show-episodes')];
        $api = $this->setupApi(
            'GET',
            '/v1/shows/38bS44xjbVVZ3No3ByF1dJ/episodes',
            $expected,
            [],
            $return
        );

        $response = $api->getShowEpisodes('spotify:show:38bS44xjbVVZ3No3ByF1dJ', $options);

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testGetShows()
    {
        $shows = [
            '5CfCWKI5pZ28U0uOzXkDHe',
            'spotify:show:5as3aKmN2k11yfDDDSrvaZ',
        ];

        $options = [
            'market' => 'SE',
        ];

        $expected = [
            'ids' => '5CfCWKI5pZ28U0uOzXkDHe,5as3aKmN2k11yfDDDSrvaZ',
            'market' => 'SE',
        ];

        $return = ['body' => get_fixture('shows')];
        $api = $this->setupApi(
            'GET',
            '/v1/shows/',
            $expected,
            [],
            $return
        );

        $response = $api->getShows($shows, $options);

        $this->assertObjectHasAttribute('shows', $response);
    }

    public function testGetTrack()
    {
        $options = ['market' => 'SE'];
        $expected = ['market' => 'SE'];

        $return = ['body' => get_fixture('track')];
        $api = $this->setupApi(
            'GET',
            '/v1/tracks/0eGsygTp906u18L0Oimnem',
            $expected,
            [],
            $return
        );

        $response = $api->getTrack('spotify:track:0eGsygTp906u18L0Oimnem', $options);

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testGetTracks()
    {
        $tracks = [
            '0eGsygTp906u18L0Oimnem',
            'spotify:track:1lDWb6b6ieDQ2xT7ewTC3G',
        ];

        $options = [
            'market' => 'SE',
        ];

        $expected = [
            'ids' => '0eGsygTp906u18L0Oimnem,1lDWb6b6ieDQ2xT7ewTC3G',
            'market' => 'SE',
        ];

        $return = ['body' => get_fixture('tracks')];
        $api = $this->setupApi(
            'GET',
            '/v1/tracks/',
            $expected,
            [],
            $return
        );

        $response = $api->getTracks($tracks, $options);

        $this->assertObjectHasAttribute('tracks', $response);
    }

    public function testGetUser()
    {
        $return = ['body' => get_fixture('user')];
        $api = $this->setupApi(
            'GET',
            '/v1/users/mcgurk',
            [],
            [],
            $return
        );

        $response = $api->getUser('spotify:user:mcgurk');

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testGetUserFollowedArtists()
    {
        $options = [
            'limit' => 10,
        ];

        $expected = [
            'limit' => 10,
            'type' => 'artist',
        ];

        $return = ['body' => get_fixture('user-followed-artists')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/following',
            $expected,
            [],
            $return
        );

        $response = $api->getUserFollowedArtists($options);

        $this->assertObjectHasAttribute('artists', $response);
    }

    public function testGetPlaylist()
    {
        $options = [
            'fields' => ['id', 'uri'],
            'market' => 'SE',
        ];

        $expected = [
            'fields' => 'id,uri',
            'market' => 'SE',
        ];

        $return = ['body' => get_fixture('user-playlist')];
        $api = $this->setupApi(
            'GET',
            '/v1/playlists/0UZ0Ll4HJHR7yvURYbHJe9',
            $expected,
            [],
            $return
        );

        $response = $api->getPlaylist(
            'spotify:playlist:0UZ0Ll4HJHR7yvURYbHJe9',
            $options
        );

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testGetPlaylistImage()
    {
        $return = ['body' => get_fixture('playlist-cover-image')];
        $api = $this->setupApi(
            'GET',
            '/v1/playlists/3cEYpjA9oz9GiPac4AsH4n/images',
            [],
            [],
            $return
        );

        $response = $api->getPlaylistImage(
            'spotify:playlist:3cEYpjA9oz9GiPac4AsH4n'
        );

        $this->assertObjectHasAttribute('url', $response);
    }

    public function testGetUserPlaylists()
    {
        $options = ['limit' => 10];
        $expected = ['limit' => 10];

        $return = ['body' => get_fixture('user-playlists')];
        $api = $this->setupApi(
            'GET',
            '/v1/users/mcgurk/playlists',
            $expected,
            [],
            $return
        );

        $response = $api->getUserPlaylists('spotify:user:mcgurk', $options);

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testGetPlaylistTracks()
    {
        $options = [
            'fields' => ['id', 'uri'],
            'limit' => 10,
            'market' => 'SE',
        ];

        $expected = [
            'fields' => 'id,uri',
            'limit' => 10,
            'market' => 'SE',
        ];

        $return = ['body' => get_fixture('user-playlist-tracks')];
        $api = $this->setupApi(
            'GET',
            '/v1/playlists/0UZ0Ll4HJHR7yvURYbHJe9/tracks',
            $expected,
            [],
            $return
        );

        $response = $api->getPlaylistTracks(
            'spotify:playlist:0UZ0Ll4HJHR7yvURYbHJe9',
            $options
        );

        $this->assertObjectHasAttribute('items', $response);
    }

    public function testMe()
    {
        $return = ['body' => get_fixture('user')];
        $api = $this->setupApi(
            'GET',
            '/v1/me',
            [],
            [],
            $return
        );

        $response = $api->me();

        $this->assertObjectHasAttribute('id', $response);
    }

    public function testMyAlbumsContains()
    {
        $albums = [
            '1oR3KrPIp4CbagPa3PhtPp',
            '6lPb7Eoon6QPbscWbMsk6a',
            'spotify:album:1oR3KrPIp4CbagPa3PhtPp',
        ];

        $expected = [
            'ids' => '1oR3KrPIp4CbagPa3PhtPp,6lPb7Eoon6QPbscWbMsk6a,1oR3KrPIp4CbagPa3PhtPp',
        ];

        $return = ['body' => get_fixture('user-albums-contains')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/albums/contains',
            $expected,
            [],
            $return
        );

        $response = $api->myAlbumsContains($albums);

        $this->assertTrue($response[0]);
    }

    public function testMyEpisodesContains()
    {
        $episodes = [
            '0zov0kd6MA3BqT1FKpOeYO',
            '3pLx6LaVQbWl5IfW8nxq56',
            'spotify:episode:6kSGLgKWhBg8AoCzylVfc2',
        ];

        $expected = [
            'ids' => '0zov0kd6MA3BqT1FKpOeYO,3pLx6LaVQbWl5IfW8nxq56,6kSGLgKWhBg8AoCzylVfc2',
        ];

        $return = ['body' => get_fixture('user-episodes-contains')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/episodes/contains',
            $expected,
            [],
            $return
        );

        $response = $api->myEpisodesContains($episodes);

        $this->assertTrue($response[0]);
    }

    public function testMyShowsContains()
    {
        $shows = [
            '5AvwZVawapvyhJUIx71pdJ',
            '2C6ups0LMt1G8n81XLlkbsPo',
            'spotify:show:2C5AvwZVawapvyhJUIx71pdJ',
        ];

        $expected = [
            'ids' => '5AvwZVawapvyhJUIx71pdJ,2C6ups0LMt1G8n81XLlkbsPo,2C5AvwZVawapvyhJUIx71pdJ',
        ];

        $return = ['body' => get_fixture('user-shows-contains')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/shows/contains',
            $expected,
            [],
            $return
        );

        $response = $api->myShowsContains($shows);

        $this->assertTrue($response[0]);
    }

    public function testMyTracksContains()
    {
        $tracks = [
            '1id6H6vcwSB9GGv9NXh5cl',
            '3mqRLlD9j92BBv1ueFhJ1l',
            'spotify:track:1id6H6vcwSB9GGv9NXh5cl',
        ];

        $expected = [
            'ids' => '1id6H6vcwSB9GGv9NXh5cl,3mqRLlD9j92BBv1ueFhJ1l,1id6H6vcwSB9GGv9NXh5cl',
        ];

        $return = ['body' => get_fixture('user-tracks-contains')];
        $api = $this->setupApi(
            'GET',
            '/v1/me/tracks/contains',
            $expected,
            [],
            $return
        );

        $response = $api->myTracksContains($tracks);

        $this->assertTrue($response[0]);
    }

    public function testNext()
    {
        $return = ['status' => 204];
        $api = $this->setupApi(
            'POST',
            '/v1/me/player/next?device_id=abc123',
            [],
            [],
            $return
        );

        $this->assertTrue(
            $api->next('abc123')
        );
    }

    public function testPause()
    {
        $return = ['status' => 204];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/player/pause?device_id=abc123',
            [],
            [],
            $return
        );

        $this->assertTrue(
            $api->pause('abc123')
        );
    }

    public function testPlay()
    {
        $options = [
            'context_uri' => 'spotify:album:1oR3KrPIp4CbagPa3PhtPp',
        ];

        $expected = json_encode($options);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 204];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/player/play?device_id=abc123',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->play('abc123', $options)
        );
    }

    public function testPrevious()
    {
        $return = ['status' => 204];
        $api = $this->setupApi(
            'POST',
            '/v1/me/player/previous?device_id=abc123',
            [],
            [],
            $return
        );

        $this->assertTrue(
            $api->previous('abc123')
        );
    }

    public function testQueueId()
    {
        $return = ['status' => 204];
        $api = $this->setupApi(
            'POST',
            '/v1/me/player/queue?uri=spotify:track:6ek0XS2AUbzrHS0B5wPNcU&device_id=abc123',
            [],
            [],
            $return
        );

        $this->assertTrue(
            $api->queue('6ek0XS2AUbzrHS0B5wPNcU', 'abc123')
        );
    }

    public function testQueueUri()
    {
        $return = ['status' => 204];
        $api = $this->setupApi(
            'POST',
            '/v1/me/player/queue?uri=spotify:episode:0Q86acNRm6V9GYx55SXKwf&device_id=abc123',
            [],
            [],
            $return
        );

        $this->assertTrue(
            $api->queue('spotify:episode:0Q86acNRm6V9GYx55SXKwf', 'abc123')
        );
    }

    public function testReorderPlaylistTracks()
    {
        $options = [
            'insert_before' => 20,
            'range_length' => 5,
            'range_start' => 0,
        ];

        $expected = json_encode([
            'insert_before' => 20,
            'range_length' => 5,
            'range_start' => 0,
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['body' => get_fixture('snapshot-id')];
        $api = $this->setupApi(
            'PUT',
            '/v1/playlists/0UZ0Ll4HJHR7yvURYbHJe9/tracks',
            $expected,
            $headers,
            $return
        );

        $this->assertNotFalse(
            $api->reorderPlaylistTracks(
                'spotify:playlist:0UZ0Ll4HJHR7yvURYbHJe9',
                $options
            )
        );
    }

    public function testRepeat()
    {
        $return = ['status' => 204];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/player/repeat?state=track',
            [],
            [],
            $return
        );

        $this->assertTrue(
            $api->repeat([
                'state' => 'track',
            ])
        );
    }

    public function testReplacePlaylistTracks()
    {
        $tracks = [
            '1id6H6vcwSB9GGv9NXh5cl',
            'spotify:track:3mqRLlD9j92BBv1ueFhJ1l',
        ];

        $expected = json_encode([
            'uris' => [
                'spotify:track:1id6H6vcwSB9GGv9NXh5cl',
                'spotify:track:3mqRLlD9j92BBv1ueFhJ1l',
            ],
        ]);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 201];
        $api = $this->setupApi(
            'PUT',
            '/v1/playlists/0UZ0Ll4HJHR7yvURYbHJe9/tracks',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->replacePlaylistTracks(
                'spotify:playlist:0UZ0Ll4HJHR7yvURYbHJe9',
                $tracks
            )
        );
    }

    public function testSearch()
    {
        $types = [
            'album',
            'artist',
        ];

        $options = [
            'limit' => 10,
        ];

        $expected = [
            'limit' => 10,
            'q' => 'blur',
            'type' => 'album,artist',
        ];

        $return = ['body' => get_fixture('search-album')];
        $api = $this->setupApi(
            'GET',
            '/v1/search',
            $expected,
            [],
            $return
        );

        $response = $api->search(
            'blur',
            $types,
            $options
        );

        $this->assertObjectHasAttribute('albums', $response);
    }

    public function testSeek()
    {
        $return = ['status' => 204];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/player/seek?position_ms=5000',
            [],
            [],
            $return
        );

        $this->assertTrue(
            $api->seek([
                'position_ms' => 5000,
            ])
        );
    }

    public function testSetReasonOnSpotifyWebAPIException()
    {
        $expectedReason = 'NO_ACTIVE_DEVICE';
        $exception = new \SpotifyWebAPI\SpotifyWebAPIException();
        $exception->setReason($expectedReason);

        $this->assertEquals($expectedReason, $exception->getReason());
    }

    public function testShuffle()
    {
        $return = ['status' => 204];
        $api = $this->setupApi(
            'PUT',
            '/v1/me/player/shuffle?state=false',
            [],
            [],
            $return
        );

        $this->assertTrue(
            $api->shuffle([
                'state' => false,
            ])
        );
    }

    public function testUnfollowArtistsOrUsers()
    {
        $options = [
            'ids' => [
                '74ASZWbe4lXaubB36ztrGX',
                '36QJpDe2go2KgaRleHCDTp',
            ],
        ];

        $expected = json_encode($options);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 204];
        $api = $this->setupApi(
            'DELETE',
            '/v1/me/following?type=artist',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->unFollowArtistsOrUsers(
                'artist',
                ['74ASZWbe4lXaubB36ztrGX', 'spotify:artist:36QJpDe2go2KgaRleHCDTp']
            )
        );
    }

    public function testUpdatePlaylist()
    {
        $options = [
            'name' => 'New playlist name',
            'public' => false,
        ];

        $expected = json_encode($options);

        $headers = ['Content-Type' => 'application/json'];
        $return = ['status' => 200];
        $api = $this->setupApi(
            'PUT',
            '/v1/playlists/0UZ0Ll4HJHR7yvURYbHJe9',
            $expected,
            $headers,
            $return
        );

        $this->assertTrue(
            $api->updatePlaylist(
                'spotify:playlist:0UZ0Ll4HJHR7yvURYbHJe9',
                $options
            )
        );
    }

    public function testUpdatePlaylistImage()
    {
        $imageData = 'dGVzdA==';

        $return = ['status' => 202];
        $api = $this->setupApi(
            'PUT',
            '/v1/playlists/0UZ0Ll4HJHR7yvURYbHJe9/images',
            $imageData,
            [],
            $return
        );

        $this->assertTrue(
            $api->updatePlaylistImage(
                'spotify:playlist:0UZ0Ll4HJHR7yvURYbHJe9',
                $imageData
            )
        );
    }

    public function testUsersFollowPlaylist()
    {
        $options = [
            'ids' => [
                'possan',
                'spotify:user:elogain',
            ],
        ];

        $expected = [
            'ids' => 'possan,elogain',
        ];

        $return = ['body' => get_fixture('users-follows-playlist')];
        $api = $this->setupApi(
            'GET',
            '/v1/playlists/0UZ0Ll4HJHR7yvURYbHJe9/followers/contains',
            $expected,
            [],
            $return
        );

        $response = $api->usersFollowPlaylist(
            'spotify:playlist:0UZ0Ll4HJHR7yvURYbHJe9',
            $options
        );

        $this->assertTrue($response[0]);
    }
}
