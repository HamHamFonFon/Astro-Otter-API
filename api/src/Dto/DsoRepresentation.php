<?php

namespace App\Dto;

use AstrobinWs\Response\DTO\Item\Image;
use AstrobinWs\Response\DTO\Item\User;

class DsoRepresentation
{
    private string $id;
    private string $elasticSearchId;
    private string $locale;
    private ?string $updatedAt = null;
    private string $name;
    private ?string $urlName;
    private string $fullNameAlt;
    private ?array $catalogs = null;
    private array|string $otherDesigs;
    private ?string $alt = null;
    private ?string $description = null;
    private string $type;
    private string $typeLabel;
    private mixed $magnitude;
    private ?string $discover = null;
    private ?int $discoverYear = null;
    private ?string $astrobinId = null;
    private ?Image $astrobin = null;
    private ?User  $astrobinUser = null;
    private ?array $geometry = null;
    private ?string $declinaison = null;
    private ?string $rightAscencion = null;
    private int|float|null $distanceLightYear = null;
    private int|float|null $distanceParsec = null;

    // Add constellation
}
