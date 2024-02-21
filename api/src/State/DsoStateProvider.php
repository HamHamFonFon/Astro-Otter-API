<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\DsoRepresentation;
use App\Dto\DTOInterface;
use App\Repository\ElasticsearchRepository\DsoRepository;
use App\Services\Factory\DsoFactory;

readonly class DsoStateProvider implements ProviderInterface
{

    public function __construct(
        private DsoRepository $dsoRepository,
        private DsoFactory $dsoFactory
    ) { }

    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): DTOInterface
    {
        ['id' => $dsoId] = $uriVariables;
        // Retrieve the state from somewhere
        $document = $this->dsoRepository->findById(md5($dsoId));
        $dsoRepresentation = function () use($document) {
            yield from $this->dsoFactory->buildDto($document);
        };

        return $dsoRepresentation()->current();

    }
}
