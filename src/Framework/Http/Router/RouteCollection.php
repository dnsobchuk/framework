<?php


namespace Framework\Http\Router;


class RouteCollection
{
    private array $routes = [];

    public function any(string $name, string $pattern, \Closure $handler, array $tokens = []): void
    {
        $this->routes[] = new Route($name, $pattern, $handler, [], $tokens);
    }

    public function get(string $name, string $pattern, \Closure $handler, array $tokens = []): void
    {
       $this->routes[] = new Route($name, $pattern, $handler, ['GET'], $tokens);
    }

    public function post(string $name, string $pattern, \Closure $handler, array $tokens = []): void
    {
        $this->routes[] = new Route($name, $pattern, $handler, ['POST'], $tokens);
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}