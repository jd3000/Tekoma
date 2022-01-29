<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use App\Form\RegistrationType;
use App\Form\RegistrationFormType;
use App\Repository\ProductRepository;
use App\Controller\RegistrationController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
    public function show(Product $product): Response
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
