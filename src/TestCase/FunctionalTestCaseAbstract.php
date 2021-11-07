<?php
declare(strict_types=1);

namespace Szemul\TestHelper\TestCase;

use PHPUnit\Framework\TestCase;
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpContainer(...$this->getEnvPaths());
        $this->setupLogger();
    }

    /** @return string[] */
    abstract protected function getEnvPaths(): array;
}
