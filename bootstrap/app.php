
<?php

    use Illuminate\Foundation\Application;
    use Illuminate\Foundation\Configuration\Exceptions;
    use Illuminate\Foundation\Configuration\Middleware;
    use Illuminate\Session\TokenMismatchException;

    return Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__ . '/../routes/web.php',
            commands: __DIR__ . '/../routes/console.php',
            health: '/up',
        )
        ->withMiddleware(function (Middleware $middleware) {
            $middleware->group('admin', [\App\Http\Middleware\AdminMiddleware::class]);
            $middleware->append(\App\Http\Middleware\VerifySessionTimeout::class);
        })
        ->withExceptions(function (Exceptions $exceptions) {
            $exceptions->render(function (TokenMismatchException $e, $request) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Session expired. Please refresh the page.'], 419);
                }
                return response()->view('errors.419', [], 419);
            });
    })->create();
