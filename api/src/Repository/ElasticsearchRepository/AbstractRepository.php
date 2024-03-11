<?php

namespace App\Repository\ElasticsearchRepository;

use App\Services\Elasticsearch;
use App\Services\Notification;
use Elasticsearch\Client;

abstract class AbstractRepository
{
    protected Client $client;

    public function __construct
    (
        private readonly string $esHost,
        private readonly Notification $notification
    )
    {
        $this->client = Elasticsearch::getInstance($this->esHost);
    }

    /**
     * Build ES document into DTO
     */
    abstract protected function getIndex(): string;
    abstract protected function getFields(): array;

    /**
     * Main requests
     */
    public function findById(string $documentId): callable|array
    {
        $param = [
            'index' => $this->getIndex(),
            'id' => $documentId
        ];
        return $this->client->get($param)['_source'];
    }


    protected function findBy(string $fieldName, string $value): callable|array
    {
        $param = [
            'index' => $this->getIndex(),
            'body' => [
                'query' => [
                    'match' => [
                        $fieldName => $value
                    ]
                ]
            ]
        ];

        return $this->client->search($param);
    }

    public function findBySearchTerms(string $searchTerm): array
    {
        $param = [
            'index' => $this->getIndex(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $searchTerm,
                        'fields' => $this->getFields(),
                        'type' => 'phrase_prefix'
                    ]
                ]
            ],
            'size' => 10
        ];

        $results = $this->client->search($param);
        return array_map(fn ($hit) => $hit['_source'],$results['hits']['hits']);
    }

    protected function addNewDocument()
    {
        $this->notification->sendNotification([]);
    }

    protected function bulkImport(array $listDocuments) { }

}
