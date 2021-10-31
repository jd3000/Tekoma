<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
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

    /**
     * Permet d'afficher le login lors de l'achat d'une création
     * 
     * @Route("/creations/login/{slug}", name="creations_login")
     */
    public function login($slug, Product $product, AuthenticationUtils $authenticationUtils): Response
    {
        // si on utilise le ProductRepository à la place du paramconverter
        // $product = $productRepo->findOneBySlug($slug);
        if ($this->getUser()) {
            return $this->redirectToRoute('target_path');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        // (“dump and die”) helper function
        // dump($product);
        return $this->render('creations/login.html.twig', [
            'product' => $product,
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * Permet d'afficher le login lors de l'achat d'une création
     * 
     * @Route("/creations/sign-up/{slug}", name="creations_sign-up")
     */
    public function signUp($slug, Product $product, AuthenticationUtils $authenticationUtils): Response
    {
        // si on utilise le ProductRepository à la place du paramconverter
        // $product = $productRepo->findOneBySlug($slug);
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        // (“dump and die”) helper function
        // dump($product);
        return $this->render('creations/sign-up.html.twig', [
            'product' => $product,
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
