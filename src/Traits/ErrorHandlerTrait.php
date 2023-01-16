<?php

namespace Szemul\TestHelper\Traits;

use DI\Container;
use Szemul\ErrorHandler\ErrorHandlerRegistry;
use Szemul\ErrorHandler\Test\ErrorHandlerRegistryMock;

trait ErrorHandlerTrait
{
    protected function mockErrorHandler(Container $container): void
    {
        $container->set(ErrorHandlerRegistry::class, new ErrorHandlerRegistryMock());
    }
}
