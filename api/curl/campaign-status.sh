#!/usr/bin/env bash
# Progress and results of a campaign.
# Usage: PUSHAGENT_API_KEY=pak_... ./campaign-status.sh 1234
set -euo pipefail
: "${PUSHAGENT_API_KEY:?Set PUSHAGENT_API_KEY first}"
ID="${1:?Pass the campaign id returned when you sent it}"

curl -sS "https://app.pushagent.net/api/v1/campaign?id=$ID" \
  -H "X-PushAgent-Key: $PUSHAGENT_API_KEY"
echo
