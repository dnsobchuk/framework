<?php

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ServerRequestInterface;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Initialisation

$routes = new RouteCollection();

$routes->get('home', '/', function (ServerRequestInterface $request) {
    $name = $request->getQueryParams()['name'] ?? 'Guest';
    return new HtmlResponse("Hello, {$name}");
});

$routes->get('about', '/about', $action = function () {
    return new HtmlResponse('I am a simple site');
});

$routes->get('blog', '/blog', function () {
    return new JsonResponse([
        ['id' => 2, 'title' => 'The Second Post'],
        ['id' => 1, 'title' => 'The First Post']
    ]);
});

$routes->get('blog_show', '/blog/{id}', function (ServerRequestInterface $request) {
    $id = $request->getAttributes()['id'];
    if($id > 5) {
        return new JsonResponse(['error', 'Undefined page'], 404);
    }
    return new JsonResponse(['id' => $id, 'title' => "Post #{$id}"]);
}, ['id' => '\d+']);

$router = new Router($routes);

### Running
$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $action = $result->getHandler();
    $response = $action($request);
} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error', 'Undefined page'], 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'dnsobchuk');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);

