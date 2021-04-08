<?php


namespace Framework\Http\Router;

class Result
{
    public string $anotherField;

    public function __construct(
        private string $name,
        private string $handler,
        private array $attributes
    ){}

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}