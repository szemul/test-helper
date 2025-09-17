<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Traits;

use Psr\Http\Message\ServerRequestInterface;

trait AuthenticatedRequestTrait //@phpstan-ignore-line
{
    /** @param array<string,string> $query */
    protected function getAuthenticatedRequest(
        string $method,
        string $path,
        array $query = [],
        string $body = '',
    ): ServerRequestInterface {
        return $this->authenticateRequest($this->getRequest($method, $path, $query, $body));
    }

    abstract protected function getRequest(
        string $method,
        string $path,
        array $query = [],
        string $body = '',
    ): ServerRequestInterface;

    abstract protected function authenticateRequest(ServerRequestInterface $request): ServerRequestInterface;
}
