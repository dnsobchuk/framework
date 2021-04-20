<?php

namespace Tests\App\Http\Action;

use App\Http\Action\HelloAction;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class HelloActionTest extends TestCase
{
    public function testGuest(): void
    {
        $action = new HelloAction();
        $request = new ServerRequest();
        $response = $action($request);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('Hello, Guest!', $response->getBody()->getContents());
    }

    public function testJack(): void
    {
        $action = new HelloAction();
        $request = (new ServerRequest())->withQueryParams(['name' => 'Jack']);
        $response = $action($request);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('Hello, Jack!', $response->getBody()->getContents());
    }

}