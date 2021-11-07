<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Traits;

use DI\Container;
use Psr\Log\LoggerInterface;
use WMDE\PsrLogTestDoubles\LoggerSpy;

trait LogHandlerTrait
{
    protected LoggerSpy $logger;

    protected function setupLogger(): void
    {
        $this->logger    = new LoggerSpy();

        $this->getContainer()->set(LoggerInterface::class, $this->logger);
    }

    protected function assertNoLoggingCallsWereMade(): void
    {
        $this->logger->assertNoLoggingCallsWhereMade();
    }

    protected function assertSingleLogMessage(string $logLevel, string $message): void
    {
        $this->assertCount(1, $this->logger->getLogCalls());
        $firstLogMessage = $this->logger->getFirstLogCall();
        $this->assertSame($logLevel, $firstLogMessage->getLevel());
        $this->assertSame($message, $firstLogMessage->getMessage());
    }

    abstract protected function getContainer(): Container;
}
