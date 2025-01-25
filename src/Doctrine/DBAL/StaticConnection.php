<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\Doctrine\DBAL;

use Doctrine\DBAL\Driver\Connection;

/**
 * Wraps a real connection and just skips the first call to beginTransaction as a transaction is already started on the underlying connection.
 */
class StaticConnection implements Connection
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var bool
     */
    private $transactionStarted = false;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function prepare($prepareString)
    {
        return $this->connection->prepare($prepareString);
    }

    /**
     * @inheritDoc
     */
    public function query()
    {
        return call_user_func_array([$this->connection, 'query'], func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function quote($input, $type = \PDO::PARAM_STR)
    {
        return $this->connection->quote($input, $type);
    }

    /**
     * @inheritDoc
     */
    public function exec($statement)
    {
        return $this->connection->exec($statement);
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId($name = null)
    {
        return $this->connection->lastInsertId($name);
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction()
    {
        if ($this->transactionStarted) {
            return $this->connection->beginTransaction();
        }

        return $this->transactionStarted = true;
    }

    /**
     * @inheritDoc
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * @inheritDoc
     */
    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    /**
     * @inheritDoc
     */
    public function errorCode()
    {
        return $this->connection->errorCode();
    }

    /**
     * @inheritDoc
     */
    public function errorInfo()
    {
        return $this->connection->errorInfo();
    }

    /**
     * @return Connection
     */
    public function getWrappedConnection()
    {
        return $this->connection;
    }
}
