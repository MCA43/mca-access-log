<?php

namespace Mca\AccessLog\Support;

final class McaAccessLogLocale
{
    public static function resolve(): string
    {
        $locale = config('access-log.locale');

        if (is_string($locale) && $locale !== '') {
            return $locale;
        }

        return (string) app()->getLocale();
    }

    public static function apply(): void
    {
        app()->setLocale(self::resolve());
    }
}
