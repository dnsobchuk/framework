<?php


namespace App\Http\Middleware;


use Laminas\Diactoros\Response\JsonResponse;

class NotFoundHandler
{
    public function __invoke()
    {
        new JsonResponse(['error', 'Undefined page'], 404);
    }
}