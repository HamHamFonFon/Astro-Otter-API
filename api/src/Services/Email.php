<?php

namespace App\Services;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final readonly class Email
{

    public function __construct(
        private MailerInterface $mailer,
        private Environment $template
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(
        string $to,
        string $from,
        string $subject,
        array $data,
        array $templates
    ): void
    {
        try {
            $email = (new \Symfony\Component\Mime\Email())
                ->from($from)
                ->to($to)
                ->subject($subject)
                ->html($this->template->render($templates['html'], $data))
                ->text($this->template->render($templates['text'], $data))
            ;

            $this->mailer->send($email);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
        }
    }
}
