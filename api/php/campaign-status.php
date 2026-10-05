<?php
/**
 * Progress and results of a campaign.
 * Usage: php campaign-status.php 1234
 */
require __DIR__ . '/pushagent.php';

$id = (int) ($argv[1] ?? 0);
if ($id <= 0) {
    fwrite(STDERR, "Usage: php campaign-status.php <campaign id>\n");
    exit(1);
}
echo json_encode(pushagent_request('GET', 'campaign?id=' . $id), JSON_PRETTY_PRINT) . "\n";
