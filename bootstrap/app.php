<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo('/');

        $middleware->appendToGroup('web', \App\Http\Middleware\EnsureEmailIsVerified::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\RecordLastActivedTime::class);
        $middleware->prependToGroup('api', \App\Http\Middleware\AcceptHeader::class);
        $middleware->alias([
            'change-locale' => \App\Http\Middleware\ChangeLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->respond(function (Response $response, \Throwable $e): Response {
            if (! $response instanceof JsonResponse) {
                return $response;
            }

            $data = $response->getData(true);

            if (is_array($data) && ! array_key_exists('code', $data)) {
                $data['code'] = $e->getCode();
                $response->setData($data);
            }

            return $response;
        });
    })->create();
