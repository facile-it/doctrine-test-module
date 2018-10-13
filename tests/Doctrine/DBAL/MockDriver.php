<?php
declare(strict_types=1);

namespace Facile\DoctrineTestModule\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use PHPUnit\Framework\MockObject\Generator;

class MockDriver implements Driver
{
    /**
     * @param string $class
     *
     * @return object
     */
    private function getMock($class)
    {
        if (class_exists(Generator::class)) {
            // PHPUnit 6.5+
            $generator = new Generator();
        } else {
            $generator = new Generator();
        }
        return $generator->getMock(
            $class,
            [],
            [],
            '',
            false
        );
    }

    /**
     * @param array $params
     * @param null $username
     * @param null $password
     * @param array $driverOptions
     * @return Driver\Connection|object
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        return $this->getMock(Driver\Connection::class);
    }

    /**
     * @return AbstractPlatform|object
     */
    public function getDatabasePlatform()
    {
        return $this->getMock(AbstractPlatform::class);
    }

    /**
     * @param Connection $conn
     * @return AbstractSchemaManager|object
     */
    public function getSchemaManager(Connection $conn)
    {
        return $this->getMock(AbstractSchemaManager::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mock';
    }

    /**
     * {@inheritdoc}
     */
    public function getDatabase(Connection $conn)
    {
        return 'mock';
    }
}
