# Spotify Profile Display Python Example

This Python code example demonstrates how to use the Spotify API to display user profile information. 

Displays your Spotify profile information using [Authorization Code](https://developer.spotify.com/documentation/web-api/tutorials/code-flow)
to grant permissions to the app.

## Installation

This example runs on flask. On [its website](https://flask.palletsprojects.com/en/3.0.x/installation/) you can find instructions on how to install it.

Install the app dependencies running:

    pip install -r requirements.txt

## Using your own credentials

You will need to register your app and get your own credentials from the [Spotify for Developers Dashboard](https://developer.spotify.com/dashboard).

- Create a new app in the dashboard and add `http://localhost:8888/callback` to the app's redirect URL list.
- Once you have created your app, create ".env" like so:
```bash
    CLIENT_ID='client_id'
    CLIENT_SECRET='client_secret'
    SESSION_SECRET='enter some code'
    REDIRECT_URI='redirect uri'
```
Update the file with the credentials obtained from the app settings in the dashboard and the redirect URI you've input in the app. For the session secret, you may use any..

## Running the example

From a console shell:

    python main.py

Then, open `http://localhost:8080` in a browser.

## Author
- Create by [Leon Markovich](hhttps://github.com/LeonMar97)
- Tested on  28/11/23.
