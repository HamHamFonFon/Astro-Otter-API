<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ElasticsearchRepository\ConstellationRepository;
use App\Repository\ElasticsearchRepository\DsoRepository;
use App\Services\Factory\DsoFactory;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\InputBag;

readonly class DsoStateProvider implements ProviderInterface
{

    public function __construct(
        private DsoRepository $dsoRepository,
        private ConstellationRepository $constellationRepository,
        private DsoFactory $dsoFactory
    ) { }

    /**
     * @throws \JsonException
     * @throws InvalidArgumentException
     */
    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): array|object|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $filters = $context['request']->query->all();
            $offset = $filters['offset'] ?? 0;
            $limit = $filters['limit'] ?? 21;
            unset($filters['offset'], $filters['limit']);
            $authorizedQueryParams = array_map(fn($param) => $param['name'], $context['operation']->getOpenapiContext()['parameters']);

            $filters = array_filter($filters, function(string $paramKey) use ($authorizedQueryParams) {
                return in_array($paramKey, $authorizedQueryParams);
            }, ARRAY_FILTER_USE_KEY);

            [
                'total' => $total,
                'documents' => $documents,
                'aggregations' => $aggregations
            ] = $this->dsoRepository->getDsosFiltersBy($filters, $offset, $limit);

            array_walk($documents, function(&$doc) {
                if ($doc['const_id']) {
                    $doc['constellation'] = $this->constellationRepository->findById(md5($doc['const_id']));
                }
            });

            $listDso = function () use ($documents) {
                foreach ($documents as $document) {
                    $dsoRepresentation = fn() => yield from $this->dsoFactory->buildDto($document);
                    yield $dsoRepresentation()->current();
                }
            };

            return [
                'data' => $listDso(), // only for retro-compatibility with vue3 version
                'items' => $listDso(),
                'filters' => $aggregations,
                'total' => $total
            ];
        } else {
            ['id' => $dsoId] = $uriVariables;
            // Retrieve the state from somewhere
            $document = $this->dsoRepository->findById(md5($dsoId));
            if ($document['const_id']) {
                $document['constellation'] = $this->constellationRepository->findById(md5($document['const_id']));
            }

            $dsoRepresentation = fn() => yield from $this->dsoFactory->buildDto($document);
            return $dsoRepresentation()->current();
        }
    }
}
