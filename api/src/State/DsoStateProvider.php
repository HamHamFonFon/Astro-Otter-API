<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\DsoRepresentation;
use App\Services\Cache\Redis;

readonly class DsoStateProvider implements ProviderInterface
{
    public function __construct(
        private Redis $redisAdapter
    )
    { }

    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): DsoRepresentation
    {
        ['id' => $dsoId] = $uriVariables;
        // Retrieve the state from somewhere
        dump($operation, $dsoId, $context);
        dump($this->redisAdapter->getItem('m42'));
        die();

        $dsoRepresentation = new DsoRepresentation();
        return $dsoRepresentation;
    }
}
