<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreationsController extends AbstractController
{
    /**
     * @Route("/creations", name="creations")
     */
    public function index(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findByExampleField(4);
        // (“dump and die”) helper function
        // dd($products);
        dump($products);
        return $this->render('creations/index.html.twig', [
            'products' => $products
        ]);
    }
}
