<?php


namespace Framework\Http\Router;


use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\RequestInterface;

class SimpleRouter implements Router
{
    public function __construct(private RouteCollection $routes){}

    public function match(RequestInterface $request): Result
    {
        foreach ($this->routes->getRoutes() as $route) {
           $result = $route->match($request);
           if($result) {
               return $result;
           }
        }
        throw new RequestNotMatchedException($request);
    }

    public function generate(string $name, array $params = []): string
    {
        foreach ($this->routes->getRoutes() as $route) {
            if(null !== $url = $route->generate($name, array_filter($params))) {
                return $url;
            }
        }
        throw new RouteNotFoundException($name, $params);
    }
}