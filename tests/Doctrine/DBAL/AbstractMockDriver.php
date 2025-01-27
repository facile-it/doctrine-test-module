<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\Doctrine\DBAL;

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;

abstract class AbstractMockDriver
{
    protected TestCase $testCase;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    protected function getMock(string $class)
    {
        $mockBuilder = new MockBuilder($this->testCase, $class);

        return $mockBuilder
            ->disableOriginalConstructor()
            ->getMock();
    }
}
