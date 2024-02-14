<?php

namespace App\Services;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class Notification
{
    private static string $topic = 'notifications/all';
    private HubInterface $hub;

    public function __construct(HubInterface $hub) { }

    public function sendNotification(array $message): string
    {
        $update = new Update(
            sprintf('%s/%s', 'https://api.astro-otter.space', self::$topic),
            json_encode($message)
        );

        return $this->hub->publish($update);
    }
}
