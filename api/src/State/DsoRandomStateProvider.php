<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Command\ImportDataCommand;
use App\Repository\ElasticsearchRepository\ConstellationRepository;
use App\Repository\ElasticsearchRepository\DsoRepository;
use App\Services\Factory\DsoFactory;
use Generator;
use Psr\Cache\InvalidArgumentException;

readonly class DsoRandomStateProvider implements ProviderInterface
{
    public function __construct(
        private DsoRepository $dsoRepository,
        private ConstellationRepository $constellationRepository,
        private DsoFactory $dsoFactory
    ) { }

    /**
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return array
     * @throws InvalidArgumentException
     * @throws \JsonException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $filters = $context['request']->query->all();
        $offset = $filters['offset'] ?? 0;
        $limit = $filters['limit'] ?? 3;

        $documents = $this->dsoRepository->getRandomDso($offset, $limit);
        return array_map(function(array $document) {
            if ($document['const_id']) {
                $document['constellation'] = $this->constellationRepository->findById(ImportDataCommand::md5ForId($document['const_id']));
            }

            $dso = fn () => yield from $this->dsoFactory->buildDto($document);
            return $dso()->current();
        }, $documents);
    }
}
