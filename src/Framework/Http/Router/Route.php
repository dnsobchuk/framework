<?php


namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\RequestInterface;

class Route
{
    public function __construct(
        public string $name,
        public string $pattern,
        public string $handler,
        public array $methods,
        public array $tokens = []
    ) {}


    public function match(RequestInterface $request): ?Result
    {
        if($this->methods && !\in_array($request->getMethod(), $this->methods, true)) {
            return null;
        }
        $pattern = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) {
            $argument = $matches[1];
            $replace = $this->tokens[$argument] ?? '[^}]+';
            return "(?P<{$argument}>{$replace})";
        }, $this->pattern);

        $path = $request->getUri()->getPath();
        if(preg_match("~^{$pattern}$~i", $path, $matches)) {
            return new Result(
                $this->name,
                $this->handler,
                array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY)
            );
        }
        return null;
    }

    public function generate(string $name, array $params = []): ?string
    {
        if($name !== $this->name) {
            return null;
        }
        $url = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) use (&$params) {
            $argument = $matches[1];
            if(!array_key_exists($argument, $params)) {
                throw new \InvalidArgumentException("Missing parameter '{$argument}'");
            }
            return $params[$argument];
        }, $this->pattern);

        if($url !== null) {
            return $url;
        }
        return null;
    }
}