<?php

use Illuminate\Support\Facades\Route;

$web = config('access-log.routes.web', []);
$prefix = $web['prefix'] ?? 'mca/access-log';
$middleware = $web['middleware'] ?? ['web', 'auth', 'mca.access-log.root', 'mca.access-log.locale'];
$namePrefix = config('access-log.routes.web.name_prefix', 'mca.access-log.');
$controllers = config('access-log.controllers.web', []);
$logs = $controllers['logs'] ?? \Mca\AccessLog\Http\Controllers\Web\LogController::class;

Route::prefix($prefix)
    ->middleware($middleware)
    ->name($namePrefix)
    ->group(function () use ($logs) {
        Route::get('/', [$logs, 'index'])->name('index');
        Route::get('/ip/{ip}', [$logs, 'ip'])->where('ip', '[0-9a-fA-F:\.]+')->name('ip');
        Route::post('/ip/{ip}/block', [$logs, 'block'])->where('ip', '[0-9a-fA-F:\.]+')->name('block');
        Route::post('/ip/{ip}/whitelist', [$logs, 'whitelist'])->where('ip', '[0-9a-fA-F:\.]+')->name('whitelist');
    });
