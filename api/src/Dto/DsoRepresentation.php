<?php

namespace App\Dto;

use AllowDynamicProperties;
use App\Model\Dso;
use App\Services\StringSanitization;
use App\Services\Translator;
use AstrobinWs\Response\DTO\Item\Image;
use AstrobinWs\Response\DTO\Item\User;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;

class DsoRepresentation implements DTOInterface
{
    public const PARSEC = 0.3066020852;

    #[Groups(['search'])]
    private string $id;
    private string $elasticSearchId;
    private string $locale;
    private ?\DateTimeInterface $updatedAt = null;
    private string $name;

    #[Groups(['search'])]
    private ?string $urlName;

    #[Groups(['search'])]
    private string $fullNameAlt;
    private ?array $catalogs = null;

    #[Groups(['search'])]
    private array|string $desigs;
    #[Groups(['search'])]
    private array|string $otherDesigs;

    private ?string $alt = null;
    private ?string $description = null;
    private string $type;

    #[Groups(['search'])]
    private string $typeLabel;
    private mixed $magnitude;
    private ?string $discover = null;
    private ?int $discoverYear = null;
    private ?array $geometry = null;
    private ?string $declinaison = null;
    private ?string $rightAscencion = null;
    private int|float|null $distanceLightYear = null;
    private int|float|null $distanceParsec = null;

    // Add constellation
    private ?string $constId = null;
    #[Groups(['search'])]
    private ?DTOInterface $constellation;
    private ?string $astrobinId = null;
    private ?Image $astrobin = null;
    private ?User $astrobinUser = null;

