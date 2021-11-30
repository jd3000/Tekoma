<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\OrderStripe;
use Doctrine\ORM\Mapping\Id;
use Stripe\Checkout\Session;
use App\Form\UpdateOrderType;
use App\Form\RegistrationType;
use App\Service\StripeService;
use App\Form\RegistrationFormType;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\OrderStripeRepository;
use App\Controller\RegistrationController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * Permet d'afficher le compte
     * 
     * @Route("/user", name="user")
     */
    public function user(OrderStripeRepository $orderStripeRepo)
    {
        $user = $this->getUser();
        $userName = $user->getUsername();
        $stripeOrders = $orderStripeRepo->findByUserName($userName);
        dump($stripeOrders);
        $userId = $user->getId();
        dump($userId);

        return $this->render('user/index.html.twig', [
            'stripeOrders' => $stripeOrders
        ]);
    }

    /**
     * Permet d'afficher de modifier l'adresse de livraison
     * 
     * @Route("/user/upd-order/{reference}", name="user_order")
     */
    public function order($reference, Request $request, OrderStripeRepository $orderStripeRepo, OrderStripe $orderStripe)
    {


        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(UpdateOrderType::class, $orderStripe);

        $form->handleRequest($request);

        $user = $this->getUser();
        $userName = $user->getUsername();
        dump($userName);


        $stripeOrder = $orderStripeRepo->findOneByReference($reference);
        $productName =  $stripeOrder->getProduct();

        dump($stripeOrder);
        $userOrder = $stripeOrder->getUsername();
        dump($userOrder);


        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();

            $entityManager->persist($stripeOrder);
            $entityManager->flush();


            $this->addFlash('success', "Addresse de livraison modifiée pour " . $productName . ".");
            return $this->redirectToRoute('user');
        }

        // $userId = $user->getId();
        if ($userName == $userOrder) {

            return $this->render('user/order-upd.html.twig', [
                'form' => $form->createView(),
                'stripeOrder' => $stripeOrder
            ]);
            # code...
        } else {
            return $this->redirectToRoute('user');
        }
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
    public function checkout($slug, Product $product, $stripeSK, Security $security): Response
    {

        $productName = $product->getName();
        $productImg = $product->getImg();
        $productPrice = $product->getPrice();
        $user = $security->getUser();
        $user = $user->getUsername();

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
                            'name' => $productName
                        ],
                        'unit_amount'  => $productPrice * 100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'metadata' => ['userMail' => $user, 'productName' => $productName, 'unique' => uniqid(), 'productImg' => $productImg],
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
