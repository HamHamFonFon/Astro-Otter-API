<?php

namespace App\Command;

use App\State\CatalogsMapping;

trait ImportData
{
    private function openFile(string $file): ?array
    {
        return json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
    }

    public static function md5ForId(string $id): string
    {
        return md5(strtolower($id));
    }


    public static function getCatalog(?string $id): string
    {
        $catalogMapping = new CatalogsMapping;
        if (!is_null($id)) {
            return $catalogMapping()[substr($id, 0, 2)] ?? CatalogsMapping::UNASSIGNED;
        }
        return CatalogsMapping::UNASSIGNED;
    }

    public static function getItemOrder(string $id): ?int
    {
        if (preg_match('/NGC(\w+)/', $id, $match)) {
            return (int)$match[1];
        }

        return null;
    }
}
