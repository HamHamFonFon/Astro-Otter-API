<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ElasticsearchRepository\DsoRepository;
use App\Services\Factory\DsoFactory;
use Symfony\Component\HttpFoundation\InputBag;

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
    ): \Generator
    {
        if ($operation instanceof CollectionOperationInterface) {
            $filters = $context['request']->query->all();
            $offset = $filters['offset'] ?? 0;
            $limit = $filters['limit'] ?? 21;

            unset($filters['offset'], $filters['limit']);

            [
                'total' => $total,
                'documents' => $documents,
                'aggregations' => $aggregations
            ] = $this->dsoRepository->getDsosFiltersBy($filters, $offset, $limit);

            $listDso = function () use ($documents) {
                yield from $this->dsoFactory->buildListDto($documents);
            };

            foreach ($listDso() as $dso) {
                dump($dso);
            }
            die();
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

            yield $dsoRepresentation()->current();
        }
    }
}
