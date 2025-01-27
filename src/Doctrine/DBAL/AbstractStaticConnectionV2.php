<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\Doctrine\DBAL;

use Doctrine\DBAL\Driver\Statement;

/**
 * @internal
 */
abstract class AbstractStaticConnectionV2 extends AbstractStaticConnection
{
    public function prepare($sql): Statement
    {
        return $this->connection->prepare($sql);
    }

    public function query(): Statement
    {
        return call_user_func_array([$this->connection, 'query'], func_get_args());
    }

    public function exec($sql): int
    {
        return $this->connection->exec($sql);
    }

    public function errorCode(): ?string
    {
        return $this->connection->errorCode();
    }

    public function errorInfo(): array
    {
        return $this->connection->errorInfo();
    }
}
