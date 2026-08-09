<?php

return [

    'enabled' => env('MCA_ACCESS_LOG_ENABLED', true),

    'locale' => env('MCA_ACCESS_LOG_LOCALE'),

    'table' => env('MCA_ACCESS_LOG_TABLE', 'mca_access_logs'),

    /*
    |--------------------------------------------------------------------------
    | Request logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('MCA_ACCESS_LOG_CAPTURE', true),
        'auto_register' => env('MCA_ACCESS_LOG_AUTO_REGISTER', true),
        'groups' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('MCA_ACCESS_LOG_GROUPS', 'web,api'))
        ))),
        // 1.0 = every request, 0.1 = ~10%
        'sample_rate' => (float) env('MCA_ACCESS_LOG_SAMPLE_RATE', 1.0),
        'skip_methods' => ['OPTIONS'],
        'skip_paths' => [
            'up',
            'mca/access-log',
            'mca/access-log/*',
            'mca/access-intel',
            'mca/access-intel/*',
            'mca/firewall',
            'mca/firewall/*',
            'mca',
            'mca/*',
        ],
        'skip_extensions' => ['css', 'js', 'map', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'ico', 'svg', 'woff', 'woff2', 'ttf'],
        'store_user_agent' => env('MCA_ACCESS_LOG_STORE_UA', true),
        'store_referer' => env('MCA_ACCESS_LOG_STORE_REFERER', true),
        'max_path_length' => 500,
        'max_ua_length' => 512,
    ],

    'retention' => [
        'days' => (int) env('MCA_ACCESS_LOG_RETENTION_DAYS', 90),
    ],

    'routes' => [
        'load_package_routes' => env('MCA_ACCESS_LOG_LOAD_ROUTES', true),
        'web' => [
            'prefix' => env('MCA_ACCESS_LOG_ROUTE_PREFIX', 'mca/access-log'),
            'middleware' => array_filter(explode(',', (string) env(
                'MCA_ACCESS_LOG_MIDDLEWARE',
                'web,auth,mca.access-log.root,mca.access-log.locale'
            ))),
            'name_prefix' => 'mca.access-log.',
        ],
    ],

    'controllers' => [
        'web' => [
            'logs' => \Mca\AccessLog\Http\Controllers\Web\LogController::class,
        ],
    ],

    'views' => [
        'namespace' => env('MCA_ACCESS_LOG_VIEW_NAMESPACE', 'mca-access-log'),
        'layout' => env('MCA_ACCESS_LOG_VIEW_LAYOUT', 'mca-access-log::layouts.app'),
    ],

    'ui' => [
        'title' => env('MCA_ACCESS_LOG_UI_TITLE'),
        'class_prefix' => 'mca-alog',
        'assets' => [
            'css' => 'vendor/mca-access-log/mca-access-log.css',
            'js' => 'vendor/mca-access-log/mca-access-log.js',
            'ui' => 'vendor/mca-permission/mca-ui.css',
            'ui_js' => 'vendor/mca-permission/mca-ui.js',
        ],
    ],

    'access' => [
        'use_permission_root' => env('MCA_ACCESS_LOG_USE_PERMISSION_ROOT', true),
        'role_column' => env('MCA_ACCESS_LOG_ROLE_COLUMN', 'role_id'),
        'root_role' => env('MCA_ACCESS_LOG_ROOT_ROLE', 'root'),
    ],

];
