<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Product;
use Stripe\Checkout\Session;
use App\Form\RegistrationType;
use App\Service\StripeService;
use App\Form\RegistrationFormType;
use App\Repository\ProductRepository;
use App\Controller\RegistrationController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
     * @Route("/user/payment/{slug}", name="user_creations_payment")
     */
    public function userSelection($slug, Product $product): Response
    {

        // si on utilise le ProductRepository à la place du paramconverter
        // $product = $productRepo->findOneBySlug($slug);

        // (“dump and die”) helper function
        // dump($product);
        return $this->render('user/payment.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * Permet d'afficher une seule creation sélectionnée par un utilisateur
     * 
     * @Route("/user/checkout/{slug}", name="user_checkout")
     */
    public function checkout($slug, Product $product, $stripeSK): Response
    {

        $productName = $product->getName();
        $productPrice = $product->getPrice();

        Stripe::setApiKey($stripeSK);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'shipping_address_collection' => [
                'allowed_countries' => ['FR'],
            ],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'eur',
                        'product_data' => [
                            'name' => $productName,
                        ],
                        'unit_amount'  => $productPrice * 100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', array('slug' => $slug), UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', array('slug' => $slug), UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        // dd($session);
        return $this->redirect($session->url, 303);
    }

    /**
     * Permet 
     * 
     * @Route("/user/success-url/{slug}", name="success_url")
     */
    public function successUrl($slug, Product $product): Response
    {
        $productName = $product->getName();

        $this->addFlash('success',  $productName . " commandé ! Merci 🙂. Suivez l'évolution de votre commande ici");
        return $this->redirectToRoute('user');
    }

    /**
     * Permet 
     * 
     * @Route("/user/cancel-url/{slug}", name="cancel_url")
     */
    public function cancelUrl($slug): Response
    {
        return $this->redirectToRoute('user_creations_payment', array('slug' => $slug));
    }
}
