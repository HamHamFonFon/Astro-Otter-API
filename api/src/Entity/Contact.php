<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private string $firstname;

    private string $lastname;

    private string $email;

    private string $country;

    private string $topic;

    private string $pot2Miel;

    private string $message;

    private string $labelCountry;
}
