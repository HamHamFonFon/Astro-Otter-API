<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new Post(
            status: 301
        )
    ]
)]
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
