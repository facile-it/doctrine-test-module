<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\PHPUnit;

use Facile\DoctrineTestModule\Doctrine\DBAL\StaticDriver;
use PHPUnit\Event\Test\BeforeFirstTestMethodCalledSubscriber;
use PHPUnit\Event\Test\BeforeFirstTestMethodCalled;
use PHPUnit\Event\Test\BeforeTestMethodCalledSubscriber;
use PHPUnit\Event\Test\BeforeTestMethodCalled;
use PHPUnit\Event\Test\AfterTestMethodCalledSubscriber;
use PHPUnit\Event\Test\AfterTestMethodCalled;
use PHPUnit\Event\Test\AfterLastTestMethodCalledSubscriber;
use PHPUnit\Event\Test\AfterLastTestMethodCalled;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

class PHPUnitExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscriber(new class ($this) implements BeforeFirstTestMethodCalledSubscriber {
            public function notify(BeforeFirstTestMethodCalled $event): void
            {
                StaticDriver::setKeepStaticConnections(true);
            }
        });
        $facade->registerSubscriber(new class ($this) implements BeforeTestMethodCalledSubscriber {
            public function notify(BeforeTestMethodCalled $event): void
            {
                StaticDriver::beginTransaction();
            }
        });
        $facade->registerSubscriber(new class ($this) implements AfterTestMethodCalledSubscriber {
            public function notify(AfterTestMethodCalled $event): void
            {
                StaticDriver::rollBack();
            }
        });
        $facade->registerSubscriber(new class ($this) implements AfterLastTestMethodCalledSubscriber {
            public function notify(AfterLastTestMethodCalled $event): void
            {
                StaticDriver::setKeepStaticConnections(false);
            }
        });
    }
}
