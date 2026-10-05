<?php
/**
 * Send a notification to all subscribers (or some of them).
 * Usage: php send-campaign.php "Title" "Message" "https://example.com/page"
 */
require __DIR__ . '/pushagent.php';

[, $title, $body, $url] = $argv + [null, 'Hello from Push Agent', 'This notification was sent with the API.', 'https://example.com/'];

$result = pushagent_request('POST', 'campaigns', [
    'title' => $title,   // up to 65 characters
    'body'  => $body,    // up to 240 characters
    'url'   => $url,     // must start with https://

    // Optional: only some subscribers (two-letter country codes).
    // 'filters' => ['country' => ['US', 'GB']],

    // Optional: send later. Use UTC (the trailing Z) to avoid time zone surprises.
    // 'schedule_at' => '2026-12-01T09:00:00Z',
]);

echo "Campaign {$result['id']} queued.\n";
echo json_encode($result['progress'], JSON_PRETTY_PRINT) . "\n";
