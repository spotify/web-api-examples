# Spotify Implicit Grant example

This app displays your Spotify profile information using [Implicit Grant](https://developer.spotify.com/documentation/web-api/tutorials/implicit-flow)
to grant permissions to the app.

> The implicit grant flow has some significant security flaws, so we strongly advise against using this flow. If you need to implement authorization where storing your client secret is not possible, use Authorization code with PKCE instead.

## Installation

This example runs on Node.js. On [its website](http://www.nodejs.org/download/) you can find instructions on how to install it.

Install the app dependencies running:

    $ npm install

## Using your own credentials

You will need to register your app and get your own credentials from the [Spotify for Developers Dashboard](https://developer.spotify.com/dashboard).

- Create a new app in the dashboard and add `http://localhost:8080` to the app's redirect URL list.
- Once you have created your app, update the `client_id` and `redirect_uri` in the `public/index.html` file with the values obtained from the app settings in the dashboard.

## Running the example

From a console shell:

    $ npm start

Then, open `http://localhost:8080` in a browser.
