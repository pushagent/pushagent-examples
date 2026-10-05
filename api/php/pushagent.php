<?php
/**
 * Tiny Push Agent API client (PHP 7.4+, cURL).
 * The API key is read from the PUSHAGENT_API_KEY environment variable,
 * or from a .env file in the repository root.
 */

const PUSHAGENT_API = 'https://app.pushagent.net/api/v1/';

function pushagent_api_key(): string
{
    $key = getenv('PUSHAGENT_API_KEY') ?: '';
    $env = dirname(__DIR__, 2) . '/.env';
    if ($key === '' && is_readable($env)) {
        foreach (file($env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (strpos($line, 'PUSHAGENT_API_KEY=') === 0) {
                $key = trim(substr($line, strlen('PUSHAGENT_API_KEY=')));
            }
        }
    }
    if ($key === '' || $key === 'pak_your_api_key_here') {
        fwrite(STDERR, "Set PUSHAGENT_API_KEY (see .env.example).\n");
        exit(1);
    }
    return $key;
}

/**
 * Calls the API and returns the decoded JSON response.
 * Throws RuntimeException with the API's error message on failure.
 */
function pushagent_request(string $method, string $path, ?array $body = null): array
{
    $ch = curl_init(PUSHAGENT_API . ltrim($path, '/'));
    $headers = ['X-PushAgent-Key: ' . pushagent_api_key(), 'Accept: application/json'];
    $opts = [
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
    ];
    if ($body !== null) {
        $headers[] = 'Content-Type: application/json';
        $opts[CURLOPT_POSTFIELDS] = json_encode($body);
    }
    $opts[CURLOPT_HTTPHEADER] = $headers;
    curl_setopt_array($ch, $opts);

    $raw = curl_exec($ch);
    if ($raw === false) {
        throw new RuntimeException('Network error: ' . curl_error($ch));
    }
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $json = json_decode($raw, true);
    if ($status >= 400 || !is_array($json)) {
        $msg = is_array($json) && isset($json['error']) ? $json['error'] : "HTTP $status";
        throw new RuntimeException($msg);
    }
    return $json;
}

/* Print API errors as a clear one-line message instead of a PHP stack trace. */
set_exception_handler(function (Throwable $e) {
    fwrite(STDERR, 'Push Agent API: ' . $e->getMessage() . "\n");
    exit(1);
});
