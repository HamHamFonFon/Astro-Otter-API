<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use App\State\SearchByAiProcessor;
use App\State\SearchProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[Post(
    uriTemplate: '/search',
    security: "is_granted('ROLE_API_USER')",
    processor: SearchProcessor::class
)]
#[Post(
    uriTemplate: '/search-by-ai',
    security: "is_granted('ROLE_AI_USER')",
    processor: SearchByAiProcessor::class
)]
final class Search
{
    #[Assert\NotBlank(message: '')]
    #[Assert\NoSuspiciousCharacters()]
    #[Assert\NotNull(message: '')]
    #[Assert\Regex(
        pattern: '/[a-zA-Z0-9-_.%\s]+/i'
    )]
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
