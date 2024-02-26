<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ElasticsearchRepository\DsoRepository;
use App\Services\Factory\DsoFactory;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\InputBag;

readonly class DsoStateProvider implements ProviderInterface
{

    public function __construct(
        private DsoRepository $dsoRepository,
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

            $listDso = function () use ($documents) {
                try {
                    yield from $this->dsoFactory->buildListDto($documents);
                } catch (\JsonException $e) {
                }
            };


            return [
                'total' => $total,
                'items' => iterator_to_array($listDso()),
                'aggregates' => $aggregations
            ];
        } else {
            ['id' => $dsoId] = $uriVariables;
            // Retrieve the state from somewhere
            $document = $this->dsoRepository->findById(md5($dsoId));
            /**
             * @throws \JsonException
             */
            $dsoRepresentation = (function () use($document) {
                yield from $this->dsoFactory->buildDto($document);
            });

            return $dsoRepresentation()->current();
        }
    }
}