    public function __construct(
        Dso $dso,
        string $locale
    )
    {
        $name = (is_array($dso->getDesigs())) ? current($dso->getDesigs()): $dso->getDesigs();

        $fieldAlt = ('en' !== $locale) ? sprintf('alt_%s', $locale) : 'alt';
        $alt = $dso->getAlt()[$fieldAlt] ?? null;

        $fieldDescription = ('en' !== $locale) ? sprintf('description_%s', $locale): 'description';
        $description = $dso->getDescription()[$fieldDescription] ?? null;

        $strSanitization = new StringSanitization;
        $othersDsoDesigs = array_filter($dso->getDesigs(), static fn(string $desig) => $desig !== $name);

        $catalogs = (!is_array($dso->getCatalog())) ? [$dso->getCatalog()] : $dso->getCatalog();

        $this
            ->setLocale($locale)
            ->setId(strtolower($dso->getId()))
            ->setElasticSearchId(md5($dso->getId()))
            ->setName($name)
            ->setAlt($alt)
            ->setUrlName($strSanitization($alt))
            ->setFullNameAlt(
                (empty($this->getAlt()))
                    ? $this->getName() ?? $this->getId()
                    : implode(' - ', [$this->getName(), $this->getAlt()])
            )
            ->setDesigs($dso->getDesigs())->setOtherDesigs($othersDsoDesigs)
            ->setCatalogs($catalogs)
            ->setType($dso->getType())
            ->setMagnitude($dso->getMag())->setDistanceLightYear($dso->getDistAl())->setDistanceParsec(self::PARSEC*$dso->getDistAl())
            ->setDeclinaison($dso->getDec())->setRightAscencion($dso->getRa())
            ->setDiscover($dso->getDiscover())->setDiscoverYear($dso->getDiscoverYear())
            ->setDescription($description)
            ->setGeometry($dso->getGeometry())
            ->setAstrobinId($dso->getAstrobinId())
            ->setConstId($dso->getConstId())
        ;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    private function setLocale(string $locale): static
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getElasticSearchId(): string
    {
        return $this->elasticSearchId;
    }

    private function setElasticSearchId(string $elasticSearchId): DsoRepresentation
    {
        $this->elasticSearchId = $elasticSearchId;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    private function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    private function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): DsoRepresentation
    {
        $this->name = $name;
        return $this;
    }

    public function getUrlName(): ?string
    {
        return $this->urlName;
    }

    public function setUrlName(?string $urlName): DsoRepresentation
    {
        $this->urlName = $urlName;
        return $this;
    }

    public function getFullNameAlt(): string
    {
        return $this->fullNameAlt;
    }

    public function setFullNameAlt(string $fullNameAlt): DsoRepresentation
    {
        $this->fullNameAlt = $fullNameAlt;
        return $this;
    }

    public function getDesigs(): array|string
    {
        return $this->desigs;
    }

    public function setDesigs(array|string $desigs): DsoRepresentation
    {
        $this->desigs = $desigs;
        return $this;
    }

    public function getOtherDesigs(): array|string
    {
        return $this->otherDesigs;
    }

    public function setOtherDesigs(array|string $otherDesigs): DsoRepresentation
    {
        $this->otherDesigs = $otherDesigs;
        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): DsoRepresentation
    {
        $this->alt = $alt;
        return $this;
    }

    public function getCatalogs(): ?array
    {
        return $this->catalogs;
    }

    public function setCatalogs(?array $catalogs): DsoRepresentation
    {
        $this->catalogs = $catalogs;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    private function setType(string $type): DsoRepresentation
    {
        $this->type = $type;
        return $this;
    }

    public function getTypeLabel(): string
    {
        return $this->typeLabel;
    }

    public function setTypeLabel(string $typeLabel): DsoRepresentation
    {
        $this->typeLabel = $typeLabel;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    private function setDescription(?string $description): DsoRepresentation
    {
        $this->description = $description;
        return $this;
    }

    public function getMagnitude(): mixed
    {
        return $this->magnitude;
    }

    private function setMagnitude(mixed $magnitude): DsoRepresentation
    {
        $this->magnitude = $magnitude;
        return $this;
    }

    public function getDiscover(): ?string
    {
        return $this->discover;
    }

    private function setDiscover(?string $discover): DsoRepresentation
    {
        $this->discover = $discover;
        return $this;
    }

    public function getDiscoverYear(): ?int
    {
        return $this->discoverYear;
    }

    private function setDiscoverYear(?int $discoverYear): DsoRepresentation
    {
        $this->discoverYear = $discoverYear;
        return $this;
    }

    public function getGeometry(): ?array
    {
        return $this->geometry;
    }

    private function setGeometry(?array $geometry): DsoRepresentation
    {
        $this->geometry = $geometry;
        return $this;
    }

    public function getDeclinaison(): ?string
    {
        return $this->declinaison;
    }

    private function setDeclinaison(?string $declinaison): DsoRepresentation
    {
        $this->declinaison = $declinaison;
        return $this;
    }

    public function getRightAscencion(): ?string
    {
        return $this->rightAscencion;
    }

    private function setRightAscencion(?string $rightAscencion): DsoRepresentation
    {
        $this->rightAscencion = $rightAscencion;
        return $this;
    }

    public function getDistanceLightYear(): float|int|null
    {
        return $this->distanceLightYear;
    }

    private function setDistanceLightYear(float|int|null $distanceLightYear): DsoRepresentation
    {
        $this->distanceLightYear = $distanceLightYear;
        return $this;
    }

    public function getDistanceParsec(): float|int|null
    {
        return $this->distanceParsec;
    }

    private function setDistanceParsec(float|int|null $distanceParsec): DsoRepresentation
    {
        $this->distanceParsec = $distanceParsec;
        return $this;
    }

    public function getAstrobinId(): ?string
    {
        return $this->astrobinId;
    }

    public function setAstrobinId(?string $astrobinId): DsoRepresentation
    {
        $this->astrobinId = $astrobinId;
        return $this;
    }

    public function getAstrobin(): ?Image
    {
        return $this->astrobin;
    }

    public function setAstrobin(?Image $astrobin): DsoRepresentation
    {
        $this->astrobin = $astrobin;
        return $this;
    }

    public function getAstrobinUser(): ?User
    {
        return $this->astrobinUser;
    }

    public function setAstrobinUser(?User $astrobinUser): DsoRepresentation
    {
        $this->astrobinUser = $astrobinUser;
        return $this;
    }

    public function getConstId(): ?string
    {
        return $this->constId;
    }

    public function setConstId(?string $constId): DsoRepresentation
    {
        $this->constId = $constId;
        return $this;
    }

    public function getConstellation(): ?DTOInterface
    {
        return $this->constellation;
    }

    public function setConstellation(?DTOInterface $constellation): self
    {
        $this->constellation = $constellation;
        return $this;
    }
}
