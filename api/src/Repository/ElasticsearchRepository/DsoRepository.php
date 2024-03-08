<?php

namespace App\Repository\ElasticsearchRepository;

use App\Dto\DsoRepresentation;
use App\Enums\DsoSearchFields;
use App\Model\Dso;
use rdfHelpers\RdfNamespace;
use rdfInterface\BlankNodeInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class DsoRepository extends AbstractRepository
{
    public const INDEX = 'deepspaceobjects';

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
            array_map(fn (DsoSearchFields $case) => $case->value, DsoSearchFields::cases()),
            [sprintf('alt.alt_%s', $locale)]
        );
    }


    private static array $listAggregates = [
        'constellation' => [
            'field' => 'const_id.keyword',
            'size' => 100
        ],
        'catalog' => [
            'field' => 'catalog.keyword',
            'size' => 100
        ],
        'type' => [
            'field' => 'type.keyword',
            'size' => 100
        ]
    ];

    private static array $listAggregatesRange = [
        'magnitude' => [
            'field' => 'mag',
            'ranges' => [
                ['to' => 5, 'key' => 'low'],
                ['from' => 5, 'to' => 10, 'key' => 'average'],
                ['from' => 10, 'to' => 15, 'key' => 'high'],
                ['from' => 15, 'key' => 'hard']
            ]
        ]
    ];

    /**
     * Requests
     */

    // GetRandomDso
    public function getRandomDso(int $offset, int $limit): array
    {
        $seed = (new \DateTime())->getTimestamp();
        $param = [
            'index' => $this->getIndex(),
            'body' => [
                'query' => [
                    'function_score' => [
                        'query' => [
                            'exists' => ['field' => 'astrobin_id']
                        ],
                        'boost' => 5,
                        'random_score' => [
                            'seed' => $seed
                        ],
                        'boost_mode' => 'multiply'
                    ]
                ],
                'from' => $offset,
                'size' => $limit
            ]
        ];

        $results = $this->client->search($param);
        return array_map(
            fn(array $hit) => $hit['_source'],
            $results['hits']['hits']
        );
    }


    // GetDsoCatalogs
    public function getDsosFiltersBy(array $filters, int $offset, int $limit): array
    {
        $fieldsFilters = [
            'query' => [
                'bool' => [
                    'must' =>
                        array_map(function(string $field, string $value): array {
                            return [
                                'term' => [$field => $value]
                            ];
                        }, array_keys($filters), $filters)
                ]
            ]
        ];
        $sort = [
            'sort' => [
                ['order' => 'asc']
            ],
            'from' => $offset,
            'size' => $limit
        ];
        $aggregates = [
            'aggs' =>
                array_merge(
                    ...array_map(function ($key, $tab) {
                        return [
                            $key => [
                                "terms" => [
                                    "field" => $tab['field'],
                                    "size" => $tab['size']
                                ]
                            ]
                        ];
                    }, array_keys(self::$listAggregates), self::$listAggregates),
                    ...array_map(function ($key, $tab) {
                        return [
                            $key => [
                                "range" => [
                                    "field" => $tab['field'],
                                    "ranges" => $tab['ranges']
                                ]
                            ]
                        ];
                    }, array_keys(self::$listAggregatesRange), self::$listAggregatesRange)
                )
        ];


        $param = [
            'index' => $this->getIndex(),
            'body' => array_merge($fieldsFilters, $sort, $aggregates),
        ];


        $results = $this->client->search($param);
        ['total' => $total, 'hits' => $hits] = $results['hits'];
        ['aggregations' => $aggregations] = $results;

        foreach ($aggregations as $type => $aggs) {
            $aggregations[$type] = array_map(function(array $bucket) use($type) {
                return [
                    'name' => $bucket['key'],
                    'count' => $bucket['doc_count'],
                    'label' => sprintf('%s.%s', $type, strtolower($bucket['key']))
                ];
            }, $aggs['buckets']);
        }

        return [
            'total' => $total['value'],
            'documents' => array_map(function($document) {
                return $document['_source'];
            }, $hits),
            'aggregations' => $aggregations
        ];
    }

}
