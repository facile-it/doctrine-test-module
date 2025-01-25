<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\PHPUnit;

use Facile\DoctrineTestModule\Doctrine\DBAL\StaticDriver;

class PHPUnitListener implements \PHPUnit\Framework\TestListener
{
    use \PHPUnit\Framework\TestListenerDefaultImplementation;

    public function startTest(\PHPUnit\Framework\Test $test): void
    {
        StaticDriver::beginTransaction();
    }

    public function endTest(\PHPUnit\Framework\Test $test, float $time): void
    {
        StaticDriver::rollBack();
    }

    public function startTestSuite(\PHPUnit\Framework\TestSuite $suite): void
    {
        StaticDriver::setKeepStaticConnections(true);
    }
}
