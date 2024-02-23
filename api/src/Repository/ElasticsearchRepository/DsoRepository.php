<?php

namespace App\Repository\ElasticsearchRepository;

use App\Dto\DsoRepresentation;
use App\Model\Dso;
use rdfHelpers\RdfNamespace;
use rdfInterface\BlankNodeInterface;

final class DsoRepository extends AbstractRepository
{
    public const INDEX = 'deepspaceobjects';

    protected function getIndex(): string
    {
        return self::INDEX;
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

    // GetDsoCatalogs
    public function getDsosFiltersBy(array $filters, int $offset, int $limit): array
    {
        $fieldsFilters = [
            'query' => [
                'bool' => [
                    'must' =>
                        array_map(function( $field, $value) {
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

        return [
            'total' => $total['value'],
            'documents' => array_map(function($document) {
                return $document['_source'];
            }, $hits),
            'aggregations' => $aggregations
        ];
    }

}
