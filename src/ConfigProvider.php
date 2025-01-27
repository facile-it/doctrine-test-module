<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule;

final class ConfigProvider
{
    public const CONFIGURATION = 'doctrine_test_module';

    /**
     * Configuration for mezzio.
     *
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return \array_merge(
            $this->getConfig(),
            [
                'dependencies' => $this->getDependencies(),
            ]
        );
    }

    /**
     * Configuration for laminas-mvc.
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return [
            self::CONFIGURATION => [
                'enable_static_connection' => true,
            ],
            'doctrine_factories' => [
                'connection' => Service\StaticConnectionFactory::class,
            ],
        ];
    }

    /**
     * Laminas servicemanager dependencies.
     *
     * @return array<string, mixed>
     */
    public function getDependencies(): array
    {
        return [];
    }
}
