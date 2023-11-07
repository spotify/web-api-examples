
# Display your Spotify Profile Data in a Web App

This is the final code for the Spotify Web API - How to Display your Profile Data in a Web . You can run this demo directly or [walk through the tutorial](https://developer.spotify.com/documentation/web-api/howtos/web-app-profile).

## Pre-requisites

To run this demo you will need:

- A [Node.js LTS](https://nodejs.org/en/) environment or later.
- A [Spotify Developer Account](https://developer.spotify.com/)

## Usage

Create an app in your [Spotify Developer Dashboard](https://developer.spotify.com/dashboard/), set the redirect URI to ` http://localhost:5173/callback` and `http://localhost:5173/callback/` and copy your Client ID. 

Clone the repository, ensure that you are in the `get_user_profile` directory and run:

```bash
npm install
npm run dev
```

Replace the value for clientId in `/src/script.ts` with your own Client ID.
