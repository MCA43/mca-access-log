<?php

namespace Mca\AccessLog\Listeners;

use Mca\AccessLog\Services\AccessLogService;
use Mca\Firewall\Events\IpBlocked;

class RecordFirewallBlock
{
    public function __construct(private readonly AccessLogService $logs) {}

    public function handle(IpBlocked $event): void
    {
        if (! config('access-log.enabled', true) || ! config('access-log.logging.enabled', true)) {
            return;
        }

        $this->logs->recordBlocked($event->ip, $event->path, $event->method, [
            'source' => 'firewall',
        ]);
    }
}
