<?php
/**
 * Dashboard alert: tell your team when products run low on stock.
 * Run it every 15 minutes with cron (the exact cron line is in api/README.md).
 *
 * Tip: add your internal dashboard as its own website in Push Agent, so only
 * your team is subscribed to it and these alerts never reach your customers.
 */
require __DIR__ . '/pushagent.php';

// 1. Read the metric from your own database (change the query to suit your data).
$pdo = new PDO('mysql:host=localhost;dbname=shop;charset=utf8mb4', 'db_user', 'db_password');
$low = (int) $pdo->query('SELECT COUNT(*) FROM products WHERE stock < 5')->fetchColumn();

// 2. Only alert when the number changes, so the team isn't told the same thing twice.
$stateFile = __DIR__ . '/low-stock.last';
$last = is_readable($stateFile) ? (int) file_get_contents($stateFile) : -1;
if ($low === $last) {
    exit(0);
}
file_put_contents($stateFile, (string) $low);
if ($low === 0) {
    exit(0);
}

// 3. Send the alert, linking straight to the right dashboard view.
$result = pushagent_request('POST', 'campaigns', [
    'title' => $low === 1 ? '1 product is low on stock' : "$low products are low on stock",
    'body'  => 'Open the inventory dashboard to reorder before they sell out.',
    'url'   => 'https://dashboards.example.com/inventory?filter=low-stock',
]);
echo "Alert sent (campaign {$result['id']}).\n";
