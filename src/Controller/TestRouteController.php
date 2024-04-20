<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestRouteController extends AbstractController
{
    #[Route('/testRoute')]
    public function testRoute(): Response
    {
        return new Response("Bonjour c'est une page de TEST DU CONTROLLER ROUTE!!!");
    }
}