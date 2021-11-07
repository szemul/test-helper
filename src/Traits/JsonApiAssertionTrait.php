<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Traits;

use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

trait JsonApiAssertionTrait
{
    /** @param mixed[] $expectedBody */
    protected function assertEqualsJsonResponse(
        array $expectedBody,
        ResponseInterface $actual,
        int $expectedStatusCode = 200,
        string $message = '',
    ): void {
        Assert::assertSame($expectedStatusCode, $actual->getStatusCode(), $message);
        Assert::assertEquals($expectedBody, json_decode((string)$actual->getBody(), true), $message);
    }
}
