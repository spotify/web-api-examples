'use-strict'

/**
 * This is an example of a basic node.js script that performs
 * the Client Credentials oAuth2 flow to authenticate against
 * the Spotify Accounts.
 *
 * For more information, read
 * https://developer.spotify.com/web-api/authorization-guide/#client_credentials_flow
 */

const Spotify = require('spotify-web-api-node');

const CLIENT_ID = '03ffe0cac0a0401aa6673c3cf6d02ced';
const CLIENT_SECRET = 'a57c43efb9644574a96d6623fb8bfbc2';

// configure spotify
const spotifyApi = new Spotify({
  clientId: CLIENT_ID,
  clientSecret: CLIENT_SECRET
});

// use the access token to access the Spotify Web API
spotifyApi.clientCredentialsGrant().then(({ body }) => {
  spotifyApi.setAccessToken(body.access_token);
  return spotifyApi.getUser('jmperezperez');
}).then(({ body }) => {
  console.log(body);
}).catch(err => {
  console.error('error getting user info', err);
});
