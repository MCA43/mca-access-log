<?php

use Mca\AccessLog\Services\AccessLogService;

if (! function_exists('mca_access_log')) {
    function mca_access_log(): AccessLogService
    {
        return app(AccessLogService::class);
    }
}

if (! function_exists('mca_alog')) {
    /** @param  array<string, string|int>  $replace */
    function mca_alog(string $key, array $replace = []): string
    {
        return (string) __('mca-access-log::access-log.'.$key, $replace);
    }
}
