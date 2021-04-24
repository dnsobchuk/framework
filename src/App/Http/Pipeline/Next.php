<?php


namespace App\Http\Pipeline;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Next
{
    private $next;

    public function __construct(private \SplQueue $queue, callable $next) {
        $this->next = $next;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return ($this->next)($request);
        }

        $current = $this->queue->dequeue();

        return $current($request, function (ServerRequestInterface $request) {
            return $this($request);
        });
    }
}