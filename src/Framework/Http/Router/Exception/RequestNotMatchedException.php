<?php


namespace Framework\Http\Router\Exception;


use JetBrains\PhpStorm\Pure;
use Psr\Http\Message\RequestInterface;

class RequestNotMatchedException extends \LogicException
{
    #[Pure] public function __construct(private RequestInterface $request)
    {
        parent::__construct('Matches not found');
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

}