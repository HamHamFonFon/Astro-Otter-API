<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\DoctrineRepository\ApiUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\State\UserPasswordHasher;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\UuidV7 as Uuid;

#[ORM\Entity(repositoryClass: ApiUserRepository::class)]
#[UniqueEntity('`email`')]
#[ApiResource(
    operations: [
        new GetCollection(
            description: "List of users",
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Post(
            description: "Create new user",
            security: "is_granted('ROLE_ADMIN')",
            validationContext: ['groups' => ['user:create']],
            processor: UserPasswordHasher::class
        ),
        new Patch(
            description: 'Enable or disable user',
            denormalizationContext: ['groups' => ['user:patch']],
            security: "is_granted('ROLE_ADMIN')",
            validationContext: ['groups' => ['user:patch']]
        ),
        new Delete(
            description: "Delete an user",
            security: "is_granted('ROLE_ADMIN')"
        )
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:create']]
)]
class ApiUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(identifier: true)]
    private ?Uuid $id = null;

    #[Assert\Email(message: 'api_users.email.email', groups: ['user:create'])]
    #[Assert\NotBlank(message: 'api_users.email.not_blank', groups: ['user:create'])]
    #[Groups(['user:read', 'user:create'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank(message: 'api_users.plain_password.not_blank', groups: ['user:create'])]
    #[Assert\NotCompromisedPassword(message: 'api_users.plain_password.not_compromised', groups: ['user:create'])]
    #[Groups(['user:create'])]
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Groups(['user:patch', 'user:read'])]
    private ?bool $isActive = false;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_API_USER
        $roles[] = 'ROLE_API_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): ApiUser
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): ApiUser
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
