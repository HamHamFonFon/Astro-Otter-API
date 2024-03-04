<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Search;


readonly class SearchByAiProcessor implements ProcessorInterface
{
    public function __construct(
        private string $apiOpenAI
    )
    { }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof Search) {
            throw new \Error();
        }
    }
}
