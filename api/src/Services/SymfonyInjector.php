<?php

namespace App\Services;

use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;

trait SymfonyInjector
{
    protected TranslatorInterface $translator;

    #[Required]
    public function injectTranslator(TranslatorInterface $translator) {}
}
