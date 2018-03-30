<?php
declare(strict_types=1);

namespace Facile\DoctrineTestModule;

class ConfigProvider
{
    /**
     * Configuration for zend-expressive
     *
     * @return array
     */
    public function __invoke()
    {
        return \array_merge(
            $this->getConfig(),
            [
                'dependencies' => $this->getDependencies()
            ]
        );
    }

    /**
     * Configuration for zend-mvc
     *
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'doctrine_factories' => [
                'connection' => Service\StaticConnectionFactory::class,
            ],
        ];
    }

    /**
     * Zend servicemanager dependencies
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [];
    }
}
