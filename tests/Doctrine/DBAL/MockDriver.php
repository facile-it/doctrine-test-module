<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\Doctrine\DBAL;

if (interface_exists(\Doctrine\DBAL\Driver\ExceptionConverterDriver::class)) {
    // dbal v2
    class MockDriver extends MockDriverV2 {}
} else {
    // dbal v3
    class MockDriver extends MockDriverV3 {}
}
