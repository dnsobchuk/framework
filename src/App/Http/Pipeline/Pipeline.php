<?php


namespace App\Http\Pipeline;


use JetBrains\PhpStorm\Pure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Pipeline
{
    private \SplQueue $queue;

    #[Pure] public function __construct(){
        $this->queue = new \SplQueue();
    }

    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        $delegate = new Next(clone $this->queue, $next);
        return $delegate($request);
    }

    public function pipe(callable $middleware): void
    {
        $this->queue->enqueue($middleware);
    }
}