<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\UuidV7 as Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'update_data')]
#[UniqueEntity(fields: 'id')]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['data:read']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['data:create']]
        )
    ]
)]
class UpdateData
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(identifier: true)]
    private ?Uuid $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\DateTime(message: 'update_data.constraint.datetime')]
    #[Groups(['data:read'])]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: 'json')]
    #[Groups(['data:create', 'data:read'])]
    #[Assert\NotBlank(groups: ['data:create'])]
    #[Assert\NotNull(groups: ['data:create'])]
    private string $listDso;

    public function __construct()
    {
        $this->id = Uuid::v7();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): UpdateData
    {
        $this->id = $id;
        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    #[ORM\PrePersist]
    public function setDateValue(): UpdateData
    {
        $this->date = new \DateTimeImmutable();
        return $this;
    }

    public function getListDso(): string
    {
        return $this->listDso;
    }

    public function setListDso(string $listDso): UpdateData
    {
        $this->listDso = $listDso;
        return $this;
    }

}
