# mca/access-log

**Türkçe** | [English](README.md)

Laravel 13 için IP bazlı HTTP erişim günlüğü — filtreler, saklama süresi ve firewall soft-entegrasyonu.  
**Access suite** rolü: `log`.

## Kurulum

```bash
composer require mca/access-log
php artisan mca:access-log:install
```

Root ile `/mca/access-log` açın.

## Yapılandırma

```env
MCA_ACCESS_LOG_ENABLED=true
MCA_ACCESS_LOG_CAPTURE=true
MCA_ACCESS_LOG_SAMPLE_RATE=1.0
MCA_ACCESS_LOG_RETENTION_DAYS=90
```

## Aile bağımlılıkları

| Paket | İlişki |
|---------|----------|
| `mca/firewall` | Önerilen — log’dan ban/whitelist + engellenen istek kaydı |
| `mca/access-intel` | Önerilen (planlı) — sağlık skoru |
| `mca/permission` | Önerilen — root UI |
| `mca/hub` | Önerilen — hub kartı |

## Lisans

MIT
