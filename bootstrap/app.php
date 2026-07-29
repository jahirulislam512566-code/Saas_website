<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ============================================
        // GLOBAL MIDDLEWARE
        // ============================================
        $middleware->use([
            \Illuminate\Http\Middleware\TrustHosts::class,
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        // ============================================
        // WEB MIDDLEWARE GROUP
        // ============================================
        $middleware->web(append: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // ============================================
        // API MIDDLEWARE GROUP
        // ============================================
        $middleware->api(prepend: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
        ]);
        $middleware->api(append: [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // ============================================
        // ALIAS MIDDLEWARE
        // ============================================
        $middleware->alias([
            // Laravel Default Middleware
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            
            // Custom Middleware - Make sure this matches what you use in routes
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.access' => \App\Http\Middleware\AdminAccess::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);

        // ============================================
        // REDIRECT CONFIGURATION
        // ============================================
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/');

        // ============================================
        // TRUSTED PROXIES
        // ============================================
        $middleware->trustProxies(at: '*');

        // ============================================
        // PREVENT REQUESTS DURING MAINTENANCE
        // ============================================
        $middleware->preventRequestsDuringMaintenance(
            except: [
                'api/health',
                'up',
                'admin',
                'admin/*',
                'login',
                'register',
            ]
        );

        // ============================================
        // TRIM STRINGS (Exclude sensitive data)
        // ============================================
        $middleware->trimStrings(
            except: [
                'password',
                'password_confirmation',
                'current_password',
                'new_password',
                'old_password',
                'card_number',
                'card_cvc',
                'card_expiry',
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ============================================
        // ERROR HANDLING
        // ============================================
        
        // Custom 404 response for API
        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Resource not found',
                    'status' => 404,
                ], 404);
            }
        });

        // Custom 404 response for API
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Route not found',
                    'status' => 404,
                ], 404);
            }
        });

        // Custom 403 response for API
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'You are not authorized to perform this action',
                    'status' => 403,
                ], 403);
            }
        });

        // Custom 401 response for API
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated',
                    'status' => 401,
                ], 401);
            }
        });

        // Custom 500 response for API (in production)
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->wantsJson() && app()->environment('production')) {
                return response()->json([
                    'message' => 'An unexpected error occurred',
                    'status' => 500,
                ], 500);
            }
        });

        // ============================================
        // EXCEPTIONS TO NOT REPORT
        // ============================================
        $exceptions->dontReport([
            \Illuminate\Auth\AuthenticationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            \Symfony\Component\HttpKernel\Exception\HttpException::class,
            \Illuminate\Database\Eloquent\ModelNotFoundException::class,
            \Illuminate\Validation\ValidationException::class,
        ]);

        // ============================================
        // SENSITIVE DATA TO NOT FLASH
        // ============================================
        $exceptions->dontFlash([
            'current_password',
            'password',
            'password_confirmation',
        ]);
    })
    ->create();