/**
 * This is an example of a basic node.js script that performs
 * the Authorization Code oAuth2 flow to authenticate against
 * the Spotify Accounts.
 *
 * For more information, read
 * https://developer.spotify.com/web-api/authorization-guide/#authorization_code_flow
 */

var express = require('express'); // Express web server framework
var request = require('request'); // "Request" library
var cors = require('cors');
var querystring = require('querystring');
var cookieParser = require('cookie-parser');

var client_id = '8fc273ab9c6046aabb27a2a00c760ff5'; // Your client id
var client_secret = '2ffb16a11f644f1f987bc1fb8bc81f75'; // Your secret
var redirect_uri = 'http://localhost:8888/callback'; // Your redirect uri


/**
 * Generates a random string containing numbers and letters
 * @param  {number} length The length of the string
 * @return {string} The generated string
 */
var generateRandomString = function(length) {
  var text = '';
  var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

  for (var i = 0; i < length; i++) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }
  return text;
};

var stateKey = 'spotify_auth_state';

var app = express();

app.use(express.static(__dirname + '/public'))
   .use(cors())
   .use(cookieParser());

app.get('/login', function(req, res) {

  var state = generateRandomString(16);
  res.cookie(stateKey, state);

  // your application requests authorization
  var scope = 'user-read-private user-read-email playlist-modify';
  res.redirect('https://accounts.spotify.com/authorize?' +
    querystring.stringify({
      response_type: 'code',
      client_id: client_id,
      scope: scope,
      redirect_uri: redirect_uri,
      state: state
    }));
});

app.get('/callback', function(req, res) {

  // your application requests refresh and access tokens
  // after checking the state parameter

  var code = req.query.code || null;
  var state = req.query.state || null;
  var storedState = req.cookies ? req.cookies[stateKey] : null;

  if (state === null || state !== storedState) {
    res.redirect('/#' +
      querystring.stringify({
        error: 'state_mismatch'
      }));
  } else {
    res.clearCookie(stateKey);
    var authOptions = {
      url: 'https://accounts.spotify.com/api/token',
      form: {
        code: code,
        redirect_uri: redirect_uri,
        grant_type: 'authorization_code'
      },
      headers: {
        'Authorization': 'Basic ' + (new Buffer(client_id + ':' + client_secret).toString('base64'))
      },
      json: true
    };

    request.post(authOptions, function(error, response, body) {

      if (!error && response.statusCode === 200) {

        var access_token = body.access_token,
            refresh_token = body.refresh_token;

        var options = {
          url: 'https://api.spotify.com/v1/me',
          headers: { 'Authorization': 'Bearer ' + access_token },
          json: true
        };
		var rpm = 120;		//TODO: GET RPM AS INPUT
		var playlist = '2RdMT12TLFq16p3foNYELU';

		var newPL = {
			url: 'https://api.spotify.com/v1/playlists',
            headers: { 'Authorization': 'Bearer ' + access_token,
		 				'Accept': 'application/json',
						'Content-Type': 'application/json',
						'data' :  '{\"name\":\"TEST1\",\"description\":\"henlo\",\"public\":\"false\"}'
					},
            json: true
		};
		var gettracks = {
			url: 'https://api.spotify.com/v1/recommendations?limit=10&market=ES&seed_artists=4NHQUGzhtTLFvgF5SZesLK&seed_genres=classical%2Ccountry&seed_tracks=0c6xIDDpzE81m2q797ordA&target_tempo=' + rpm,
            headers: { 'Authorization': 'Bearer ' + access_token,
		 				'Accept': 'application/json',
						'Content-Type': 'application/json'
					},
            json: true
		};


		var parsed = [];

        // use the access token to access the Spotify Web API
		// request.post(newPL, function(error, response, body0){
		// 	console.log(body0);
	        request.get(gettracks, function(error, response, body1) {
	          console.log(body1);
			  	parsed = body1;
				// console.log(parsed);
				// var uris = [];
				// var names = [];
				console.log("PARSED:");
				console.log(parsed);
				console.log("PARSED.TRACKS:");
				console.log(parsed.tracks);
				// alert("right before");
				parsed.tracks.forEach(function(track){
					var addtrack = {
						url: 'https://api.spotify.com/v1/playlists/' + playlist + '/tracks?uris='+ track.uri,
			            headers: { 'Authorization': 'Bearer ' + access_token,
					 				'Accept': 'application/json',
									'Content-Type': 'application/json'
								},
			            json: true
					};
					request.post(addtrack, function(error, response, body2){
						console.log(body2);
					});
					// alert("in foreach");
					// names.push(track.name);
					// uris.push(track.uri);
					// var html = "<a href=" + track.uri + ">" + track.name + "</a>";
					// console.log(html);
					// window.document.getElementById("unique").innerHTML = html;

				});
			// console.log(uris);
			// uris.forEach(function(uri){
				// var a = window.document.createElement('a');
				// a.setAttribute('href', uri);
				// var d = window.document.getElementById('unique');
				// window.document.d.appendChild(a);
			 });
        // });

			//array[tracks objects]	track objects have uri
        // we can also pass the token to the browser to make requests from there
        res.redirect('/#' +
          querystring.stringify({
            access_token: access_token,
            refresh_token: refresh_token
          }));
      } else {
        res.redirect('/#' +
          querystring.stringify({
            error: 'invalid_token'
          }));
      }
    });
  }
});

app.get('/refresh_token', function(req, res) {

  // requesting access token from refresh token
  var refresh_token = req.query.refresh_token;
  var authOptions = {
    url: 'https://accounts.spotify.com/api/token',
    headers: { 'Authorization': 'Basic ' + (new Buffer(client_id + ':' + client_secret).toString('base64')) },
    form: {
      grant_type: 'refresh_token',
      refresh_token: refresh_token
    },
    json: true
  };

  request.post(authOptions, function(error, response, body) {
    if (!error && response.statusCode === 200) {
      var access_token = body.access_token;
      res.send({
        'access_token': access_token
      });
    }
  });
});

console.log('Listening on 8888');
app.listen(8888);
