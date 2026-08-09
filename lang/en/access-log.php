<?php

return [
    'app' => [
        'title' => 'Access Log',
        'brand' => 'Access Log',
        'nav_aria' => 'Access log navigation',
    ],
    'nav' => [
        'back_mca' => 'MCA Hub',
        'logs' => 'Requests',
        'firewall' => 'Firewall',
    ],
    'filters' => [
        'ip' => 'IP',
        'method' => 'Method',
        'status' => 'Status',
        'blocked' => 'Blocked',
        'blocked_all' => 'All',
        'blocked_yes' => 'Blocked only',
        'blocked_no' => 'Allowed only',
        'q' => 'Path / UA search…',
        'apply' => 'Filter',
        'clear' => 'Clear',
    ],
    'fields' => [
        'ip' => 'IP',
        'method' => 'Method',
        'path' => 'Path',
        'status' => 'Status',
        'user' => 'User',
        'duration' => 'Duration',
        'when' => 'When',
        'user_agent' => 'User agent',
        'hits' => 'Hits',
        'blocked' => 'Blocked',
        'unique_paths' => 'Unique paths',
        'last_seen' => 'Last seen',
    ],
    'actions' => [
        'view_ip' => 'IP detail',
        'block' => 'Blacklist IP',
        'whitelist' => 'Whitelist IP',
        'block_label' => 'From access log',
        'whitelist_label' => 'From access log',
        'block_reason_default' => 'Blocked from access log',
        'open_firewall' => 'Open firewall',
    ],
    'flash' => [
        'blocked' => ':ip added to blacklist.',
        'whitelisted' => ':ip added to whitelist.',
    ],
    'table' => [
        'empty' => 'No access logs yet.',
        'top_ips' => 'Top IPs',
        'blocked_badge' => 'Blocked',
    ],
    'pages' => [
        'index_title' => 'Access requests',
        'ip_title' => 'IP :ip',
    ],
    'hint' => [
        'suite' => 'Access suite: mca/firewall for rules, mca/access-intel (planned) for health scoring.',
        'retention' => 'Old rows are purged with php artisan mca:access-log:purge',
    ],
    'errors' => [
        'root_only' => 'Only root users can view access logs.',
        'firewall_missing' => 'mca/firewall is not installed.',
    ],
    'modal' => [
        'ok' => 'OK',
        'confirm' => 'Confirm',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'alert_title' => 'Notice',
        'confirm_title' => 'Confirm',
    ],
    'confirm' => [
        'block' => 'Add this IP to the firewall blacklist?',
        'whitelist' => 'Add this IP to the firewall whitelist?',
    ],
    'console' => [
        'install' => [
            'start' => 'Installing MCA Access Log…',
            'config_ready' => 'Config ready',
            'assets_published' => 'Assets published',
            'migration_done' => 'Migrations done',
            'done' => 'MCA Access Log installed.',
            'web_ui' => 'Web UI: /:prefix',
        ],
        'purge' => [
            'confirm' => 'Delete logs older than :days days?',
            'cancelled' => 'Purge cancelled.',
            'done' => 'Deleted :count rows older than :days days.',
        ],
    ],
];
