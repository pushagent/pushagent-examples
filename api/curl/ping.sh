#!/usr/bin/env bash
# Check your API key: which website and plan it belongs to.
# Usage: PUSHAGENT_API_KEY=pak_... ./ping.sh
set -euo pipefail
: "${PUSHAGENT_API_KEY:?Set PUSHAGENT_API_KEY first}"

curl -sS https://app.pushagent.net/api/v1/ping \
  -H "X-PushAgent-Key: $PUSHAGENT_API_KEY"
echo
