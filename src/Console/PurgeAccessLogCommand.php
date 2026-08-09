<?php

namespace Mca\AccessLog\Console;

use Illuminate\Console\Command;
use Mca\AccessLog\Services\AccessLogService;
use Mca\AccessLog\Support\McaAccessLogLocale;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'mca:access-log:purge')]
class PurgeAccessLogCommand extends Command
{
    protected $signature = 'mca:access-log:purge
                            {--days= : Retention days (default from config)}
                            {--force : Skip confirmation}';

    protected $description = 'Delete access logs older than retention days';

    public function handle(AccessLogService $logs): int
    {
        McaAccessLogLocale::apply();

        $days = (int) ($this->option('days') ?: config('access-log.retention.days', 90));
        $days = max(1, $days);

        if (! $this->option('force') && ! $this->confirm(mca_alog('console.purge.confirm', ['days' => $days]))) {
            $this->components->warn(mca_alog('console.purge.cancelled'));

            return self::SUCCESS;
        }

        $deleted = $logs->purgeOlderThan($days);
        $this->components->info(mca_alog('console.purge.done', ['count' => $deleted, 'days' => $days]));

        return self::SUCCESS;
    }
}
