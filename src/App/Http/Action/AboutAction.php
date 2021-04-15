<?php

namespace App\Http\Action;

use Laminas\Diactoros\Response\JsonResponse;

class AboutAction
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse('I am a simple site');
    }

}