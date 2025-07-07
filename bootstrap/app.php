<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        apiPrefix: 'api/v1'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn(Request $request) => url('/') );
        $middleware->redirectUsersTo(fn(Request $request) => url('/') );

        $middleware->statefulApi();
        // $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $exception, $request) {

            if ($exception instanceof ValidationException) {
                return sendValidationResponse($exception->validator);
            }
            elseif($exception instanceof AuthenticationException)
            {
                return sendUnauthorizedResponse();
            }
            elseif($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException)
            {
                return sendNotFoundResponse();
            }
            elseif($exception instanceof MethodNotAllowedHttpException)
            {
                return sendMethodNotAllowedResponse();
            }
            elseif($exception instanceof AccessDeniedHttpException)
            {
                return sendDontHavePermissionResponse();
            }

            return null;
        });
    })->create();
