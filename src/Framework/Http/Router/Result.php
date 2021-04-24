<?php


namespace Framework\Http\Router;

class Result
{
    private string $name;
    private array $attributes;
    private mixed $handler;


    public function __construct(string $name, mixed $handler, array $attributes){
        $this->name = $name;
        $this->attributes = $attributes;
        $this->handler = $handler;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandler(): mixed
    {
        return $this->handler;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}