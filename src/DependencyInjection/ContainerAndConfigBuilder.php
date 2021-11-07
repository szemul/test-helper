<?php
declare(strict_types=1);

namespace Szemul\TestHelper\DependencyInjection;

use DI\Container;
use DI\ContainerBuilder;
use Szemul\Config\Builder\ConfigBuilderInterface;
use Szemul\Config\Config;
use Szemul\Config\Environment\EnvironmentHandler;
use Szemul\DependencyInjection\Provider\DefinitionProviderInterface;

class ContainerAndConfigBuilder
{
    /** @var ConfigBuilderInterface[] */
    protected array $configBuilders = [];
    /** @var DefinitionProviderInterface[] */
    protected array $definitionProviders = [];
    /** @var string[] */
    protected array $envFilePaths = [];

    public function __construct(string ...$envFilePaths)
    {
        $this->envFilePaths = $envFilePaths;
    }

    public function addConfigBuilders(ConfigBuilderInterface ...$configBuilders): static
    {
        $this->configBuilders = array_merge($this->configBuilders, $configBuilders);

        return $this;
    }

    public function addDefinitionProviders(DefinitionProviderInterface ...$definitionProviders): static
    {
        $this->definitionProviders = array_merge($this->definitionProviders, $definitionProviders);

        return $this;
    }

    public function build(): Container
    {
        return $this->buildContainer($this->buildConfig());
    }

    protected function buildConfig(): Config
    {
        $environmentHandler = new EnvironmentHandler(...$this->envFilePaths);

        $config = new Config();

        foreach ($this->configBuilders as $builder) {
            $builder->build($environmentHandler, $config);
        }

        return $config;
    }

    protected function buildContainer(Config $config): Container
    {
        $containerBuilder = new ContainerBuilder();

        foreach ($this->definitionProviders as $provider) {
            $containerBuilder->addDefinitions($provider->getDefinitions());
        }

        return $containerBuilder->build();
    }
}
