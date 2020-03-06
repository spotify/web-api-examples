/**
 * This is an example of a basic node.js script that performs
 * the Client Credentials oAuth2 flow to authenticate against
 * the Spotify Accounts.
 *
 * For more information, read
 * https://developer.spotify.com/web-api/authorization-guide/#client_credentials_flow
 */
const axios = require('axios');

const client_id = 'CLIENT_ID'; // Your client id
const client_secret = 'CLIENT_SECRET'; // Your secret

const params = {
  client_id,
  client_secret,
  grant_type: "client_credentials"
};

axios({
  method: "post",
  url: 'https://accounts.spotify.com/api/token',
  params,
  headers: {
    "Content-Type": "application/x-www-form-urlencoded"
  }
}).then(response=>{
  const token = response.data.access_token
  axios({
    method:"get",
    url: 'https://api.spotify.com/v1/users/jmperezperez',
    headers: {'Authorization': 'Bearer ' + token},
  }).then(response=>console.log(response.data)).catch(err=>console.error(err))
}).catch(error=>console.error(error))