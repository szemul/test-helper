<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Traits;

use DI\Container;
use Szemul\DependencyInjection\Provider\DefinitionProviderInterface;
use Szemul\TestHelper\Di\ContainerAndConfigBuilder;
use Szemul\Config\Builder\ConfigBuilderInterface;

trait ContainerAndConfigBuilderTrait
{
    protected Container $container;

    protected function setUpContainer(string ...$envPaths): Container
    {
        $this->container = (new ContainerAndConfigBuilder(...$envPaths))
            ->addConfigBuilders(...$this->getConfigBuilders())
            ->addDefinitionProviders(...$this->getDefinitionProviders())
            ->build();

        return $this->container;
    }

    protected function getContainer(): Container
    {
        return $this->container;
    }

    /** @return ConfigBuilderInterface[] */
    abstract protected function getConfigBuilders(): array;

    /** @return DefinitionProviderInterface[] */
    abstract protected function getDefinitionProviders(): array;
}
