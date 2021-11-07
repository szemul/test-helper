<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Database\Initializer;

interface InitializerInterface
{
    public function initialize(): void;
}
