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
    public function user()
    {
        return $this->render('user/index.html.twig');
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
        // dump($userName);


        $stripeOrder = $orderStripeRepo->findOneByReference($reference);
        $productName =  $stripeOrder->getProduct();

        // dump($stripeOrder);
        $userOrder = $stripeOrder->getUsername();
        // dump($userOrder);


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
        $quantity = $product->getQuantity($slug);
        if ($quantity < 1) {
            return $this->redirectToRoute('contact_order', ['slug' => $slug]);
        }

        // si on utilise le ProductRepository à la place du paramconverter
        // $product = $productRepo->findOneBySlug($slug);

        // (“dump and die”) helper function
        // dump($product);

        return $this->render('user/payment.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * Permet la redirection vers le formulaire stripe
     * 
     * @Route("/user/checkout/{slug}", name="user_checkout")
     */
    public function checkout($slug, Product $product, $stripeSK, Request $request): Response
    {
        $user = $this->getUser();
        $id = $user->getId();


        $productName = $product->getName();
        $productImg = $product->getImg();
        $productPrice = $product->getPrice();
        $userName = $user->getUsername();

        $baseUrl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $imgStripe = $baseUrl . "/img/uploads/" . $productImg;


        Stripe::setApiKey($stripeSK);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'shipping_address_collection' => [
                'allowed_countries' => ['FR'],
            ],
            'customer_email' => $userName,
            'line_items' => [
                [
                    'price_data' => [
                        'currency'     => 'eur',
                        'product_data' => [
                            'name' => $productName,
                            'images' => [$imgStripe]
                        ],
                        'unit_amount'  => $productPrice * 100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'metadata' => ['userMail' => $userName, 'productName' => $productName, 'unique' => uniqid(), 'productImg' => $productImg, 'stripeImg' => $imgStripe, 'id' => $id],
            'mode'                 => 'payment',
            'submit_type'          => 'pay',
            'success_url'          => $this->generateUrl('success_url', array('slug' => $slug), UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', array('slug' => $slug), UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        // dump($session->id);
        // dd($session);
        return $this->redirect($session->url, 303);
    }

    /**
     * Permet de soustraire un quantité du produit précedemment acheté
     * 
     * @Route("/user/success-url/{slug}", name="success_url")
     */
    public function successUrl($slug, Product $product): Response
    {
        // $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $productName = $product->getName();
        $productQuantity = $product->getQuantity();


        if ($productQuantity > 0) {
            $product->setQuantity($productQuantity - 1);
        }
        $entityManager->persist($product);
        $entityManager->flush();

        $this->addFlash('success',  $productName . " commandé ! Merci 🙂. Suivez l'évolution de votre commande ici");
        return $this->redirectToRoute('user');
    }

    /**
     * Permet de rediréger l'utilisateur vers le produit précédent
     * 
     * @Route("/user/cancel-url/{slug}", name="cancel_url")
     */
    public function cancelUrl($slug): Response
    {
        return $this->redirectToRoute('user_creations_payment', array('slug' => $slug));
    }
}
