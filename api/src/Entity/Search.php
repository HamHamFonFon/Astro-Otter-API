<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use App\State\SearchProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[Post(
    uriTemplate: '/search',
    security: "is_granted('ROLE_API_USER')",
    processor: SearchProcessor::class

)]
class Search
{
    #[Assert\NotBlank()]
    #[Assert\NoSuspiciousCharacters()]
    #[Assert\NotNull()]
    private string $terms;

    public function getTerms(): string
    {
        return $this->terms;
    }

    public function setTerms(string $terms): Search
    {
        $this->terms = $terms;
        return $this;
    }
}
