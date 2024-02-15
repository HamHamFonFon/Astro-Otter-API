<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'update_data')]
#[UniqueEntity(fields: 'id')]
class UpdateData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\DateTime(message: 'update_data.constraint.datetime')]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: 'json')]
    #[Assert\NotBlank()]
    #[Assert\NotNull]
    private string $listDso;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): UpdateData
    {
        $this->id = $id;
        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): UpdateData
    {
        $this->date = $date;
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
