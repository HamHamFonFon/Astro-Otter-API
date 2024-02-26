<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ElasticsearchRepository\DsoRepository;
use App\Services\Factory\DsoFactory;
use Generator;
use Psr\Cache\InvalidArgumentException;

readonly class DsoRandomStateProvider implements ProviderInterface
{
    public function __construct(
        private DsoRepository $dsoRepository,
        private DsoFactory $dsoFactory
    ) { }

    /**
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return Generator
     * @throws InvalidArgumentException
     * @throws \JsonException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Generator
    {
        $filters = $context['request']->query->all();
        $offset = $filters['offset'] ?? 0;
        $limit = $filters['limit'] ?? 3;

        $documents = $this->dsoRepository->getRandomDso($offset, $limit);

        yield from $this->dsoFactory->buildListDto($documents);
    }
}
