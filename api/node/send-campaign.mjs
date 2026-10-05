// Send a notification to all subscribers with the Push Agent API.
// Requires Node.js 18+ (built-in fetch).
// Usage: PUSHAGENT_API_KEY=pak_... node send-campaign.mjs

const key = process.env.PUSHAGENT_API_KEY;
if (!key) {
  console.error('Set PUSHAGENT_API_KEY first (see .env.example).');
  process.exit(1);
}

const res = await fetch('https://app.pushagent.net/api/v1/campaigns', {
  method: 'POST',
  headers: { 'X-PushAgent-Key': key, 'Content-Type': 'application/json' },
  body: JSON.stringify({
    title: 'New: our spring collection is here', // up to 65 characters
    body: 'Tap to see what is new this week.',      // up to 240 characters
    url: 'https://example.com/new-arrivals',        // must start with https://
    // filters: { country: ['US', 'CA'] },
    // schedule_at: '2026-12-01T09:00:00Z',
  }),
});

const data = await res.json();
if (!res.ok) {
  console.error(`Error ${res.status}: ${data.error ?? 'unknown error'}`);
  process.exit(1);
}
console.log(`Campaign ${data.id} queued.`, data.progress);
