# Spotify Client Credentials example

This app retrieves information from an artist's track using [Client Credentials](https://developer.spotify.com/documentation/web-api/tutorials/code-flow)
to grant permissions to the app.

## Installation

This example runs on Node.js. On [its website](http://www.nodejs.org/download/) you can find instructions on how to install it.

### Using your own credentials

You will need to register your app and get your own credentials from the [Spotify for Developers Dashboard](https://developer.spotify.com/dashboard).

- Create a new app in the dashboard and add `http://localhost:8888/callback` to the app's redirect URL list.
- Once you have created your app, update the `client_id` and `client_secret` in the `app.js` file with the credentials obtained from the app settings in the dashboard.

## Running the example

From a console shell:

    $ node app.js
