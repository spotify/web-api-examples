/**
 * This is an example of a basic node.js script that performs
 * the Authorization Code oAuth2 flow to authenticate against
 * the Spotify Accounts.
 *
 * For more information, read
 * https://developer.spotify.com/web-api/authorization-guide/#authorization_code_flow
 */
 var SpotifyWebApi = require('spotify-web-api-node');

 var spotifyApi = new SpotifyWebApi();
var express = require('express'); // Express web server framework
var request = require('request'); // "Request" library
var cors = require('cors');
var querystring = require('querystring');
var cookieParser = require('cookie-parser');
var bodyParser = require('body-parser')

var client_id = '8fc273ab9c6046aabb27a2a00c760ff5'; // Your client id
var client_secret = 'f41e5f2c83fe4f03a7b3300b7caeb271'; // Your secret
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
   .use(bodyParser.json())
   .use(bodyParser.urlencoded())
   .use(cors())
   .use(cookieParser());
var inputs = [];
app.post('/login', function(req, res) {
	inputs = req.body;
  // TODO: GET RPM
  console.log('HIT LOGIN REDIRECT', req.body)

  var state = generateRandomString(16);
  res.cookie(stateKey, state);

  // your application requests authorization
  var scope = 'user-read-private user-read-email user-top-read playlist-modify-public playlist-modify-private';
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
		  spotifyApi.setAccessToken(body.access_token);
        var access_token = body.access_token,
            refresh_token = body.refresh_token;
		var userinfo = {};
			spotifyApi.getMe()
			  .then(function(data) {
				  userinfo = data.body;
			    console.log('Some information about the authenticated user', data.body);
			  }, function(err) {
			    console.log('Something went wrong!', err);
			  });
        var options = {
          url: 'https://api.spotify.com/v1/me',
          headers: { 'Authorization': 'Bearer ' + access_token },
          json: true
        };
		var rpm = inputs.RPM;		//TODO: GET RPM AS INPUT
		console.log(rpm);
		var playlist = '34pXWLoQkK6POGHLcb0U51';
		var userpers = {
			url: 'https://api.spotify.com/v1/me/top/artists?limit=3',
			headers: { 'Authorization': 'Bearer ' + access_token,
						'Accept': 'application/json',
						'Content-Type': 'application/json'
					},
			json: true
		};

	// 	spotifyApi.createPlaylist('My Cool Playlist', { 'public' : false })
  // .then(function(data) {
  //   console.log('Created playlist!');
  // }, function(err) {
  //   console.log('Something went wrong!', err);
  // });

		var newPL = {
			url: 'https://api.spotify.com/v1/users/sammkaiser/playlists',
            headers: { 'Authorization': 'Bearer ' + access_token,
		 				'Accept': 'application/json',
						'Content-Type': 'application/json'
					},
			body: JSON.stringify({name: "rpm", public:true}),
            json: true
		};
		request.post(newPL, function(error, response, body3){
			if(response != 200 && response != 201){
				console.log("Error from create new playlist");
				console.log(response.body);
			}else{
		console.log('Spotify API',spotifyApi)
		// spotifyApi.createPlaylist('My Cool Playlist', { 'public' : false })
		//   .then(function(data) {
		// 	  playlist = data.id;
		//     console.log('Created playlist!');
		//   }, function(err) {
		//     console.log('Something went wrong in creating a playlist!', err);
		//   });
				console.log(error);
				console.log(body3);
				console.log(body3.id);
				playlist = body3.id;
				request.get(userpers, function(error, response, body0){
					//console.log(body0);
					//console.log(body0.items);
					var genres = [];
					body0.items.forEach(function(artist){
						artist.genres.forEach(function(i){
							genres.push(i);
						});
					})
					var genreString = '';

						var res = genres[0].replace(" ", "_");
						genreString += res;
					console.log("GENRESTRING");
					console.log(genreString);

					var artists = [];
					body0.items.forEach(function(artist){
						artists.push(artist.id)
					});
					var artistString = '';
						var res = artists[0].replace(" ", "_");
						artistString += res;
					var gettracks = {
						url: 'https://api.spotify.com/v1/recommendations?limit=10&market=' + userinfo.country + '&seed_artists='+ artistString +'&seed_genres=' + genreString + '&seed_tracks=0c6xIDDpzE81m2q797ordA&target_tempo=' + rpm,
			            headers: { 'Authorization': 'Bearer ' + access_token,
					 				'Accept': 'application/json',
									'Content-Type': 'application/json'
								},
			            json: true
					};
			        request.get(gettracks, function(error, response, body1) {
			          console.log(body1);
					  	// parsed = body1;
						// console.log(parsed);
						// var uris = [];
						// var names = [];
						console.log("PARSED:");
						console.log(body1);
						console.log("PARSED.TRACKS:");
						console.log(body1.tracks);
						// alert("right before");
						body1.tracks.forEach(function(track){
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

						});
					});
				 });
			 }
         });

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
