<?php


namespace App\Http\Middleware;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class BasicAuthActionMiddleware
{
    public const ATTRIBUTE = '_user';

    public function __construct(private array $users){}

    public function __invoke(ServerRequestInterface $request, callable $next): EmptyResponse|HtmlResponse
    {
        $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
        $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;

        if(!empty($username) && !empty($password)) {
            foreach ($this->users as $name => $pass) {
                if($username === $name && $password === $pass) {
                    return ($next)($request->withAttribute(self::ATTRIBUTE, $username));
                }
            }
        }
        return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Restricted area']);
    }

}