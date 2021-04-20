<?php

namespace Tests\App\Http\Action;

use App\Http\Action\AboutAction;
use PHPUnit\Framework\TestCase;

class AboutActionTest extends TestCase
{
    public function testAbout(): void
    {
        $action = new AboutAction();
        $response = $action();
        self::assertEquals('I am a simple site', $response->getBody()->getContents());
    }

}