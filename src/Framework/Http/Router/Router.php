<?php


namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

interface Router
{
    /**
     * @param ServerRequestInterface $request
     * @return Result
     * @throws RequestNotMatchedException
     */
    public function match(ServerRequestInterface $request): Result;

    /**
     * @param string $name
     * @param array $params
     * @throws RouteNotFoundException
     * @return string
     */
    public function generate(string $name, array $params = []): string;
}