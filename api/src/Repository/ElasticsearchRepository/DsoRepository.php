<?php

namespace App\Repository\ElasticsearchRepository;

use App\Dto\DsoRepresentation;
use App\Model\Dso;

final class DsoRepository extends AbstractRepository
{
    public const INDEX = 'deepspaceobjects';
    protected function getIndex(): string
    {
        return self::INDEX;
    }

    protected function getEsModel(): string
    {
        return Dso::class;
    }

    protected function getDto(): string
    {
        return DsoRepresentation::class;
    }

    /**
     * Requests
     */

    // GetRandomDso

    // GetDsoCatalogs


}
