<?php

namespace App\Services\Cache;

interface CacheInterface
{
    public function getItem(string $key): ?string;

    public function saveItem(string $key, $value): bool;

    public function hasItem(string $key): bool;

    public function deleteItem(string $key): bool;

    public function deleteAll(): bool;
}
