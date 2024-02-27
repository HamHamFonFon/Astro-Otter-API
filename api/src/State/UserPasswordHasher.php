<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\ApiUser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserPasswordHasher implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface          $processor,
        private UserPasswordHasherInterface $passwordHasher
    ) { }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ApiUser|null
    {
        if (!$data->getPlainPassword()) {
            return $this->processor->process($data, $operation, $uriVariables, $context);
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $data,
            $data->getPlainPassword()
        );
        $data->setPassword($hashedPassword);
        $data->setRoles(['ROLE_API_USER']);
        $data->eraseCredentials();

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
