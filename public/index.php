<?php

use App\Http\Action;
use App\Http\Middleware;
use App\Http\Pipeline\Pipeline;
use Aura\Router\RouterContainer;
use Framework\Http\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Initialisation

$params = [
    'users' => ['admin' => 'password']
];

$aura = new RouterContainer();

$routes = $aura->getMap();

$routes->get('home', '/', Action\HelloAction::class);
$routes->get('about', '/about', Action\AboutAction::class);
$routes->get('cabinet', '/cabinet', [
    new Middleware\BasicAuthActionMiddleware($params['users']),
    Action\CabinetAction::class
]);
$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);
$resolver = new MiddlewareResolver();
$pipeline = new Pipeline();
$pipeline->pipe($resolver->resolve(Middleware\ProfilerMiddleware::class));

### Running

$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }

    $handlers = $result->getHandler();
    foreach (is_array($handlers) ? $handlers : [$handlers] as $handler) {
        $pipeline->pipe($resolver->resolve($handler));
    }
} catch (RequestNotMatchedException $e) {}

$response = $pipeline($request, new Middleware\NotFoundHandler());

### Postprocessing

$response = $response->withHeader('X-Developer', 'dnsobchuk');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);

