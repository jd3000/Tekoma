<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\Mapping\Id;
use Stripe\Checkout\Session;
use App\Form\RegistrationType;
use App\Service\StripeService;
use App\Form\RegistrationFormType;
use App\Repository\ProductRepository;
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
            'metadata' => ['userMail' => $user, 'productName' => $productName],
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

    /**
     * Permet d'afficher une seule creation sélectionnée par un utilisateur
     * 
     * @Route("/user/order/{slug}", name="user_order_create")
     */
    public function orderCreate($slug, Product $product, Request $request, Security $security): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        // $order = new Order();
        // $orderUserName = $order->setUsername('nom');
        // $orderProductName = $order->setProduct('produit');
        // $orderReference = $order->setReference('ref');
        // $orderUpdateCity = $order->setCity('city');
        // $orderUpdateAddressLine1 = $order->setAdressLine1('line1');
        // $orderUpdateAddressLine2 = $order->setAdressLine2('line2');
        // $orderUpdatePostalCode = $order->setPostalCode('post code');
        // $orderStatus = $order->setStatusStripe('status');
        // $now = new \DateTimeImmutable($request->get('time'));
        // $orderCrationDate = $order->setCreatedAt($now);
        // $orderUpadateDate = $order->setUpdatedAt($now);
        // $orderPrice = $order->setPrice($product->getPrice() / 100);
        // $orderIsSent = $order->setIsSent(false);
        // $user = $security->getUser();
        // $id = $order->setUser($user);
        // dd($id);
        // $entityManager->persist($order);
        // $entityManager->flush();

        // si on utilise le ProductRepository à la place du paramconverter
        // $product = $productRepo->findOneBySlug($slug);

        // (“dump and die”) helper function
        // dump($product);
        return $this->render('user/order.html.twig', [
            'product' => $product
        ]);
    }
}
