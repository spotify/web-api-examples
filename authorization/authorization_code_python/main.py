import spotipy
from spotipy.oauth2 import SpotifyClientCredentials
import requests
from dotenv import load_dotenv
import os
from flask import Flask, redirect, request, jsonify, session, render_template
import urllib.parse
from datetime import datetime
import json
import base64
load_dotenv()  # getting credentials
CLIENT_ID = os.getenv("CLIENT_ID")
CLIENT_SECRET = os.getenv("CLIENT_SECRET")
SESSION_SECRET = os.getenv("SESSION_SECRET")
REDIRECT_URI = os.getenv("REDIRECT_URI")
"""the user should add .env file with credentials"""
CREDENTIALS = f"{CLIENT_ID}:{CLIENT_SECRET}"
# Encode the combined string to base64
encoded_credentials = base64.b64encode(CREDENTIALS.encode('utf-8')).decode('utf-8')
app = Flask(__name__)

app.secret_key = SESSION_SECRET

AUTH_URL = "https://accounts.spotify.com/authorize"
TOKEN_URL = "https://accounts.spotify.com/api/token"
API_BASE = "https://api.spotify.com/v1/"


@app.route("/")
def index():
    return render_template('index.html')


@app.route("/login")
def login():

    SCOPE = "user-read-private user-read-email"

    params = {
        "client_id": CLIENT_ID,
        "response_type": "code",
        "scope": SCOPE,
        "redirect_uri": REDIRECT_URI,
        "show_dialog": True,
    }

    auth_url = f"{AUTH_URL}?{urllib.parse.urlencode(params)}"
    return redirect(auth_url)


@app.route("/callback")
def callback():
    if "error" in request.args:
        return jsonify({"error": request.args["error"]})
    if "code" in request.args:
        req_body = {
            "code": request.args["code"],
            "grant_type": "authorization_code",
            "redirect_uri": REDIRECT_URI,
            "client_id": CLIENT_ID,
            "client_secret": CLIENT_SECRET,
            "headers":{
                'content-type': 'application/x-www-form-urlencoded',
                'authorization_header' : f'Basic {encoded_credentials}'
            },
            "json":True
        }
        res = requests.post(TOKEN_URL, data=req_body)
        token_info = res.json()
        
        session["access_token"] = token_info["access_token"]
        session["refresh_token"] = token_info["refresh_token"]
        
        
        token_js=f'access_token={token_info["access_token"]}&refresh_token={token_info["refresh_token"]}'
        return redirect(f"/#{token_js}")
    #add invalid token later !!!
    

@app.route("/refresh_token")
def refresh_token():
    """making sure again that the token expired.."""
    req_body = {
        "grant_type": "refresh_token",
        "refresh_token": session["refresh_token"],
        "client_id": CLIENT_ID,
        "client_secret": CLIENT_SECRET,
        "headers":{
                'content-type': 'application/x-www-form-urlencoded',
                'authorization_header' : f'Basic {encoded_credentials}'
            },
    }
    res = requests.post(TOKEN_URL, data=req_body)
    new_token_info = res.json()
    session["access_token"] = new_token_info["access_token"]
    
    return jsonify({'access_token':new_token_info['access_token'],'refresh_token':session['refresh_token']})



if __name__ == "__main__":
    app.run(host="0.0.0.0", port=8080, debug=True)
