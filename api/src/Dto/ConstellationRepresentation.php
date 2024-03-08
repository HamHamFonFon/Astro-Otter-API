<?php

namespace App\Dto;

use App\Model\Constellation;
use App\Services\StringSanitization;
use Symfony\Component\Serializer\Annotation\Groups;

class ConstellationRepresentation implements DTOInterface
{

    #[Groups(['search'])]
    private string $id;
    private string $elasticSearchId;
    private string $locale;
    private ?\DateTimeInterface $updatedAt = null;

    #[Groups(['search'])]
    private string $alt;

    #[Groups(['search'])]
    private ?string $urlName;
    private ?string $description = null;

    private array $geometry;
    private array $geometryLine;

    private string $kind;

    #[Groups(['search'])]
    private ?string $generic;

    #[Groups(['search'])]
    private ?string $cover;

    #[Groups(['search'])]
    private string $context = Constellation::class;

    public function __construct(
        Constellation $constellation,
        string $locale
    )
    {
        $fieldAlt = ('en' !== $locale) ?  sprintf('alt_%s', $locale): 'alt';
        $alt = $constellation->getAlt()[$fieldAlt] ?? null;

        $fieldDescription = ('en' !== $locale) ? sprintf('description_%s', $locale): 'description';
        $description = $constellation->getDescription()[$fieldDescription];

        $strSanitization = new StringSanitization;

        $this
            ->setLocale($locale)
            ->setId(strtolower($constellation->getId()))
            ->setElasticSearchId(md5($constellation->getId()))
            ->setUpdatedAt(null)
            ->setAlt($alt)
            ->setUrlName($strSanitization($alt))
            ->setDescription($description)
            ->setKind($constellation->getLoc())
            ->setGeneric($constellation->getGen())
            ->setCover(sprintf('%s.jpg', strtolower($constellation->getId())))
        ;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getElasticSearchId(): string
    {
        return $this->elasticSearchId;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setId(string $id): ConstellationRepresentation
    {
        $this->id = $id;
        return $this;
    }

    public function setElasticSearchId(string $elasticSearchId): ConstellationRepresentation
    {
        $this->elasticSearchId = $elasticSearchId;
        return $this;
    }

    public function setLocale(string $locale): ConstellationRepresentation
    {
        $this->locale = $locale;
        return $this;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): ConstellationRepresentation
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): ConstellationRepresentation
    {
        $this->alt = $alt;
        return $this;
    }

    public function getUrlName(): ?string
    {
        return $this->urlName;
    }

    public function setUrlName(?string $urlName): ConstellationRepresentation
    {
        $this->urlName = $urlName;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): ConstellationRepresentation
    {
        $this->description = $description;
        return $this;
    }

    public function getKind(): string
    {
        return $this->kind;
    }

    public function setKind(string $kind): ConstellationRepresentation
    {
        $this->kind = $kind;
        return $this;
    }

    public function getGeneric(): ?string
    {
        return $this->generic;
    }

    public function setGeneric(?string $generic): ConstellationRepresentation
    {
        $this->generic = $generic;
        return $this;
    }


    public function getGeometry(): array
    {
        return $this->geometry;
    }

    public function setGeometry(array $geometry): ConstellationRepresentation
    {
        $this->geometry = $geometry;
        return $this;
    }

    public function getGeometryLine(): array
    {
        return $this->geometryLine;
    }

    public function setGeometryLine(array $geometryLine): ConstellationRepresentation
    {
        $this->geometryLine = $geometryLine;
        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): ConstellationRepresentation
    {
        $this->cover = $cover;
        return $this;
    }
}
