<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\DsoRepresentation;

class DsoRepresentationProvider implements ProviderInterface
{
    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): DsoRepresentation
    {
        dump($operation, $uriVariables, $context);
        // Get DsoRepresentaiton from cache

        // If not in cache
        $dsoRepresentation = new DsoRepresentation();
        return $dsoRepresentation;
    }
}
