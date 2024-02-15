<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Contact;
use App\Services\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

readonly class ContactStateProcessor implements ProcessorInterface
{
    public function __construct(
        private Email  $email,
        private string $to
    ) { }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof Contact) {
            try {
                $this->email->sendEmail(
                    $this->to,
                    $data->getEmail(),
                    $data->getTopic(),
                    [

                    ],
                    [
                        'html' => '',
                        'text' => ''
                    ]
                );
            } catch (TransportExceptionInterface $e) {
            }
        }

    }
}
