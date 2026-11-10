<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

// Vercel hanya mengizinkan penulisan di /tmp. Pindahkan storage Laravel
// (view cache, log, file sementara) ke sana agar aplikasi bisa berjalan.
if (getenv('VERCEL')) {
    $storagePath = '/tmp/storage';

    foreach ([
        'app/private',
        'app/public',
        'framework/cache/data',
        'framework/sessions',
        'framework/testing',
        'framework/views',
        'logs',
    ] as $directory) {
        $path = $storagePath.'/'.$directory;

        if (! is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    $app->useStoragePath($storagePath);
}

return $app;
