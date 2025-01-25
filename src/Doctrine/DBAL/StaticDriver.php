<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\Doctrine\DBAL;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\DriverException;
use Doctrine\DBAL\Driver\ExceptionConverterDriver;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\VersionAwarePlatformDriver;

class StaticDriver implements Driver, ExceptionConverterDriver, VersionAwarePlatformDriver
{
    /**
     * @var Connection[]
     */
    private static $connections = [];

    /**
     * @var bool
     */
    private static $keepStaticConnections = false;

    /**
     * @var Driver
     */
    private $underlyingDriver;

    /**
     * @var AbstractPlatform
     */
    private $platform;

    public function __construct(Driver $underlyingDriver, AbstractPlatform $platform)
    {
        $this->underlyingDriver = $underlyingDriver;
        $this->platform = $platform;
    }

    /**
     * @inheritDoc
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        if (self::$keepStaticConnections) {
            $key = sha1(serialize($params) . $username . $password);

            if (! isset(self::$connections[$key])) {
                self::$connections[$key] = $this->underlyingDriver->connect(
                    $params,
                    $username,
                    $password,
                    $driverOptions
                );
                self::$connections[$key]->beginTransaction();
            }

            return new StaticConnection(self::$connections[$key]);
        }

        return $this->underlyingDriver->connect($params, $username, $password, $driverOptions);
    }

    /**
     * @inheritDoc
     */
    public function getDatabasePlatform()
    {
        return $this->platform;
    }

    /**
     * @inheritDoc
     */
    public function getSchemaManager(\Doctrine\DBAL\Connection $conn)
    {
        return $this->underlyingDriver->getSchemaManager($conn);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->underlyingDriver->getName();
    }

    /**
     * @inheritDoc
     */
    public function getDatabase(\Doctrine\DBAL\Connection $conn)
    {
        return $this->underlyingDriver->getDatabase($conn);
    }

    /**
     * @inheritDoc
     */
    public function convertException($message, DriverException $exception)
    {
        if ($this->underlyingDriver instanceof ExceptionConverterDriver) {
            return $this->underlyingDriver->convertException($message, $exception);
        }

        return new Exception\DriverException($message, $exception);
    }

    /**
     * @inheritDoc
     */
    public function createDatabasePlatformForVersion($version)
    {
        return $this->platform;
    }

    /**
     * @param bool $keepStaticConnections
     */
    public static function setKeepStaticConnections($keepStaticConnections)
    {
        self::$keepStaticConnections = $keepStaticConnections;
    }

    /**
     * @return bool
     */
    public static function isKeepStaticConnections()
    {
        return self::$keepStaticConnections;
    }

    public static function beginTransaction()
    {
        foreach (self::$connections as $con) {
            try {
                $con->beginTransaction();
            } catch (\PDOException $e) {
                // transaction could be started already
            }
        }
    }

    public static function rollBack()
    {
        foreach (self::$connections as $con) {
            $con->rollBack();
        }
    }

    public static function commit()
    {
        foreach (self::$connections as $con) {
            $con->commit();
        }
    }
}
