import { useState } from "react";
import { Button } from 'reactstrap';

function MatchingPlaylist(
  {
    playlist,
    tracks,
  } : {
    playlist?: { url: string, name: string },
    tracks?: { name: string, album: string }[]
  }
) {
  const [expanded, setExpanded] = useState(false);

  return (
    <div>
      <a target="_blank" href={playlist?.url} rel="noreferrer">{playlist?.name}</a>
      {expanded ? (
        <div className="ml-1">
          {(tracks || []).map(({ name, album }) => `${name} - ${album}`).join(', ')}
        </div>
      ) : (
        <span>
          {tracks && tracks[0] && (
            <span>: {tracks[0].name} - {tracks[0].album}</span>
          )}
        </span>
      )}
      <Button onClick={() => setExpanded(expanded => !expanded)} color='link'>See {expanded ? 'less' : 'more'}</Button>
    </div>
  )
}

export default MatchingPlaylist;