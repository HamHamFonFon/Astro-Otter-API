<?php

namespace App\Services\Cache;

use Psr\Cache\InvalidArgumentException;
use \Psr\Cache\CacheItemPoolInterface;

final readonly class Redis implements CacheInterface
{

    public function __construct(
        private CacheItemPoolInterface $redisCachePool
    ) { }

    /**
     * @throws InvalidArgumentException
     */
    public function getItem(string $key): ?string
    {
        $cacheItem = $this->redisCachePool->getItem($key);
        return $cacheItem->isHit() ? $cacheItem->get() : null;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function saveItem(string $key, $value): bool
    {
        $cacheItem = $this->redisCachePool->getItem($key);
        $cacheItem->set($value);
        return $this->redisCachePool->save($cacheItem);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function hasItem(string $key): bool
    {
        return $this->redisCachePool->hasItem($key);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function deleteItem(string $key): bool
    {
        return $this->redisCachePool->deleteItem($key);
    }

    public function deleteAll(): bool
    {
        return $this->redisCachePool->clear();
    }

    public function test(): void
    {
        dump($this->redisCachePool);
    }
}
