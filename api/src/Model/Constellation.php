<?php

namespace App\Model;

use ApiPlatform\Elasticsearch\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Dto\ConstellationRepresentation;
use App\Repository\ElasticsearchRepository\ConstellationRepository;
use App\State\ConstellationsStateProvider;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/constellation/item/{id}',
            security: "is_granted('ROLE_API_USER')",
            output: ConstellationRepresentation::class,
            provider: ConstellationsStateProvider::class,
            stateOptions: new Options(index: ConstellationRepository::INDEX),
        ),
        new GetCollection(
            uriTemplate: '/constellation/list',
            security: "is_granted('ROLE_API_USER')",
            provider: ConstellationsStateProvider::class,
            stateOptions: new Options(index: ConstellationRepository::INDEX),
        )
    ]
)]
class Constellation
{
    #[ApiProperty(identifier: true)]
    private string $id;

    private string $gen;

    private array $alt;

    private array $description;

    private int $rank;

    private int $order;

    private string $loc;

    private array $geometry;

    private array $geometryLine;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Constellation
    {
        $this->id = $id;
        return $this;
    }

    public function getGen(): string
    {
        return $this->gen;
    }

    public function setGen(string $gen): Constellation
    {
        $this->gen = $gen;
        return $this;
    }

    public function getAlt(): array
    {
        return $this->alt;
    }

    public function setAlt(array $alt): Constellation
    {
        $this->alt = $alt;
        return $this;
    }

    public function getDescription(): array
    {
        return $this->description;
    }

    public function setDescription(array $description): Constellation
    {
        $this->description = $description;
        return $this;
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    public function setRank(int $rank): Constellation
    {
        $this->rank = $rank;
        return $this;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): Constellation
    {
        $this->order = $order;
        return $this;
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function setLoc(string $loc): Constellation
    {
        $this->loc = $loc;
        return $this;
    }

    public function getGeometry(): array
    {
        return $this->geometry;
    }

    public function setGeometry(array $geometry): Constellation
    {
        $this->geometry = $geometry;
        return $this;
    }

    public function getGeometryLine(): array
    {
        return $this->geometryLine;
    }

    public function setGeometryLine(array $geometryLine): Constellation
    {
        $this->geometryLine = $geometryLine;
        return $this;
    }
}
