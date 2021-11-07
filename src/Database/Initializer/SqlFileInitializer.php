<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Database\Initializer;

use Szemul\Database\Config\MysqlConfig;

class SqlFileInitializer implements InitializerInterface
{
    public function __construct(protected string $sqlFilePath, protected MysqlConfig $config)
    {
    }

    public function initialize(): void
    {
        $command = implode(' ', [
            'mysql',
            '-h',
            escapeshellarg($this->config->getAccess()->getHost()),
            '-P',
            $this->config->getAccess()->getPort(),
            '-u',
            $this->config->getAccess()->getUsername(),
            '-p' . escapeshellarg($this->config->getAccess()->getPassword()),
            '-e',
            escapeshellarg('DROP DATABASE IF EXISTS ' . $this->config->getDatabase() . '; CREATE DATABASE ' . $this->config->getDatabase()),
        ]);

        exec($command);
        $command = implode(' ', [
            'mysql',
            '-h',
            escapeshellarg($this->config->getAccess()->getHost()),
            '-P',
            $this->config->getAccess()->getPort(),
            '-u',
            escapeshellarg($this->config->getAccess()->getUsername()),
            '-p' . escapeshellarg($this->config->getAccess()->getPassword()),
            escapeshellarg($this->config->getDatabase()),
            '<',
            escapeshellarg($this->sqlFilePath),
        ]);

        exec($command);
    }
}
