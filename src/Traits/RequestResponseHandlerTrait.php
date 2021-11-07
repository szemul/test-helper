<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Traits;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;

trait RequestResponseHandlerTrait
{
    /** @param array<string,string> $query */
    protected function getRequest(
        string $method,
        string $path,
        array $query = [],
        string $body = '',
    ): ServerRequestInterface {
        ServerRequestCreatorFactory::create();

        $headers    = new Headers();
        $bodyStream = new Stream(fopen('php://temp', 'wb+'));

        $bodyStream->write($body);

        return new Request(
            $method,
            new Uri('https', 'example.com', 443, $path, http_build_query($query)),
            $headers,
            [],
            [],
            $bodyStream,
        );
    }

    protected function getResponse(): ResponseInterface
    {
        return new Response();
    }
}
