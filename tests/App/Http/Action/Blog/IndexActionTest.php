<?php


namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\IndexAction;
use PHPUnit\Framework\TestCase;

class IndexActionTest extends TestCase
{
    public function testIndex()
    {
        $action = new IndexAction();
        $response = $action();
        $testData = json_encode([
            ['id' => 2, 'title' => 'The Second Post'],
            ['id' => 1, 'title' => 'The First Post']
        ]);
        self::assertEquals(200, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString($testData, $response->getBody()->getContents());
    }
}