<?php

namespace App\Controller;

use App\Entity\Prospect;
use App\Form\ProspectType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepo, Request $request, MailerInterface $mailer): Response
    {
        $prospect = new Prospect;
        // $prospect->setProduct($product);
        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $prospect = $form->getData();

            $email =  (new Email())
                ->from($form->get('prospectEmail')->getData())
                ->to('contact@tekoma.com')
                ->subject('CONTACT - ' . $form->get('subject')->getData())
                // path of the Twig template to render
                ->html('<b>Sujet :</b>' . ' ' . $form->get('subject')->getData() . '<br><b>Message :</b>' . ' ' . $form->get('message')->getData() . '<br><b>Identité :</b>' . ' ' . $form->get('firstname')->getData() . ' ' . $form->get('lastname')->getData() . '<br><b>Email :</b>' . ' ' . $form->get('prospectEmail')->getData() . '');



            $mailer->send($email);
            $this->addFlash('success', "Votre demande a bien été envoyée.");
            return $this->redirectToRoute('home');
        }

        $products = $productRepo->findByExampleField(4);
        // (“dump and die”) helper function
        // dd($products);
        dump($products);
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }
}
