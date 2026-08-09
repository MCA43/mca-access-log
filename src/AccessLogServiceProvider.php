<?php

namespace Mca\AccessLog;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Mca\AccessLog\Console\InstallAccessLogCommand;
use Mca\AccessLog\Console\PurgeAccessLogCommand;
use Mca\AccessLog\Http\Middleware\EnsureMcaAccessLogRoot;
use Mca\AccessLog\Http\Middleware\LogAccessRequest;
use Mca\AccessLog\Http\Middleware\SetMcaAccessLogLocale;
use Mca\AccessLog\Listeners\RecordFirewallBlock;
use Mca\AccessLog\Services\AccessLogService;
use Mca\Firewall\Events\IpBlocked;

class AccessLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/access-log.php', 'access-log');
        $this->app->singleton(AccessLogService::class);
    }

    public function boot(): void
    {
        if (! config('access-log.enabled', true)) {
            return;
        }

        $this->registerPublishing();
        $this->registerMiddleware();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'mca-access-log');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'mca-access-log');
        $this->registerRoutes();
        $this->registerLogging();
        $this->registerFirewallListener();
        $this->registerHub();

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallAccessLogCommand::class,
                PurgeAccessLogCommand::class,
            ]);
        }
    }

    protected function registerHub(): void
    {
        if (! function_exists('mca_hub_register')) {
            return;
        }

        mca_hub_register('access-log', [
            'enabled' => fn () => (bool) config('access-log.enabled', true),
        ]);
    }

    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/access-log.php' => config_path('access-log.php'),
        ], 'mca-access-log-config');

        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/mca-access-log'),
        ], 'mca-access-log-assets');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/mca-access-log'),
        ], 'mca-access-log-views');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'mca-access-log-migrations');
    }

    protected function registerMiddleware(): void
    {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('mca.access-log.root', EnsureMcaAccessLogRoot::class);
        $router->aliasMiddleware('mca.access-log.locale', SetMcaAccessLogLocale::class);
        $router->aliasMiddleware('mca.access-log', LogAccessRequest::class);
    }

    protected function registerRoutes(): void
    {
        if (! config('access-log.routes.load_package_routes', true)) {
            return;
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    protected function registerLogging(): void
    {
        if (! config('access-log.logging.enabled', true) || ! config('access-log.logging.auto_register', true)) {
            return;
        }

        $this->app->booted(function (): void {
            /** @var Router $router */
            $router = $this->app['router'];
            $groups = config('access-log.logging.groups', ['web', 'api']);

            if (! is_array($groups)) {
                return;
            }

            foreach ($groups as $group) {
                if (is_string($group) && $group !== '') {
                    $router->pushMiddlewareToGroup($group, LogAccessRequest::class);
                }
            }
        });
    }

    protected function registerFirewallListener(): void
    {
        if (! class_exists(IpBlocked::class)) {
            return;
        }

        Event::listen(IpBlocked::class, RecordFirewallBlock::class);
    }
}
