<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findByExampleField(4);
        // (“dump and die”) helper function
        // dd($products);
        dump($products);
        return $this->render('home/index.html.twig', [
            'products' => $products
        ]);
    }
}
