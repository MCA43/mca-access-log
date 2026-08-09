# mca/access-log

**English** | [Türkçe](README.tr.md)

HTTP access logging by IP for Laravel 13 — filters, retention, and firewall soft-integration.  
**Access suite** role: `log`.

## Install

```bash
composer require mca/access-log
php artisan mca:access-log:install
```

Open `/mca/access-log` as root.

## Config

```env
MCA_ACCESS_LOG_ENABLED=true
MCA_ACCESS_LOG_CAPTURE=true
MCA_ACCESS_LOG_AUTO_REGISTER=true
MCA_ACCESS_LOG_GROUPS=web,api
MCA_ACCESS_LOG_SAMPLE_RATE=1.0
MCA_ACCESS_LOG_RETENTION_DAYS=90
```

## Suite dependencies

| Package | Relation |
|---------|----------|
| `mca/firewall` | Suggested — block/whitelist from log UI + blocked request records |
| `mca/access-intel` | Suggested (planned) — health scoring |
| `mca/permission` | Suggested — root admin UI |
| `mca/hub` | Suggested — dashboard card |

## Commands

```bash
php artisan mca:access-log:purge --days=90 --force
```

## License

MIT
