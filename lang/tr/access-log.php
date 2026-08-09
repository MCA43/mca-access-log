<?php

return [
    'app' => [
        'title' => 'Erişim Günlüğü',
        'brand' => 'Erişim Günlüğü',
        'nav_aria' => 'Erişim günlüğü menüsü',
    ],
    'nav' => [
        'back_mca' => 'MCA Hub',
        'logs' => 'İstekler',
        'firewall' => 'Güvenlik duvarı',
    ],
    'filters' => [
        'ip' => 'IP',
        'method' => 'Method',
        'status' => 'Durum',
        'blocked' => 'Engellenen',
        'blocked_all' => 'Tümü',
        'blocked_yes' => 'Sadece engellenen',
        'blocked_no' => 'Sadece izinli',
        'q' => 'Path / UA ara…',
        'apply' => 'Filtrele',
        'clear' => 'Temizle',
    ],
    'fields' => [
        'ip' => 'IP',
        'method' => 'Method',
        'path' => 'Path',
        'status' => 'Durum',
        'user' => 'Kullanıcı',
        'duration' => 'Süre',
        'when' => 'Zaman',
        'user_agent' => 'User agent',
        'hits' => 'İstek',
        'blocked' => 'Engellenen',
        'unique_paths' => 'Benzersiz path',
        'last_seen' => 'Son görülme',
    ],
    'actions' => [
        'view_ip' => 'IP detay',
        'block' => 'Blacklist’e ekle',
        'whitelist' => 'Whitelist’e ekle',
        'block_label' => 'Erişim günlüğünden',
        'whitelist_label' => 'Erişim günlüğünden',
        'block_reason_default' => 'Erişim günlüğünden engellendi',
        'open_firewall' => 'Güvenlik duvarını aç',
    ],
    'flash' => [
        'blocked' => ':ip blacklist’e eklendi.',
        'whitelisted' => ':ip whitelist’e eklendi.',
    ],
    'table' => [
        'empty' => 'Henüz erişim kaydı yok.',
        'top_ips' => 'En çok istek atan IP’ler',
        'blocked_badge' => 'Engellendi',
    ],
    'pages' => [
        'index_title' => 'Erişim istekleri',
        'ip_title' => 'IP :ip',
    ],
    'hint' => [
        'suite' => 'Access ailesi: kurallar için mca/firewall, skor için mca/access-intel (planlı).',
        'retention' => 'Eski kayıtlar: php artisan mca:access-log:purge',
    ],
    'errors' => [
        'root_only' => 'Erişim günlüğünü yalnızca root kullanıcılar görebilir.',
        'firewall_missing' => 'mca/firewall yüklü değil.',
    ],
    'modal' => [
        'ok' => 'Tamam',
        'confirm' => 'Onayla',
        'cancel' => 'İptal',
        'close' => 'Kapat',
        'alert_title' => 'Bilgi',
        'confirm_title' => 'Onay',
    ],
    'confirm' => [
        'block' => 'Bu IP firewall blacklist’e eklensin mi?',
        'whitelist' => 'Bu IP firewall whitelist’e eklensin mi?',
    ],
    'console' => [
        'install' => [
            'start' => 'MCA Access Log kuruluyor…',
            'config_ready' => 'Config hazır',
            'assets_published' => 'Asset’ler yayınlandı',
            'migration_done' => 'Migration tamam',
            'done' => 'MCA Access Log kuruldu.',
            'web_ui' => 'Web arayüzü: /:prefix',
        ],
        'purge' => [
            'confirm' => ':days günden eski loglar silinsin mi?',
            'cancelled' => 'Purge iptal edildi.',
            'done' => ':days günden eski :count kayıt silindi.',
        ],
    ],
];
