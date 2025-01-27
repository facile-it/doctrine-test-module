<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;

/**
 * @internal
 */
class MockDriverV3 extends AbstractMockDriver implements Driver
{
    /**
     * @inheritDoc
     */
    public function connect(array $params): Driver\Connection
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
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform): AbstractSchemaManager
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

    public function getExceptionConverter(): ExceptionConverter
    {
        return $this->getMock(ExceptionConverter::class);
    }
}
