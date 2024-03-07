<?php

namespace App\Services;

use DateTimeInterface;

final class DateSanitization
{
    public const FORMAT_DATE_ES = DateTimeInterface::RFC3339;

    public function __invoke(?string $date): \DateTime
    {
        if (is_null($date)) return new \DateTime('now');
        $date = \DateTime::createFromFormat(self::FORMAT_DATE_ES, $date);
        return (false !== $date) ? $date : (new \DateTime('now'));
    }
}
