<?php

namespace App\Repository\ElasticsearchRepository;

use App\Enums\ConstellationSearchFields;
use Symfony\Component\HttpFoundation\Session\Session;

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

    /**
     * @return array
     */
    protected function getFields(): array
    {
        $locale = (new Session())->get('_locale') ?: 'en';
        return array_merge(
            array_map(fn (ConstellationSearchFields $case) => $case->value, ConstellationSearchFields::cases()),
            [sprintf('alt_%s', $locale), sprintf('alt_%s.keyword', $locale)]
        );
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
