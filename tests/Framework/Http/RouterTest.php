<?php


namespace Framework\Http;

use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\ShowAction;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class RouterTest extends TestCase
{
    public function testCorrectMethod()
    {
        $routes = new RouteCollection();
        $handler = IndexAction::class;
        $routes->get($nameGet = 'blog', '/blog', $handler);
        $routes->post($namePost = 'blog_edit', '/blog', $handler);

        $router = new Router($routes);

        $result = $router->match($this->buildRequest('GET', '/blog'));
        self::assertEquals($nameGet, $result->getName());
        self::assertEquals($handler, $result->getHandler());

        $result = $router->match($this->buildRequest('POST', '/blog'));
        self::assertEquals($namePost, $result->getName());
        self::assertEquals($handler, $result->getHandler());
    }

    public function testMissingMethod()
    {
        $routes = new RouteCollection();

        $handler = IndexAction::class;

        $routes->post('blog', '/blog', $handler);

        $router = new Router($routes);

        $this->expectException(RequestNotMatchedException::class);
        $router->match($this->buildRequest('DELETE', '/blog'));
    }

    public function testCorrectAttributes()
    {
        $routes = new RouteCollection();

        $handler = ShowAction::class;
        $routes->get($name = 'blog_show', '/blog/{id}', $handler, ['id' => '\d+']);

        $router = new Router($routes);

        $result = $router->match($this->buildRequest('GET', '/blog/5'));

        self::assertEquals($name, $result->getName());
        self::assertEquals(['id' => '5'], $result->getAttributes());
    }

    public function testIncorrectAttributes()
    {
        $routes = new RouteCollection();

        $handler = ShowAction::class;

        $routes->get($name = 'blog_show', '/blog/{id}', $handler, ['id' => '\d+']);

        $router = new Router($routes);

        $this->expectException(RequestNotMatchedException::class);
        $router->match($this->buildRequest('GET', '/blog/slug'));
    }

    public function testGenerate()
    {
        $routes = new RouteCollection();
        $handler = IndexAction::class;
        $handlerShow = ShowAction::class;
        $routes->get('blog', '/blog', $handler);
        $routes->get('blog_show', '/blog/{id}', $handlerShow, ['id' => '\d+']);

        $router = new Router($routes);

        self::assertEquals('/blog', $router->generate('blog'));
        self::assertEquals('/blog/5', $router->generate('blog_show', ['id' => 5]));
    }

    public function testGenerateMissingAttributes()
    {
        $routes = new RouteCollection();
        $handler = ShowAction::class;
        $routes->get($name = 'blog_show', '/blog/{id}', $handler, ['id' => '\d+']);

        $router = new Router($routes);

        $this->expectException(\InvalidArgumentException::class);
        $router->generate('blog_show', ['slug' => 'post']);
    }

    public function buildRequest($method, $uri): RequestInterface
    {
        return (new ServerRequest())
            ->withMethod($method)
            ->withUri(new Uri($uri));
    }

}