<?php

declare(strict_types=1);

namespace Facile\DoctrineTestModule\Doctrine\Cache;

use Doctrine\Common\Cache\CacheProvider;

class StaticArrayCache extends CacheProvider
{
    /**
     * @var array<mixed>
     */
    private static $data = [];

    /**
     * @inheritDoc
     */
    protected function doFetch($id)
    {
        return $this->doContains($id) ? self::$data[$id] : false;
    }

    /**
     * @inheritDoc
     */
    protected function doContains($id)
    {
        // isset() is required for performance optimizations, to avoid unnecessary function calls to array_key_exists.
        return isset(self::$data[$id]) || array_key_exists($id, self::$data);
    }

    /**
     * @inheritDoc
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        self::$data[$id] = $data;

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function doDelete($id)
    {
        unset(self::$data[$id]);

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function doFlush()
    {
        self::$data = [];

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function doGetStats()
    {
        return null;
    }
}
