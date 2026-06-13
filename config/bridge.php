<?php
return [
    'env' => env('BRIDGE_ENV', 'sandbox'),
    'key' => env('BRIDGE_KEY'),
    'allowed_ips' => env('BRIDGE_ALLOWED_IPS', ''),
    'log_payload' => (bool) env('BRIDGE_LOG_PAYLOAD', true),
    'timeout' => (int) env('BRIDGE_TIMEOUT', 30),
];
