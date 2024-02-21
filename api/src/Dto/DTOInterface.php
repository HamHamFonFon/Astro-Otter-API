<?php

namespace App\Dto;

interface DTOInterface
{
    public function getLocale(): string;

    public function getElasticSearchId(): string;

    public function getId(): string;

    public function getUpdatedAt(): ?\DateTimeInterface;
}
