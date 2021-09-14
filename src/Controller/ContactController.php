<?php

namespace App\Controller;


use App\Entity\Prospect;
use App\Form\ProspectType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Message;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\TextPart;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Part\Multipart\RelatedPart;
use Symfony\Component\Mime\Part\Multipart\AlternativePart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $prospect = new Prospect;
        // $prospect->setProduct($product);
        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

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


            // # 2

            // $headers = (new Headers())
            //     ->addMailboxListHeader('From', ['fabien@symfony.com'])
            //     ->addMailboxListHeader('To', ['foo@example.com'])
            //     ->addTextHeader('Subject', 'Important Notification');

            // $embeddedImage = new DataPart(fopen('img/TEKOMA.png', 'r'), null, 'image/png');
            // $imageCid = $embeddedImage->getContentId();

            // $textContent = new TextPart('Lorem ipsum...');
            // $htmlContent = new TextPart(sprintf(
            //     '<img src="cid:%s" width="100px"/> <h1>Lorem ipsum</h1> <p>...</p>',
            //     $imageCid
            // ), null, 'html');;
            // $bodyContent = new AlternativePart($textContent, $htmlContent);
            // $body = new RelatedPart($bodyContent, $embeddedImage);

            // $email = new Message($headers, $body);
            // $mailer->send($email);


            // # 1

            // $email =  (new Email())
            //     ->from($form->get('prospectEmail')->getData())
            //     ->to('contact@tekoma.com')
            //     ->subject('CONTACT - ' . $form->get('subject')->getData())
            //     // path of the Twig template to render
            //     ->html('<b>Sujet :</b>' . ' ' . $form->get('subject')->getData() . '<br><b>Message :</b>' . ' ' . $form->get('message')->getData() . '<br><b>Identité :</b>' . ' ' . $form->get('firstname')->getData() . ' ' . $form->get('lastname')->getData() . '<br><b>Email :</b>' . ' ' . $form->get('prospectEmail')->getData() . '');

            // $mailer->send($email);

            $this->addFlash('success', "Votre demande a bien été envoyée.");
            return $this->redirectToRoute('contact');
            // dump($prospect);
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView()
        ]);
    }
}
