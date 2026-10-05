#!/usr/bin/env bash
# Send a notification to all subscribers of the website the API key belongs to.
# Usage: PUSHAGENT_API_KEY=pak_... ./send-campaign.sh
set -euo pipefail
: "${PUSHAGENT_API_KEY:?Set PUSHAGENT_API_KEY first}"

curl -sS -X POST https://app.pushagent.net/api/v1/campaigns \
  -H "X-PushAgent-Key: $PUSHAGENT_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
        "title": "New: our spring collection is here",
        "body": "Tap to see what is new this week.",
        "url": "https://example.com/new-arrivals"
      }'
echo

# Only subscribers in some countries, sent later (UTC):
#   -d '{"title": "...", "body": "...", "url": "https://...",
#        "filters": {"country": ["US", "CA"]},
#        "schedule_at": "2026-12-01T09:00:00Z"}'
