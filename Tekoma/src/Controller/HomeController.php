<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Prospect;
use App\Service\HCaptcha;
use App\Form\ProspectType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Repository\ProductRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepo, Request $request, MailerInterface $mailer, HCaptcha $hcaptcha): Response
    {
        $user = $this->getUser();
        $prospect = new Prospect;
        // $prospect->setProduct($product);
        if ($user) {
            $email = $user->getUsername();
            $prospect->setProspectEmail($email);
        }

        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);

        $ishcaptchaValid = $hcaptcha->isHCaptchaValid();
        $verif = false;

        if ($form->isSubmitted() && $form->isValid() && $ishcaptchaValid['success'] == false) {
            $this->addFlash('success', "<span data-verified-form=\"form_prospect\">✔ Les champs sont corrects 🖱 Cliquez sur Envoyer</span>");
            $verif = $form->isValid();
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "<span data-verified-form=\"form_prospect\">❌Les champs sont incorrects - Votre demande ne pourra pas être envoyée</span>");
            $verif = $form->isValid();
        }

        if ($form->isSubmitted() && $form->isValid() && $ishcaptchaValid['success'] == true) {

            $prospect = $form->getData();

            // # 3
            $email = (new TemplatedEmail())
                ->from($form->get('prospectEmail')->getData())
                ->to(new Address('contact@tekoma.com'))
                ->subject('CONTACT - ' . $form->get('subject')->getData())

                // path of the Twig template to render
                ->htmlTemplate('emails/template.html.twig')

                // pass variables (name => value) to the template
                ->context([
                    'expiration_date' => new \DateTime('+7 days'),
                    'firstname' => $form->get('firstname')->getData(),
                    'lastname' => $form->get('lastname')->getData(),
                    'prospectEmail' => $form->get('prospectEmail')->getData(),
                    'subject' => $form->get('subject')->getData(),
                    'message' => $form->get('message')->getData(),

                ]);

            $mailer->send($email);
            $this->addFlash('success', "Votre demande a bien été envoyée.");
            return $this->redirectToRoute('home');
        }
        $products = $productRepo->findActiveHighlightedField(4);

        // (“dump and die”) helper function
        // dd($products);
        // dump($products);
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'response' => $hcaptcha->isHCaptchaValid(),
            'verif' => $verif
        ]);
    }
}
