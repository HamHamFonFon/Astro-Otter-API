<?php

namespace App\Services\Cache;

use Symfony\Contracts\Cache\CacheInterface as AbstractAdapter;

readonly class Redis implements CacheInterface
{

    public function __construct(
        private AbstractAdapter $cachePoolAdaper
    ) { }

    public function getItem(string $key): ?string
    {
        $cacheItem = $this->cachePoolAdaper->getItem($key);
        return $cacheItem->isHit() ? $cacheItem->get() : null;
    }

    public function saveItem(string $key, $value): bool
    {
        $cacheItem = $this->cachePoolAdaper->getItem($key);
        $cacheItem->set($value);
        return $this->cachePoolAdaper->save($cacheItem);
    }

    public function hasItem(string $key): bool
    {
        return $this->cachePoolAdaper->hasItem($key);
    }

    public function deleteItem(string $key): bool
    {
        return $this->cachePoolAdaper->deleteItem($key);
    }

    public function deleteAll(): bool
    {
        return $this->cachePoolAdaper->clear();
    }
}
