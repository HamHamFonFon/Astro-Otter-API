<?php

namespace App\Model;

use ApiPlatform\Elasticsearch\State\ItemProvider;
use ApiPlatform\Elasticsearch\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Dto\DsoRepresentation;
use App\State\DsoRepresentationProvider;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/dso/{id}',
            stateOptions: new Options(index: 'deepspaceobjects')
        )
    ]
)]
class Dso
{
    #[ApiProperty(identifier: true)]
    private string $id = '';

    private string|array|null $catalog = null;
    private int $order;
    private string $updatedAt;
    private array|string $desigs;
    private ?array $alt = null;
    private ?array $description = null;
    private ?string $type = null;
    private ?string $constId = null;
    private ?float $mag = null;
    private ?string $dim = null;
    private ?string $cl = null;
    private ?float $distAl = null;
    private ?string $discover = null;
    private ?float $discoverYear = null;
    private ?string $ra = null;
    private ?string $dec = null;
    private ?string $astrobinId = null;
    private ?array $geometry = null;
}
