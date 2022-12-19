/**
 * This is an example of a basic node.js script that performs
 * the Client Credentials oAuth2 flow to authenticate against
 * the Spotify Accounts.
 *
 * For more information, read
 * https://developer.spotify.com/web-api/authorization-guide/#client_credentials_flow
 */

var axios = require('axios');
var querystring = require('querystring');

var client_id = 'CLIENT_ID'; // Your client id
var client_secret = 'CLIENT_SECRET'; // Your secret

// your application requests authorization
var authOptions = {
  url: 'https://accounts.spotify.com/api/token',
  headers: {
    'Authorization': 'Basic ' + (new Buffer.from(`${client_id}:${client_secret}`).toString('base64'))
  },
  form: {
    grant_type: 'client_credentials'
  },
  json: true
};

axios.post(authOptions.url, querystring.stringify(authOptions.form), authOptions).then(response => {
  if (response.status === 200) {
    // use the access token to access the Spotify Web API
    var token = response.data.access_token;
    var options = {
      url: 'https://api.spotify.com/v1/users/jmperezperez',
      headers: {
        'Authorization': 'Bearer ' + token
      },
      json: true
    };
    axios.get(options.url, options).then(response => {
      console.log(response.data);
    });
  }
});
