<?php

/**
 * Serverless function entry point for Vercel PHP runtime.
 * Laravel's public/index.php is required via this wrapper.
 */

// Vercel menyediakan filesystem read-only kecuali /tmp. Arahkan cache bootstrap
// Laravel ke /tmp supaya deployment dari Git tidak gagal menulis packages.php.
if (getenv('VERCEL')) {
    $bootstrapCachePath = '/tmp/bootstrap-cache';

    if (! is_dir($bootstrapCachePath)) {
        mkdir($bootstrapCachePath, 0777, true);
    }

    foreach ([
        'APP_CONFIG_CACHE' => 'config.php',
        'APP_EVENTS_CACHE' => 'events.php',
        'APP_PACKAGES_CACHE' => 'packages.php',
        'APP_ROUTES_CACHE' => 'routes.php',
        'APP_SERVICES_CACHE' => 'services.php',
    ] as $key => $file) {
        $value = $bootstrapCachePath.'/'.$file;

        putenv($key.'='.$value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

require __DIR__.'/../public/index.php';
