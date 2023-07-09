<?php
declare(strict_types=1);

namespace Szemul\TestHelper\TestCase;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Szemul\Router\RouterInterface;
use Szemul\SlimErrorHandlerBridge\ErrorHandlerFactory\ErrorHandlerFactory;
use Szemul\TestHelper\Bootstrap\TestBootstrap;

abstract class FullFunctionalTestCaseAbstract extends FunctionalTestCaseAbstract
{
    protected function processRequest(ServerRequestInterface $request): ResponseInterface
    {
        $appBootstrapper = new TestBootstrap($request, $this->getRouter(), new ErrorHandlerFactory(), ...$this->getMiddlewares());

        $appBootstrapper($this->container);

        return $appBootstrapper->getResponse();
    }

    /** @return MiddlewareInterface[] */
    abstract protected function getMiddlewares(): array;

    abstract protected function getRouter(): RouterInterface;
}
