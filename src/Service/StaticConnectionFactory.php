<?php
declare(strict_types=1);

namespace Facile\DoctrineTestModule\Service;

use DoctrineORMModule\Service\DBALConnectionFactory;
use Doctrine\DBAL\Connection;
use Facile\DoctrineTestModule\Doctrine\DBAL\StaticDriver;
use Interop\Container\ContainerInterface;

class StaticConnectionFactory extends DBALConnectionFactory
{
    /**
     * {@inheritDoc}
     *
     * @return Connection
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Connection $connectionOriginalDriver */
        $connectionOriginalDriver = parent::__invoke($container, $requestedName, $options);

        // wrapper class can be overridden/customized in params (see Doctrine\DBAL\DriverManager)
        $connectionWrapperClass = \get_class($connectionOriginalDriver);
        /** @var Connection $connection */
        $connection = new $connectionWrapperClass(
            $connectionOriginalDriver->getParams(),
            new StaticDriver($connectionOriginalDriver->getDriver(), $connectionOriginalDriver->getDatabasePlatform()),
            $connectionOriginalDriver->getConfiguration(),
            $connectionOriginalDriver->getEventManager()
        );

        if (StaticDriver::isKeepStaticConnections()) {
            // The underlying connection already has a transaction started.
            // Make sure we use savepoints to be able to easily roll-back nested transactions
            if ($connection->getDriver()->getDatabasePlatform()->supportsSavepoints()) {
                $connection->setNestTransactionsWithSavepoints(true);
            }
            // We start a transaction on the connection as well
            // so the internal state ($_transactionNestingLevel) is in sync with the underlying connection.
            $connection->beginTransaction();
        }

        return $connection;
    }
}