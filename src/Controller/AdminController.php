<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**    
     * @Route("/admin", name="admin")     
     */
    public function index(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findAll();
        // (“dump and die”) helper function
        // dd($products);
        return $this->render('admin/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * Permet d'afficher une seule creation
     * 
     * @Route("/admin/{slug}", name="admin_show")
     */
    public function show($slug, ProductRepository $productRepo): Response
    {
        $product = $productRepo->findOneBySlug($slug);
        // (“dump and die”) helper function
        dump($product);
        return $this->render('admin/show.html.twig', [
            'product' => $product
        ]);
    }
}
