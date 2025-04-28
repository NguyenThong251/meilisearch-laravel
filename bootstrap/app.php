<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Meilisearch\Client;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth:api' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create()
    ->bind('meilisearch', function () {
        $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));
        $client->index('tasks')->updateSearchableAttributes(['name', 'description']);
        $client->index('tasks')->updateFilterableAttributes(['status', 'priority', 'creator_id', 'project_id']);
        return $client;
    });