<?php

namespace Szemul\TestHelper\TestCase;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class UnitTestCaseAbstract extends TestCase
{
    use MockeryPHPUnitIntegration;
}
