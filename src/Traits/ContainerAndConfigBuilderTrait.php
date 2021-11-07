<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Traits;

use DI\Container;
use Szemul\Config\ConfigInterface;
use Szemul\DependencyInjection\Provider\DefinitionProviderInterface;
use Szemul\TestHelper\DependencyInjection\ContainerAndConfigBuilder;
use Szemul\Config\Builder\ConfigBuilderInterface;

trait ContainerAndConfigBuilderTrait
{
    protected Container $container;

    protected function setUpContainer(string ...$envPaths): Container
    {
        $builder = (new ContainerAndConfigBuilder(...$envPaths))
            ->addConfigBuilders(...$this->getConfigBuilders());

        $config = $builder->buildConfig();

        $this->container = $builder->addDefinitionProviders(...$this->getDefinitionProviders($config))
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
    abstract protected function getDefinitionProviders(ConfigInterface $config): array;
}
