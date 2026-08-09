<?php

namespace Mca\AccessLog\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ip',
        'method',
        'path',
        'route_name',
        'status_code',
        'user_id',
        'user_agent',
        'referer',
        'duration_ms',
        'is_blocked',
        'meta',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'duration_ms' => 'integer',
            'is_blocked' => 'boolean',
            'meta' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function getTable(): string
    {
        return (string) config('access-log.table', 'mca_access_logs');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    public function scopeForIp(Builder $query, string $ip): Builder
    {
        return $query->where('ip', $ip);
    }

    public function scopeBlocked(Builder $query): Builder
    {
        return $query->where('is_blocked', true);
    }
}
