<?php
declare(strict_types=1);

namespace Facile\DoctrineTestModule;

class ConfigProvider
{
    /**
     * Configuration for mezzio
     *
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return \array_merge(
            $this->getConfig(),
            [
                'dependencies' => $this->getDependencies()
            ]
        );
    }

    /**
     * Configuration for laminas-mvc
     *
     * @return array<string, mixed>
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
     * Laminas servicemanager dependencies
     *
     * @return array<string, mixed>
     */
    public function getDependencies(): array
    {
        return [];
    }
}
