<?php

namespace App\Services\Factory;

use App\Dto\DTOInterface;

interface FactoryInterface
{
    public function buildDto(array $document);
}
