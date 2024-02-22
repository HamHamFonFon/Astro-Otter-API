<?php

namespace App\Repository\ElasticsearchRepository;

use App\Repository\ElasticsearchRepository\AbstractRepository;

class ConstellationRepository extends AbstractRepository
{
    public const INDEX = 'constellations';
    /**
     * @inheritDoc
     */
    protected function getIndex(): string
    {
        return self::INDEX;
    }

    public function getAllConstellations(): callable|array
    {
        $json = '{
            "sort": [
                {"order": "asc"}
            ],
            "query": {
                "match_all": {}
            }
        }';
        $param = [
            'index' => $this->getIndex(),
            'size' => 100,
            'body' => $json
        ];


        $results = $this->client->search($param);
        return array_map(
            fn(array $hit) => $hit['_source'],
            $results['hits']['hits']
        );
    }
}
