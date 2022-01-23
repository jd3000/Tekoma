<?php

namespace App\Controller;


use App\Entity\OrderStripe;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebhookController extends AbstractController
{
    /**
     * @Route("/webhook", name="webhook")
     */
    public function stripeWebhookAction(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            // http_response_code(400);
            return new Response(http_response_code(400));

            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                $entityManager = $this->getDoctrine()->getManager();
                $order = new OrderStripe();
                $user = User::class;
                // $user = $userRepository->find((int) $paymentIntent->metadata->id);
                $user = $userRepository->findOneById(intval($paymentIntent->metadata->id));
                $userId = $user->getId();
                $orderUserId = $order->setUser($user);
                $orderUserName = $order->setUsername($paymentIntent->metadata->userMail);
                $orderProductName = $order->setProduct($paymentIntent->metadata->productName);
                $orderProductImg = $order->setImg($paymentIntent->metadata->productImg);
                $orderReference = $order->setReference($paymentIntent->id);
                $orderAdresseName = $order->setName($paymentIntent->shipping->name);
                $orderUpdateCity = $order->setCity($paymentIntent->shipping->address->city);
                $orderUpdateAddressLine1 = $order->setAdressLine1($paymentIntent->shipping->address->line1);
                $orderUpdateAddressLine2 = $order->setAdressLine2($paymentIntent->shipping->address->line2);
                $orderUpdatePostalCode = $order->setPostalCode($paymentIntent->shipping->address->postal_code);
                $orderStatus = $order->setStatusStripe($paymentIntent->status);
                $orderIntent = $order->setIntent($paymentIntent->payment_intent);
                $now = new \DateTimeImmutable($request->get('time'));
                $orderCrationDate = $order->setCreatedAt($now);
                $orderUpadateDate = $order->setUpdatedAt($now);
                $orderPrice = $order->setPrice($paymentIntent->amount_total / 100);
                $orderIsSent = $order->setIsSent(false);

                // // $orderuserId = $order->setUser($user);
                $entityManager->persist($order);

                $entityManager->flush();
                // Then define and call a method to handle the successful payment intent.

                return new Response(http_response_code(200));

                break;
            case 'payment_intent.payment_failed':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                // handlePaymentMethodAttached($paymentMethod);
                break;
                // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return new Response(http_response_code(200));
    }
}
