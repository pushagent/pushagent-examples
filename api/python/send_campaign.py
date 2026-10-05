"""Send a notification to all subscribers with the Push Agent API.

Python 3.8+, standard library only.
Usage: PUSHAGENT_API_KEY=pak_... python send_campaign.py
"""
import json
import os
import sys
import urllib.error
import urllib.request

API = "https://app.pushagent.net/api/v1/"


def request(method, path, body=None):
    key = os.environ.get("PUSHAGENT_API_KEY")
    if not key:
        sys.exit("Set PUSHAGENT_API_KEY first (see .env.example).")
    data = json.dumps(body).encode() if body is not None else None
    req = urllib.request.Request(API + path, data=data, method=method)
    req.add_header("X-PushAgent-Key", key)
    req.add_header("Accept", "application/json")
    if data is not None:
        req.add_header("Content-Type", "application/json")
    try:
        with urllib.request.urlopen(req, timeout=30) as res:
            return json.load(res)
    except urllib.error.HTTPError as err:
        try:
            message = json.load(err).get("error", err.reason)
        except ValueError:
            message = err.reason
        sys.exit(f"Error {err.code}: {message}")


if __name__ == "__main__":
    result = request("POST", "campaigns", {
        "title": "New: our spring collection is here",  # up to 65 characters
        "body": "Tap to see what is new this week.",     # up to 240 characters
        "url": "https://example.com/new-arrivals",       # must start with https://
        # "filters": {"country": ["US", "CA"]},
        # "schedule_at": "2026-12-01T09:00:00Z",
    })
    print(f"Campaign {result['id']} queued.")
    print(json.dumps(result.get("progress"), indent=2))
