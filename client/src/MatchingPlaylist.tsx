function MatchingPlaylist(
  {
    playlist,
    tracks,
  } : {
    playlist?: { url: string, name: string },
    tracks?: { name: string, album: string }[]
  }
) {
  return (
    <div>
      <a target="_blank" href={playlist?.url} rel="noreferrer">{playlist?.name}</a>
      : {(tracks || []).map(({ name, album }) => `${name} - ${album}`).join(', ')}
    </div>
  )
}

export default MatchingPlaylist;