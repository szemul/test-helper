<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Bootstrap;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Szemul\Framework\Bootstrap\AppBootstrap;
use Szemul\Framework\Router\RouterInterface;

class TestBootstrap extends AppBootstrap
{
    protected ResponseInterface $response;

    public function __construct(
        private ServerRequestInterface $request,
        ?RouterInterface $router = null,
        MiddlewareInterface ...$middlewares,
    ) {
        parent::__construct($router, ...$middlewares);
    }

    public function __invoke(ContainerInterface $container): void
    {
        $app = $this->setupApp($container);

        $this->addMiddlewares($app);

        $this->addSystemMiddlewares($app, $container);

        $this->setRoutes($app);

        $this->response = $app->handle($this->request);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
