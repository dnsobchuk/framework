<?php


namespace Framework\Http\Router\Exception;


use JetBrains\PhpStorm\Pure;

class RouteNotFoundException extends \LogicException
{
    #[Pure] public function __construct(private string $name, private array $params)
    {
        parent::__construct("Route {$name} not found.");
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}