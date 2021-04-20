<?php


namespace App\Http\Pipeline;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Pipeline
{
    private array $middleware = [];

    public function pipe(callable $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function __invoke(ServerRequestInterface $request, callable $default): ResponseInterface
    {
        $current = array_shift($this->middleware);

        return $current($request, function (ServerRequestInterface $request) use ($default) {
            return $this($request, $default);
        });
    }
}