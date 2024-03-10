<?php

namespace App\Services;

use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;

trait SymfonyInjector
{
    protected ?TranslatorInterface $translator = null;

    #[Required]
    public function injectTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $this->translator ?: $translator;
    }
}
