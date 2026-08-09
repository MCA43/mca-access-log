# Changelog

## [0.1.0] - 2026-08-09

### Added
- HTTP access logging middleware (`mca.access-log`) with sample rate
- `mca_access_logs` table (IP, method, path, status, UA, duration, blocked flag)
- Admin UI: filters, top IPs, IP detail page
- Soft-dep on `mca/firewall`: `IpBlocked` listener + blacklist/whitelist actions
- Retention purge: `php artisan mca:access-log:purge`
- Install: `php artisan mca:access-log:install`
- Hub integration (`extra.mca`, suite: access / log)

### Notes
- Skips MCA admin paths and static assets by default
- Pair with `mca/firewall` (rules) and planned `mca/access-intel` (scoring)
