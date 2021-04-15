<?php


namespace Framework\Http\Router;


use Aura\Router\Exception\RouteNotFound;
use Aura\Router\RouterContainer;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class AuraRouterAdapter implements Router
{
    public function __construct(private RouterContainer $aura){}

    /**
     * @param ServerRequestInterface $request
     * @return Result
     */
    public function match(ServerRequestInterface $request): Result
    {
       $matcher = $this->aura->getMatcher();
       if($route = $matcher->match($request)) {
           return new Result($route->name, $route->handler, $route->attributes);
       }
       throw new RequestNotMatchedException($request);
    }

    /**
     * @param string $name
     * @param array $params
     * @return string
     * @throws RouteNotFoundException
     */
    public function generate(string $name, array $params = []): string
    {
        $generator = $this->aura->getGenerator();
        try {
            return $generator->generate($name, $params);
        } catch (RouteNotFound $e) {
            throw new RouteNotFoundException($name, $params, $e);
        }
    }
}