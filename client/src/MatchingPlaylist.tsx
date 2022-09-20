import { useState } from "react";
import { Button, Table } from 'reactstrap';

function MatchingPlaylist(
  {
    playlist,
    tracks,
  } : {
    playlist?: { url: string, name: string },
    tracks?: { name: string, album: string, trackIndexInPlaylist: number }[]
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
                <th>
                  Track # in Playlist
                </th>
                <th>
                  Song
                </th>
                <th>
                  Album
                </th>
              </tr>
            </thead>
            <tbody>
              {(tracks || []).map(({ name, album, trackIndexInPlaylist }) => (
                <tr>
                  <td>
                    {trackIndexInPlaylist + 1}
                  </td>
                  <td>
                    {name}
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
            <span>{tracks[0].name} - {tracks[0].album}</span>
          )}
        </span>
      )}
    </div>
  )
}

export default MatchingPlaylist;