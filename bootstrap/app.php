<?php

use App\Http\Middleware\EnsureAccountIsActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\EnsureUserHasPageAccess;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo(function (Request $request) {
            return $request->user()?->canAccessPage('dashboard')
                ? route('admin.dashboard')
                : route('home');
        });
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'access' => EnsureUserHasPageAccess::class,
            'active' => EnsureAccountIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $exception, Request $request) {
            if ($exception instanceof AuthenticationException || $exception instanceof ValidationException) {
                return null;
            }

            if ($request->expectsJson()) {
                return null;
            }

            $status = $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : 500;

            $message = $exception->getMessage() !== ''
                ? $exception->getMessage()
                : match ($status) {
                    403 => 'Anda tidak memiliki akses ke halaman ini.',
                    404 => 'Halaman yang Anda cari tidak ditemukan.',
                    default => 'Terjadi kesalahan pada aplikasi.',
                };

            return response()->view('errors.app', [
                'status' => $status,
                'message' => $message,
            ], $status);
        });
    })->create();
