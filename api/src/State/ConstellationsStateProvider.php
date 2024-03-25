<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Command\ImportDataCommand;
use App\Repository\ElasticsearchRepository\ConstellationRepository;
use App\Services\Factory\ConstellationFactory;
use Psr\Cache\InvalidArgumentException;

readonly class ConstellationsStateProvider implements ProviderInterface
{

    public function __construct(
        private ConstellationRepository $constellationRepository,
        private ConstellationFactory    $constellationFactory
    ) { }

    /**
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return object|array|null
     * @throws InvalidArgumentException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
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


            return iterator_to_array($allConstellations());
//            foreach ($allConstellations() as $constellation) {
//                yield $constellation;
//            }
        } else {
            ['id' => $idConst] = $uriVariables;
            $constellationDoc = $this->constellationRepository->findById(ImportDataCommand::md5ForId($idConst));
            $constellationRepresentation = fn() => yield from $this->constellationFactory->buildDto($constellationDoc);
            return $constellationRepresentation()->current();

        }
    }
}
