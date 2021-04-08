<?php


namespace Framework\Http\Router;


class Route
{
    public function __construct(
        public string $name,
        public string $pattern,
        public string $handler,
        public array $methods,
        public array $tokens = []
    ) {}
}