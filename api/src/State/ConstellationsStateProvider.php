<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ElasticsearchRepository\ConstellationRepository;
use App\Services\Factory\ConstellationFactory;

readonly class ConstellationsStateProvider implements ProviderInterface
{

    public function __construct(
        private ConstellationRepository $constellationRepository,
        private ConstellationFactory    $constellationFactory
    ) { }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): \Generator//: object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $allDocsConstellations = $this->constellationRepository->getAllConstellations();
            $allConstellations = function() use ($allDocsConstellations) {
                foreach ($allDocsConstellations as $document) {
                    $constellationRepresentation = fn() => yield from $this->constellationFactory->buildDto($document);
                    yield $constellationRepresentation()->current();
                }

//                yield from $this->constellationFactory->buildListDto($allDocsConstellations);
            };

            foreach ($allConstellations() as $constellation) {
                yield $constellation;
            }
        } else {
            ['id' => $idConst] = $uriVariables;
            $constellationDoc = $this->constellationRepository->findById(md5($idConst));
            yield $this->constellationFactory->buildDto($constellationDoc)->current();
        }
    }
}
