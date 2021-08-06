<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecificationsController extends AbstractController
{
    /**
     * @Route("/specifications", name="specifications")
     */
    public function index(): Response
    {
        return $this->render('specifications/index.html.twig', [
            'controller_name' => 'SpecificationsController',
        ]);
    }
}
