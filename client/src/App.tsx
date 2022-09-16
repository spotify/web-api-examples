import React, { useCallback, useEffect, useRef, useState } from 'react';
import { ajax } from "jquery";
import './App.css';
import { Button, Input, Label } from 'reactstrap';

function usePrevious<T>(value: T) {
  const ref = useRef<T>();
  useEffect(() => {
    ref.current = value; //assign the value of ref to the argument
  },[value]); //this code will run when the value of 'value' changes
  return ref.current; //in the end, return the current ref value.
}

function App() {
  const [accessToken, setAccessToken] = useState('');
  const previousAccessToken = usePrevious(accessToken);

  const [playlists, setPlaylists] = useState<{ name: string, external_urls: { spotify: string }, tracks: { href: string }; }[]>([]);
  const [playlistsTracks, setPlaylistsTracks] = useState<{ playlist: { name: string, external_urls: { spotify: string } }, tracks: { name: string, artists: { name: string }[] }[] }[]>([]);

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

        if (!data.access_token) {
            // render initial screen
            $('#login').show();
            $('#loggedin').hide();
        }
      });
    } else {
      ajax({
        url: 'https://api.spotify.com/v1/me',
        headers: {
          'Authorization': 'Bearer ' + accessToken
        },
        success: function(response) {
          $('#login').hide();
          $('#loggedin').show();
          recursivelyGetPlaylists();
        },
        error: function(response) {
          if (response.status === 401) {
            $('#login').show();
            $('#loggedin').hide();
          }
        }
      });
    }
  }, [accessToken])  // eslint-disable-line react-hooks/exhaustive-deps

  const memoizedGetPlaylistTracks = useCallback((index: number) => {
    const rateLimitWindowSeconds = 30;

    if (index >= playlists.length) {
      setLoading(false);
      return;
    }

    const url = playlists[index].tracks.href;
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
      <div id="login">
        <h1>Spotify In-Playlist Search</h1>
        <a href="/login" className="btn btn-primary">Log in with Spotify</a>
      </div>
      <div id="loggedin">
        <div>
          <Label for="song-name">Song Name</Label>
          <Input name="song-name" id="song-name-field" type="text" value={searchTerm} onInput={(e) => setSearchTerm((e.target as HTMLInputElement).value)} />
        </div>
        <div>
          <Button id="reload-playlists-button" onClick={() => reloadPlaylists()}>Reload Playlists</Button>
        </div>
        <h3>Matching Playlists
          <div>
            <small id="loading">
              {loading ? '(Still loading more playlists)' : '(Done loading)'}
            </small>
          </div>
        </h3>
        <div id="matching-playlists-links">
          {matchingPlaylists.map(({ playlist, tracks }) => (
            <div>
              <a target="_blank" href={playlist?.url} rel="noreferrer">{playlist?.name} - {(tracks || []).map(({ name }) => name).join(' - ')}</a>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

export default App;
