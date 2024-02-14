<?php

namespace App\Entity;

#[ORM\Entity]
class Contact
{
    private string $firstname;

    private string $lastname;

    private string $email;

    private string $country;

    private string $topic;

    private string $pot2Miel;

    private string $message;

    private string $labelCountry;
}
