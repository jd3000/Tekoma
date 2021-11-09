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

class UserController extends AbstractController
{
    /**
     * Permet d'afficher une seule creation
     * 
     * @Route("/user", name="user")
     */
    public function user()
    {
        // si on utilise le ProductRepository à la place du paramconverter
        // $product = $productRepo->findOneBySlug($slug);

        // (“dump and die”) helper function
        // dump($product);
        return $this->render('user/index.html.twig');
    }


    /**
     * Permet d'afficher une seule creation sélectionnée par un utilisateur
     * 
     * @Route("/user/creations/{slug}", name="user_creations_show")
     */
    public function userSelection($slug, Product $product): Response
    {

        // si on utilise le ProductRepository à la place du paramconverter
        // $product = $productRepo->findOneBySlug($slug);

        // (“dump and die”) helper function
        // dump($product);
        return $this->render('user/show.html.twig', [
            'product' => $product
        ]);
    }
}
