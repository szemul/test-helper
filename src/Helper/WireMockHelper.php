<?php

declare(strict_types=1);

namespace Szemul\TestHelper\Helper;

use Fig\Http\Message\RequestMethodInterface;
use InvalidArgumentException;
use WireMock\Client\MappingBuilder;
use WireMock\Client\WireMock;

class WireMockHelper
{
    public function __construct(
        private string $host,
        private int $port = 8080,
    ) {
    }

    public function getWireMock(): WireMock
    {
        return WireMock::create($this->host, $this->port);
    }

    public function mockApiCall(
        string $method,
        string $uri,
        int $expectedResponseStatus,
        ?string $requestBody = null,
        ?string $responseBody = null,
    ): void {
        $wireMock = $this->getWireMock();
        $apiMock  = $this->getApiMock($method, $uri);

        if (!empty($requestBody)) {
            $apiMock->withRequestBody(WireMock::equalTo($requestBody));
        }

        $responseDefinitionBuilder = WireMock::aResponse()->withStatus($expectedResponseStatus);

        if (!empty($responseBody)) {
            $responseDefinitionBuilder
                ->withBody($responseBody);
        }

        $apiMock->willReturn($responseDefinitionBuilder);
        $wireMock->stubFor($apiMock);
    }

    protected function getApiMock(string $method, string $uri): MappingBuilder
    {
        $urlRegex = preg_quote($uri) . '.*';

        switch ($method) {
            case RequestMethodInterface::METHOD_GET:
                return WireMock::get(WireMock::urlMatching($urlRegex));

            case RequestMethodInterface::METHOD_POST:
                return WireMock::post(WireMock::urlMatching($urlRegex));

            case RequestMethodInterface::METHOD_PUT:
                return WireMock::put(WireMock::urlMatching($urlRegex));

            case RequestMethodInterface::METHOD_DELETE:
                return WireMock::delete(WireMock::urlMatching($urlRegex));

            default:
                throw new InvalidArgumentException('Invalid method given: ' . $method);
        }
    }
}
