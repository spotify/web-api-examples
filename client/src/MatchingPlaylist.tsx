import { useState } from "react";
import { Button, Table } from 'reactstrap';

function MatchingPlaylist(
  {
    playlist,
    tracks,
    playPlaylistTrack,
  } : {
    playlist?: { url: string, name: string, uri: string },
    tracks?: { name: string, uri: string, album: string, artists: string[], trackIndexInPlaylist: number }[]
    playPlaylistTrack: (songUri: string, offsetPosition: number) => void
  }
) {
  const [expanded, setExpanded] = useState(false);

  return (
    <div>
      <a target="_blank" href={playlist?.url} rel="noreferrer">{playlist?.name}:</a>
      <Button onClick={() => setExpanded(expanded => !expanded)} color='link' className="py-0 border-0 align-baseline">See {expanded ? 'less' : 'more'} song results</Button>
      {expanded ? (
        <div className="ml-1">
          <Table>
            <thead>
              <tr>
                <th></th>
                <th>
                  Track # in Playlist
                </th>
                <th>
                  Song
                </th>
                <th>
                  Artists
                </th>
                <th>
                  Album
                </th>
              </tr>
            </thead>
            <tbody>
              {(tracks || []).map(({ name, uri, album, artists, trackIndexInPlaylist }) => (
                <tr>
                  <td><Button onClick={() => playPlaylistTrack(uri, trackIndexInPlaylist)} color="primary">
                    Play
                  </Button></td>
                  <td>
                    {trackIndexInPlaylist + 1}
                  </td>
                  <td>
                    {name}
                  </td>
                  <td>
                    {artists.join(', ')}
                  </td>
                  <td>
                    {album}
                  </td>
                </tr>
              ))}
            </tbody>
          </Table>
        </div>
      ) : (
        <span>
          {tracks && tracks[0] && (
            <span>{tracks[0].name} - {tracks[0].artists.join(', ')} - {tracks[0].album}</span>
          )}
        </span>
      )}
    </div>
  )
}

export default MatchingPlaylist;