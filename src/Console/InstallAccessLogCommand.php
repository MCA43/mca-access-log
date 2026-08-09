<?php

namespace Mca\AccessLog\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Mca\AccessLog\Support\McaAccessLogLocale;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'mca:access-log:install')]
class InstallAccessLogCommand extends Command
{
    protected $signature = 'mca:access-log:install
                            {--no-assets : Skip CSS publish}';

    protected $description = 'Install MCA Access Log (migration, assets)';

    public function handle(): int
    {
        McaAccessLogLocale::apply();

        $this->components->info(mca_alog('console.install.start'));

        if (! file_exists(config_path('access-log.php'))) {
            $this->callSilent('vendor:publish', ['--tag' => 'mca-access-log-config']);
        }
        $this->components->task(mca_alog('console.install.config_ready'), fn () => true);

        if (! $this->option('no-assets')) {
            $this->callSilent('vendor:publish', [
                '--tag' => 'mca-access-log-assets',
                '--force' => true,
            ]);
            $this->components->task(mca_alog('console.install.assets_published'), fn () => true);
        }

        Artisan::call('migrate', ['--force' => true]);
        $this->output->write(Artisan::output());
        $this->components->task(mca_alog('console.install.migration_done'), fn () => true);

        $this->newLine();
        $this->components->info(mca_alog('console.install.done'));
        $this->line('  '.mca_alog('console.install.web_ui', [
            'prefix' => config('access-log.routes.web.prefix', 'mca/access-log'),
        ]));

        return self::SUCCESS;
    }
}
