import React, { useCallback, useEffect, useRef, useState } from 'react';
import { ajax } from "jquery";
import './App.css';
import { Button, Input, Label, Progress, Spinner } from 'reactstrap';

/* TODO: store previous versions and don't reload same version
Use the snapshot_id
Playlist APIs expose a snapshot_id that corresponds to the version of the
playlist that you are working with. Downloading a playlist can be expensive
so some apps may want to store and refer to the snapshot_id to avoid refreshing
an entire playlist that has not changed. You can learn more about snapshot_id in
our Working with Playlists guide.
https://developer.spotify.com/documentation/general/guides/working-with-playlists/
*/

function usePrevious<T>(value: T) {
  const ref = useRef<T>();
  useEffect(() => {
    ref.current = value; //assign the value of ref to the argument
  },[value]); //this code will run when the value of 'value' changes
  return ref.current; //in the end, return the current ref value.
}

// TODO: use retry-after instead of this constant
/* The header of the 429 response will normally include a Retry-After header
with a value in seconds. Consider waiting for the number of seconds specified in
Retry-After before your app calls the Web API again.*/
const rateLimitWindowSeconds = 30;

function App() {
  const [accessToken, setAccessToken] = useState('');
  const previousAccessToken = usePrevious(accessToken);

  const [profileInfo, setProfileInfo] = useState<{ display_name?: string, external_urls?: { spotify: string }}>({});

  const [playlists, setPlaylists] = useState<{ name: string, external_urls: { spotify: string }, tracks: { href: string }; }[]>([]);
  const [playlistsTracks, setPlaylistsTracks] = useState<{ playlist: { name: string, external_urls: { spotify: string } }, tracks: { name: string, artists: { name: string }[] }[] }[]>([]);

  const [playlistIndex, setPlaylistIndex] = useState(0);

  const [loading, setLoading] = useState(true);
  const [loadingPlaylists, setLoadingPlaylists] = useState(true);

  const [searchTerm, setSearchTerm] = useState('');
  const [matchingPlaylists, setMatchingPlaylists] = useState<{ playlist?: { name: string, url: string }; tracks?: { name: string, artists: string[] }[]; }[]>([]);

  useEffect(() => {
    if (searchTerm === '') return;

    setMatchingPlaylists(playlistsTracks.map(({playlist, tracks}) => {
      if (!playlist) return {};
      return {
        playlist: {
          name: playlist.name,
          url: playlist.external_urls.spotify
        },
        tracks: tracks.map(({name, artists}: { name: string, artists: { name: string }[] }) => {
          return {
            name,
            artists: artists.map(({name}) => name),
          };
        }).filter(({name, artists}: { name: string, artists: string[] }) => (`${name} ${artists.join(' ')}`.toLowerCase().includes(searchTerm))) // TODO: add search by album name
      };
    }).filter(({playlist, tracks}) => !!playlist && tracks.length > 0));
  }, [searchTerm, playlistsTracks])

  useEffect(() => {
    if (previousAccessToken) return;

    if (!accessToken){
      ajax({
        url: '/refresh_token',
        xhrFields: { withCredentials: true },
      }).done(function(data) {
        setAccessToken(data.access_token);
      });
    } else {
      ajax({
        url: 'https://api.spotify.com/v1/me',
        headers: {
          'Authorization': 'Bearer ' + accessToken
        },
        success: function(response) {
          setProfileInfo(response);
          recursivelyGetPlaylists();
        },
        error: function(response) {
          if (response.status === 401) {
            setAccessToken('');
          }
        }
      });
    }
  }, [accessToken])  // eslint-disable-line react-hooks/exhaustive-deps

  const memoizedGetPlaylistTracks = useCallback((index: number) => {
    if (index >= playlists.length) {
      setLoading(false);
      return;
    }

    const url = playlists[index].tracks.href;
    setPlaylistIndex(index);
    ajax({
      url,
      headers: {
        'Authorization': 'Bearer ' + accessToken
      },
      success: function(response) {
        if (response.items && response.items[0]) {
          setPlaylistsTracks(playlistsTracks => [...playlistsTracks, {
            playlist: playlists[index],
            tracks: response.items.map(({ track }: { track: { name: string, artists: {}[] } }) => track).filter((track: {}) => !!track),
          }]);
        }
        // TODO: recurse:
        // if (response.next) recursivelyGetPlaylists(response.next);
        // else {
        //   playlistsTracksUrls = playlists.map(({ tracks }) => tracks.href);
        //   console.log(playlistsTracksUrls);
        // }
        memoizedGetPlaylistTracks(index + 1);
      },
      error: function(response) {
        // TODO: handle other errors
        if (response.status === 429) {
          setTimeout(() => memoizedGetPlaylistTracks(index), rateLimitWindowSeconds*1000)
        } else {
          memoizedGetPlaylistTracks(index + 1);
        }
      }
    });
  }, [accessToken, playlists])

  useEffect(() => {
    if (!loadingPlaylists && playlists.length > 0) memoizedGetPlaylistTracks(0);
  }, [loadingPlaylists, playlists, memoizedGetPlaylistTracks])

  function recursivelyGetPlaylists(url = 'https://api.spotify.com/v1/me/playlists') {
    ajax({
      url,
      headers: {
        'Authorization': 'Bearer ' + accessToken
      },
      success: function(response) {
        if (response.items) {
          setPlaylists(playlists => [...playlists, ...response.items]);
          if (response.next) recursivelyGetPlaylists(response.next);
          else {
            setLoadingPlaylists(false);
          }
        } else console.error('response to recursivelyGetPlaylists had no items')
      },
      error: function(response) {
        // TODO: handle other errors
        if (response.status === 429) {
          setTimeout(() => recursivelyGetPlaylists(url), rateLimitWindowSeconds*1000)
        } else {
          console.error('unknown error in recursivelyGetPlaylists', response)
        }
      }
    });
  }

  const reloadPlaylists = () => {
    setLoading(true);
    setLoadingPlaylists(true);
    setPlaylists([]);
    setPlaylistsTracks([]);
    recursivelyGetPlaylists();
  }

  return (
    <div className="container">
      {
        accessToken ? (
          <div>
            {
              (profileInfo.external_urls?.spotify && profileInfo.display_name) && (
                <h1>Logged in as <a target="_blank" href={profileInfo.external_urls?.spotify} rel="noreferrer">{profileInfo.display_name}</a></h1>
              )
            }
            <div>
              <Label for="song-name">Song Name</Label>
              <Input name="song-name" id="song-name-field" type="text" value={searchTerm} onInput={(e) => setSearchTerm((e.target as HTMLInputElement).value)} />
            </div>
            <div>
              <Button id="reload-playlists-button" onClick={() => reloadPlaylists()}>Reload Playlists</Button>
            </div>
            <h3>Matching Playlists</h3>
            {
              loading && (
                <div>
                  <small>
                    (Still loading more playlists)
                  </small>
                  <Spinner
                    size="sm"
                    className="ml-2"
                    style={{ color: '#1DB954' }}
                  >
                  </Spinner>
                  <div className="text-center">
                    Searching {playlistIndex} / {playlists.length} playlists
                  </div>
                  <Progress
                    animated
                    value={playlistIndex}
                    max={playlists.length}
                    barStyle={{ backgroundColor: '#1DB954' }}
                  />
                </div>
              )
            }
            <div id="matching-playlists-links">
              {matchingPlaylists.map(({ playlist, tracks }) => (
                <div>
                  <a target="_blank" href={playlist?.url} rel="noreferrer">{playlist?.name} - {(tracks || []).map(({ name }) => name).join(' - ')}</a>
                </div>
              ))}
            </div>
          </div>
        ) : (
        <div>
          <h1>Spotify In-Playlist Search</h1>
          <a href="/login" className="btn btn-primary">Log in with Spotify</a>
        </div>
        )
      }
    </div>
  );
}

export default App;
