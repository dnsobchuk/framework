<?php

use App\Http\Action;
use App\Http\Middleware;
use App\Http\Pipeline\Pipeline;
use Aura\Router\RouterContainer;
use Framework\Http\ActionResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ServerRequestInterface;

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
$routes->get('cabinet', '/cabinet', function (ServerRequestInterface $request) use ($params) {

    $pipeline = new Pipeline();
    $pipeline->pipe(new Middleware\ProfilerMiddleware());
    $pipeline->pipe(new Middleware\BasicAuthActionMiddleware($params['users']));
    $pipeline->pipe(new Action\CabinetAction());

    return $pipeline($request, function () {
        return new HtmlResponse('Undefined page', 404);
    });
});
$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);
$resolver = new ActionResolver();
### Running
$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $handler = $result->getHandler();
    $action = $resolver->resolve($handler);
    $response = $action($request);
} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error', 'Undefined page'], 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'dnsobchuk');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);

