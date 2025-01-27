<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\Service;

use Doctrine\DBAL\Events;
use DoctrineModule\Service\AbstractFactory;
use DoctrineORMModule\Service\DBALConnectionFactory;
use Doctrine\DBAL\Connection;
use Facile\DoctrineTestModule\ConfigProvider;
use Facile\DoctrineTestModule\Doctrine\DBAL\PostConnectEventListener;
use Facile\DoctrineTestModule\Doctrine\DBAL\StaticDriver;
use Psr\Container\ContainerInterface;
use DoctrineORMModule\Options\DBALConnection;

use function assert;

final class StaticConnectionFactory extends AbstractFactory
{
    /**
     * @inheritDoc
     *
     * @return Connection
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        /** @var Connection $originalConnection */
        $originalConnection = (new DBALConnectionFactory($this->name))($container, $requestedName, $options);

        /** @var array{doctrine_test_module?: array{enable_static_connection?: bool | array<string, bool>}} $config */
        $config = $container->get('config')[ConfigProvider::CONFIGURATION];

        if (! StaticDriver::isKeepStaticConnections()) {
            return $originalConnection;
        }

        $enableConfig = $config['enable_static_connection'] ?? false;

        if ($enableConfig === false) {
            return $originalConnection;
        }

        if (is_array($enableConfig) && (! isset($enableConfig[$this->name]) || $enableConfig[$this->name] !== true)) {
            return $originalConnection;
        }

        $options = $this->getOptions($container, 'connection');
        assert($options instanceof DBALConnection);

        // wrapper class can be overridden/customized in params (see Doctrine\DBAL\DriverManager)
        $connectionWrapperClass = \get_class($originalConnection);
        $connection = new $connectionWrapperClass(
            $originalConnection->getParams(),
            new StaticDriver($originalConnection->getDriver(), $originalConnection->getDatabasePlatform()),
            $originalConnection->getConfiguration(),
            $originalConnection->getEventManager()
        );

        assert($connection instanceof Connection);

        $connection->getEventManager()->addEventListener(Events::postConnect, new PostConnectEventListener());

        if ($connection->getDriver()->getDatabasePlatform()->supportsSavepoints()) {
            $connection->setNestTransactionsWithSavepoints(true);
        }

        return $connection;
    }

    public function getOptionsClass(): string
    {
        return DBALConnection::class;
    }
}
