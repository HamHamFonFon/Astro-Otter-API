<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    private const HEADER_LANGUAGE = 'Accept-Language';

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->headers->has(self::HEADER_LANGUAGE)) {
            $locale = $request->headers->get(self::HEADER_LANGUAGE);
            (new Session())->set('_locale', $locale);
            $request->setLocale($locale);

        } else {
            $request->setLocale('en');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 17]
            ]
        ];
    }
}
