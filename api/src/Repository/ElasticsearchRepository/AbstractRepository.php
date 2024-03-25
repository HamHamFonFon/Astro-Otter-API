<?php

namespace App\Repository\ElasticsearchRepository;

use App\Services\Elasticsearch;
use App\Services\Notification;
use Elasticsearch\Client;
use App\Command\ImportDeltaDataCommand;

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

    public function insertDocument(array $document): void
    {
        $param = [
            'index' => $this->getIndex(),
            'id' => $document['idDoc'],
            'body' => [
                'doc' => $document['data']
            ]
        ];

        switch($document['mode']) {
            case ImportDeltaDataCommand::MODE_CREATE_DOCUMENT: $this->client->create($param);
                break;
            case ImportDeltaDataCommand::MODE_UPDATE_DOCUMENT: $this->client->update($param);
                break;
        };

        $message = match($document['mode']) {
            ImportDeltaDataCommand::MODE_CREATE_DOCUMENT => sprintf('New object have been added: "%s"', $document['data']['id']),
            ImportDeltaDataCommand::MODE_UPDATE_DOCUMENT => sprintf('"%s" have been updated',$document['data']['id'])
        };

        $notification = [
            'message' => $message,
            'date' => (new \DateTime())->format('Y-m-d H:i:s'),
            'type' => 'success'
        ];
        $this->notification->sendNotification($notification);
    }


    /**
     * Bulk is not possible with elastica/elastica 7.x, wait to upgrade to 8.x
     * @param array $listDocuments
     * @return void
     */
    protected function bulkData(array $listDocuments): void
    {
        $param = [
          'index' => $this->getIndex(),
        ];
    }

}
