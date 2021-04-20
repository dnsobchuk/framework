<?php

namespace App\Http\Action;

use App\Http\Middleware\BasicAuthActionMiddleware;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class CabinetAction
{

    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {
        $username = $request->getAttribute(BasicAuthActionMiddleware::ATTRIBUTE);
        return new HtmlResponse("I am logged in as {$username}");
    }
}