<?php


namespace Tests\Framework\Http\Pipeline;


use App\Http\Pipeline\Pipeline;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class PipelineTest extends TestCase
{
    public function testPipe()
    {
        $pipeline = new Pipeline();

        $pipeline->pipe(new Middleware1);
        $pipeline->pipe(new Middleware2);

        $response = $pipeline(new ServerRequest(), new Last());

        $this->assertJsonStringEqualsJsonString(
            json_encode(['middleware-1' => 1, 'middleware-2' => 2]),
            $response->getBody()->getContents()
        );
    }
}

class Middleware1
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('middleware-1', 1));
    }
}

class Middleware2
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('middleware-2', 2));
    }
}

class Last
{
    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        return new JsonResponse($request->getAttributes());
    }
}