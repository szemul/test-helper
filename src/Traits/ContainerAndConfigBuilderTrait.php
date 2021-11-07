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

    protected function setUpContainer(?ConfigInterface $config, string ...$envPaths): Container
    {
        $builder = new ContainerAndConfigBuilder(...$envPaths);

        if (null !== $config) {
            $builder->setConfig($config);
        }

        $builder->addConfigBuilders(...$this->getConfigBuilders())
            ->buildConfig()
            ->addDefinitionProviders(...$this->getDefinitionProviders($builder->getConfig()))
            ->buildContainer();

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
