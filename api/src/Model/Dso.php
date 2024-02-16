<?php

namespace App\Model;

use ApiPlatform\Elasticsearch\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Dto\DsoRepresentation;

#[ApiResource(
    operations: [
        new Get(
            stateOptions: new Options(index: 'deepspaceobjects')
        ),
        new GetCollection(
            stateOptions: new Options(index: 'deepspaceobjects')
        )
    ]
)]
class Dso
{
    #[ApiProperty(identifier: true)]
    private string $id = '';

    private string|array|null $catalog = null;
    private int|string|null $order = null;
    private string $updatedAt;
    private array|string $desigs;
    private ?array $alt = null;
    private ?array $description = null;
    private ?string $type = null;
    private ?string $constId = null;
    private float|int|null $mag = null;
    private ?string $dim = null;
    private ?string $cl = null;
    private ?float $distAl = null;
    private ?string $discover = null;
    private ?float $discoverYear = null;
    private ?string $ra = null;
    private ?string $dec = null;
    private ?string $astrobinId = null;
    private ?array $geometry = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Dso
    {
        $this->id = $id;
        return $this;
    }

    public function getCatalog(): array|string|null
    {
        return $this->catalog;
    }

    public function setCatalog(array|string|null $catalog): Dso
    {
        $this->catalog = $catalog;
        return $this;
    }

    public function getOrder(): int|string|null
    {
        return $this->order;
    }

    public function setOrder(int|string|null $order): Dso
    {
        $this->order = $order;
        return $this;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): Dso
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getDesigs(): array|string
    {
        return $this->desigs;
    }

    public function setDesigs(array|string $desigs): Dso
    {
        $this->desigs = $desigs;
        return $this;
    }

    public function getAlt(): ?array
    {
        return $this->alt;
    }

    public function setAlt(?array $alt): Dso
    {
        $this->alt = $alt;
        return $this;
    }

    public function getDescription(): ?array
    {
        return $this->description;
    }

    public function setDescription(?array $description): Dso
    {
        $this->description = $description;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Dso
    {
        $this->type = $type;
        return $this;
    }

    public function getConstId(): ?string
    {
        return $this->constId;
    }

    public function setConstId(?string $constId): Dso
    {
        $this->constId = $constId;
        return $this;
    }

    public function getMag(): float|int|null
    {
        return $this->mag;
    }

    public function setMag(float|int|null $mag): Dso
    {
        $this->mag = $mag;
        return $this;
    }

    public function getDim(): ?string
    {
        return $this->dim;
    }

    public function setDim(?string $dim): Dso
    {
        $this->dim = $dim;
        return $this;
    }

    public function getCl(): ?string
    {
        return $this->cl;
    }

    public function setCl(?string $cl): Dso
    {
        $this->cl = $cl;
        return $this;
    }

    public function getDistAl(): ?float
    {
        return $this->distAl;
    }

    public function setDistAl(?float $distAl): Dso
    {
        $this->distAl = $distAl;
        return $this;
    }

    public function getDiscover(): ?string
    {
        return $this->discover;
    }

    public function setDiscover(?string $discover): Dso
    {
        $this->discover = $discover;
        return $this;
    }

    public function getDiscoverYear(): ?float
    {
        return $this->discoverYear;
    }

    public function setDiscoverYear(?float $discoverYear): Dso
    {
        $this->discoverYear = $discoverYear;
        return $this;
    }

    public function getRa(): ?string
    {
        return $this->ra;
    }

    public function setRa(?string $ra): Dso
    {
        $this->ra = $ra;
        return $this;
    }

    public function getDec(): ?string
    {
        return $this->dec;
    }

    public function setDec(?string $dec): Dso
    {
        $this->dec = $dec;
        return $this;
    }

    public function getAstrobinId(): ?string
    {
        return $this->astrobinId;
    }

    public function setAstrobinId(?string $astrobinId): Dso
    {
        $this->astrobinId = $astrobinId;
        return $this;
    }

    public function getGeometry(): ?array
    {
        return $this->geometry;
    }

    public function setGeometry(?array $geometry): Dso
    {
        $this->geometry = $geometry;
        return $this;
    }


}
