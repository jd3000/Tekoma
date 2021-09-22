<?php

namespace App\Controller;

use App\Entity\Product;
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
        $products = $productRepo->findActiveProducts(true);
        // (“dump and die”) helper function
        // dd($products);
        return $this->render('creations/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * Permet d'afficher une seule creation
     * 
     * @Route("/creations/{slug}", name="creations_show")
     */
    public function show($slug, Product $product): Response
    {

        // si on utilise le ProductRepository à la place du paramconverter
        // $product = $productRepo->findOneBySlug($slug);

        // (“dump and die”) helper function
        // dump($product);
        return $this->render('creations/show.html.twig', [
            'product' => $product
        ]);
    }
}
