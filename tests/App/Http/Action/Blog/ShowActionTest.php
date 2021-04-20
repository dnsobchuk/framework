<?php


namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\ShowAction;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class ShowActionTest extends TestCase
{
    public function test404()
    {
        $id = 6;
        $action = new ShowAction();
        $request = (new ServerRequest())->withAttribute('id', $id);
        $response = $action($request);
        self::assertJsonStringEqualsJsonString(json_encode(['error', 'Undefined page']), $response->getBody()->getContents());
    }

    public function testShowBlog()
    {
        $id = 1;
        $action = new ShowAction();
        $request = (new ServerRequest())->withAttribute('id', $id);
        $response = $action($request);
        self::assertJsonStringEqualsJsonString(json_encode(['id' => $id, 'title' => "Post #{$id}"]), $response->getBody()->getContents());
    }
}