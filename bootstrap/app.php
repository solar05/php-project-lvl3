<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

 $app->withFacades();

 $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

if (env('APP_DEBUG')) {
    $app->configure('app');
    $app->configure('debugbar');
    $app->register(Barryvdh\Debugbar\LumenServiceProvider::class);
}

$app->bind('GuzzleClient', function () {
    return new \GuzzleHttp\Client();
});

$app->bind('GuzzleRequest7', function ($app, $parameters) {
    return new \GuzzleHttp\Psr7\Request($parameters['method'], $parameters['URL']);
});

$app->bind('DiDoc', function ($app, $parameters) {
    return new \DiDom\Document($parameters['document']);
});

$app->bind('SM', function ($app, $parameters) {
    return new SM\StateMachine\StateMachine($parameters['domain'], config('FSM_CONFIG'));
});


config(['FSM_CONFIG' => [
    'graph'         => 'domainGraph',
    'property_path' => 'state',
    'states'        => [
        'initialized',
        'pending',
        'completed',
        'cancelled'],
    'transitions' => [
        'send' => [
            'from' => ['initialized'],
            'to'   => 'pending'
        ],
        'complete' => [
            'from' => ['cancelled', 'pending'],
            'to'   => 'completed'
        ],
        'cancel' => [
            'from' => ['pending'],
            'to'   => 'cancelled'
        ]]]]);
/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
});

return $app;
