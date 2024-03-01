<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use App\State\ContactStateProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[Post(security: "is_granted('ROLE_API_USER')", processor: ContactStateProcessor::class,)]
class Contact
{
    #[Assert\NotBlank(message: 'contact.constraint.not_blank')]
    private string $firstname;

    #[Assert\NotBlank(message: 'contact.constraint.not_blank')]
    private string $lastname;

    #[Assert\Email(message: 'contact.constraint.email')]
    #[Assert\NotBlank(message: 'contact.constraint.not_blank')]
    private string $email;

    #[Assert\Country(message: 'contact.constraint.country')]
    private string $country;

    #[Assert\NotBlank(message: 'contact.constraint.not_blank')]
    private string $topic;

    #[Assert\NotBlank()]
    private string $message;

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): Contact
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): Contact
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): Contact
    {
        $this->country = $country;
        return $this;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): Contact
    {
        $this->topic = $topic;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Contact
    {
        $this->message = $message;
        return $this;
    }
}
