<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\Doctrine\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;

class StaticDriverTest extends TestCase
{
    public function testReturnCorrectPlatform(): void
    {
        $platform = $this->createMock(AbstractPlatform::class);
        $driver = new StaticDriver(new MockDriver($this), $platform);
        $this->assertSame($platform, $driver->getDatabasePlatform());
        $this->assertSame($platform, $driver->createDatabasePlatformForVersion('1'));
    }

    public function testConnect(): void
    {
        $platform = $this->createMock(AbstractPlatform::class);
        $driver = new StaticDriver(new MockDriver($this), $platform);
        $driver::setKeepStaticConnections(true);
        /** @var StaticConnection $connection1 */
        $connection1 = $driver->connect(['database_name' => 1], 'user1', 'pw1');
        /** @var StaticConnection $connection2 */
        $connection2 = $driver->connect(['database_name' => 2], 'user1', 'pw2');
        $this->assertInstanceOf(StaticConnection::class, $connection1);
        $this->assertNotSame($connection1->getWrappedConnection(), $connection2->getWrappedConnection());
        $driver = new StaticDriver(new MockDriver($this), $platform);
        /** @var StaticConnection $connectionNew1 */
        $connectionNew1 = $driver->connect(['database_name' => 1], 'user1', 'pw1');
        /** @var StaticConnection $connectionNew2 */
        $connectionNew2 = $driver->connect(['database_name' => 2], 'user1', 'pw2');
        $this->assertSame($connection1->getWrappedConnection(), $connectionNew1->getWrappedConnection());
        $this->assertSame($connection2->getWrappedConnection(), $connectionNew2->getWrappedConnection());
    }
}
