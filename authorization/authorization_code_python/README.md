This app displays your Spotify profile information using [Authorization Code](https://developer.spotify.com/documentation/web-api/tutorials/code-flow)
to grant permissions to the app.

## Installation

This example runs on flask. On [its website](https://flask.palletsprojects.com/en/3.0.x/installation/) you can find instructions on how to install it.

Install the app dependencies running:

    pip install -r requirements.txt

## Using your own credentials

You will need to register your app and get your own credentials from the [Spotify for Developers Dashboard](https://developer.spotify.com/dashboard).

- Create a new app in the dashboard and add `http://localhost:8888/callback` to the app's redirect URL list.
- Once you have created your app, 
create ".env" like so:
```bash
    CLIENT_ID='client_id'
    CLIENT_SECRET='client_secret'
    SESSION_SECRET='enter some code'
    REDIRECT_URI='redirect uri'
```
uppdate file with the credentials obtained from the app settings in the dashboard and the 
redirect uri youv'e input in the app,
for the session secret may use any.

## Running the example

From a console shell:

    python main.py

Then, open `http://localhost:8080` in a browser.