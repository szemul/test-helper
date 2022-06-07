<?php

declare(strict_types=1);

namespace Szemul\TestHelper\TestCase;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Szemul\Config\ConfigInterface;
use Szemul\Framework\Action\ActionAbstract;
use Szemul\TestHelper\Traits\ContainerAndConfigBuilderTrait;
use Szemul\TestHelper\Traits\JsonApiAssertionTrait;
use Szemul\TestHelper\Traits\LogHandlerTrait;
use Szemul\TestHelper\Traits\RequestResponseHandlerTrait;

abstract class FunctionalTestCaseAbstract extends TestCase
{
    use ContainerAndConfigBuilderTrait;
    use JsonApiAssertionTrait;
    use LogHandlerTrait;
    use RequestResponseHandlerTrait;

    /** @return string[] */
    abstract protected function getEnvPaths(): array;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpContainer($this->getConfig(), ...$this->getEnvPaths());
        $this->setupLogger();
    }

    protected function getConfig(): ?ConfigInterface
    {
        return null;
    }

    protected function getAction(string $actionClassName): ActionAbstract
    {
        return $this->getContainer()->get($actionClassName);
    }

    protected function callAction(string $actionClassName, string $method, array $requestBody): ResponseInterface
    {
        $action   = $this->getAction($actionClassName);
        $request  = $this->getRequest($method, '', []);
        $request  = $request->withParsedBody($requestBody);
        $response = $action($request, $this->getResponse(), []);

        $response->getBody()->rewind();

        return $response;
    }
}
