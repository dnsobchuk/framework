<?php


namespace Framework\Http\Router;

class Result
{
    public string $anotherField;

    public function __construct(
        private string $name,
        private \Closure $handler,
        private array $attributes
    ){}

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandler(): \Closure
    {
        return $this->handler;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}