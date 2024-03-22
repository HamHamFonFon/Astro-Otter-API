<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class Home extends AbstractController
{
    public function __invoke(Request $request, string $apiDocUrl): RedirectResponse
    {
        return $this->redirect($apiDocUrl);
    }
}
