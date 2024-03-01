<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Search;
use Gemini\Client;

readonly class SearchByAiProcessor implements ProcessorInterface
{
    public function __construct(
        private string $apiGeminiKey
    )
    { }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof Search) {
            throw new \Error();
        }
        $content = $data->getTerms();
        $client = \Gemini::client($this->apiGeminiKey);

        $result = $client->geminiPro()->generateContent($content);
    }
}
