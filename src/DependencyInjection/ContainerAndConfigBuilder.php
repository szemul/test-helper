<?php
declare(strict_types=1);

namespace Szemul\TestHelper\DependencyInjection;

use DI\Container;
use DI\ContainerBuilder;
use Szemul\Config\Builder\ConfigBuilderInterface;
use Szemul\Config\Config;
use Szemul\Config\ConfigInterface;
use Szemul\Config\Environment\EnvironmentHandler;
use Szemul\DependencyInjection\Provider\DefinitionProviderInterface;

class ContainerAndConfigBuilder
{
    protected bool             $isConfigBuilt = false;
    protected ?ConfigInterface $config        = null;
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

    /** @return null|array<string,mixed> */
    public function __debugInfo(): ?array
    {
        return [
            'config'              => null === $this->config ? null : '*** Instance of ' . get_class($this->config),
            'configBuilders'      => $this->configBuilders,
            'definitionProviders' => $this->definitionProviders,
            'envFilePaths'        => $this->envFilePaths,
        ];
    }

    public function setConfig(ConfigInterface $config): static
    {
        if ($this->isConfigBuilt) {
            throw new \RuntimeException('Trying to set the config instance after the config was already built');
        }

        $this->config = $config;

        return $this;
    }

    public function getConfig(): ?ConfigInterface
    {
        return $this->config;
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

    public function buildConfig(): static
    {
        $environmentHandler = new EnvironmentHandler(...$this->envFilePaths);

        if (null === $this->config) {
            $this->config = new Config();
        }

        foreach ($this->configBuilders as $builder) {
            $builder->build($environmentHandler, $this->config);
        }

        return $this;
    }

    public function buildContainer(): Container
    {
        $containerBuilder = new ContainerBuilder();

        foreach ($this->definitionProviders as $provider) {
            $containerBuilder->addDefinitions($provider->getDefinitions());
        }

        return $containerBuilder->build();
    }
}
