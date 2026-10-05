# Push Agent REST API examples

Send web push notifications from your own code: a cron job, a dashboard, a CMS or a back-office script.

> The API is part of the **Growth** plan and higher. Every new account can try Growth free for 14 days. See [pricing](https://pushagent.net/pricing/).

## 1. Get an API key

In your [Push Agent dashboard](https://app.pushagent.net), open **API & WooCommerce → API keys** and click **Create API key**. Copy it straight away: it is shown only once.

Keep it out of your code. Put it in an environment variable, or copy [`.env.example`](../.env.example) to `.env` (which git ignores):

```bash
export PUSHAGENT_API_KEY=pak_your_api_key_here
```

Each key belongs to one website, and notifications go to that website's subscribers.

## 2. Endpoints

Base URL: `https://app.pushagent.net/api/v1/`
Authentication: send your key in the `X-PushAgent-Key` header (or `Authorization: Bearer <key>`). Requests and responses are JSON.

| Method | Endpoint | What it does |
|---|---|---|
| `GET` | `ping` | Checks the key: which website and plan it belongs to |
| `POST` | `campaigns` | Sends a notification to all subscribers, or a filtered group |
| `GET` | `campaign?id=ID` | Progress and results of a campaign |

### `POST campaigns`

| Field | Required | Notes |
|---|---|---|
| `title` | yes | Up to 65 characters |
| `body` | yes | Up to 240 characters |
| `url` | yes | Where the notification opens; must start with `https://` |
| `filters` | no | Narrow the audience, e.g. `{"country": ["US", "GB"]}` (two-letter country codes). `platform` and `browser` filters use the same names as the filters on the New notification screen. |
| `schedule_at` | no | Send later, e.g. `"2026-12-01T09:00:00Z"` (UTC) |
| `icon`, `image` | no | `https://` image URLs; the icon defaults to your website icon |

Example response:

```json
{ "ok": true, "id": 1234, "progress": { "...": "..." } }
```

Errors come back with an HTTP status code and a message, for example `401 {"error": "Invalid or revoked API key."}`.

## 3. Examples in this folder

| Folder | Files |
|---|---|
| [`curl/`](curl) | `ping.sh`, `send-campaign.sh`, `campaign-status.sh` |
| [`php/`](php) | `pushagent.php` (tiny client), `send-campaign.php`, `campaign-status.php`, `low-stock-alert.php` |
| [`node/`](node) | `send-campaign.mjs` (Node.js 18+, no dependencies) |
| [`python/`](python) | `send_campaign.py` (Python 3.8+, standard library only) |

```bash
# PHP
php php/send-campaign.php "Flash sale: 30% off today" "Tap to see the deals." "https://example.com/sale"
php php/campaign-status.php 1234

# Node.js
node node/send-campaign.mjs

# Python
python3 python/send_campaign.py
```

### Dashboard alerts with cron

[`php/low-stock-alert.php`](php/low-stock-alert.php) checks a number in your database and sends an alert only when it changes. Run it every 15 minutes:

```cron
*/15 * * * * php /path/to/pushagent-examples/api/php/low-stock-alert.php
```

Tip: add your internal dashboard as its own website in Push Agent, so only your team is subscribed to it and alerts never reach your customers.

## Good practice

- Keep titles short and put what happened first. Browsers cut long titles, especially on phones.
- Link to the exact page that explains the alert, not your home page.
- Alert on a *change*, not on every check, so people don't get the same message twice.
- If a key is ever exposed, revoke it in the dashboard and create a new one.
