<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Traits;

use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;
use Szemul\SlimErrorHandlerBridge\Exception\HttpUnprocessableEntityException;
use Szemul\TestHelper\Helper\ArrayCleanupHelper;

trait JsonApiAssertionTrait
{
    /** @param mixed[] $expectedBody */
    protected function assertEqualsJsonResponse(
        array $expectedBody,
        ResponseInterface $actual,
        int $expectedStatusCode = 200,
        string $message = '',
        array $ignoreBodyKeys = [],
    ): void {
        Assert::assertSame($expectedStatusCode, $actual->getStatusCode(), $message);
        Assert::assertEquals(
            $expectedBody,
            $this->getArrayCleanupHelper()->removeKeysFromArray(
                json_decode((string)$actual->getBody(), true),
                $ignoreBodyKeys,
            ),
            $message,
        );
    }

    protected function assertUnprocessableExceptionParamErrors(
        array $expected,
        HttpUnprocessableEntityException $e,
        string $message = '',
    ): void {
        $this->assertEquals($expected, json_decode(json_encode($e->getParameterErrors()), true), $message);
    }

    protected function getArrayCleanupHelper(): ArrayCleanupHelper
    {
        return new ArrayCleanupHelper();
    }
}
