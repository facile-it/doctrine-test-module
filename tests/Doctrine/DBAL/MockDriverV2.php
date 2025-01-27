<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;

/**
 * @internal
 */
class MockDriverV2 extends AbstractMockDriver implements Driver
{
    /**
     * @inheritDoc
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = []): Driver\Connection
    {
        return $this->getMock(Driver\Connection::class);
    }

    /**
     * @inheritDoc
     */
    public function getDatabasePlatform(): AbstractPlatform
    {
        return $this->getMock(AbstractPlatform::class);
    }

    /**
     * @inheritDoc
     */
    public function getSchemaManager(Connection $conn): AbstractSchemaManager
    {
        return $this->getMock(AbstractSchemaManager::class);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'mock';
    }

    /**
     * @inheritDoc
     */
    public function getDatabase(Connection $conn): string
    {
        return 'mock';
    }
}
