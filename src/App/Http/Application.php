<?php


namespace App\Http;


use App\Http\Pipeline\Pipeline;
use Framework\Http\MiddlewareResolver;
use JetBrains\PhpStorm\Pure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application extends Pipeline
{
    private $default;

    #[Pure] public function __construct(private MiddlewareResolver $resolver, callable $default)
    {
       parent::__construct();
       $this->default = $default;
    }

    public function pipe(mixed $middleware): void
    {
        parent::pipe($this->resolver->resolve($middleware));
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this($request, $this->default);
    }
}