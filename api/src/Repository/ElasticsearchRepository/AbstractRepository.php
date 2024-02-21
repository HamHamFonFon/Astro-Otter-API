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
        private Notification $notification
    )
    {
        $this->client = Elasticsearch::getInstance($this->esHost);
    }

    /**
     * Build ES document into DTO
     */
    abstract protected function getIndex(): string;

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

    protected function findBySearchTerms(string $searchTerm)
    {}

    protected function addNewDocument()
    {
        $this->notification->sendNotification([]);
    }

    protected function bulkImport(array $listDocuments) { }

}
