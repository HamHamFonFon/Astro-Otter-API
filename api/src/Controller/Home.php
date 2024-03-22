<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class Home extends AbstractController
{
    #[Route(path: '/', name: 'home')]
    public function __invoke(Request $request, string $apiDocUrl): RedirectResponse
    {
        return $this->redirect($apiDocUrl);
    }
}
