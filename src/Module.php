<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule;

class Module
{
    /**
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        $provider = new ConfigProvider();

        $config = $provider->getConfig();
        $config['service_manager'] = $provider->getDependencies();

        return $config;
    }
}
