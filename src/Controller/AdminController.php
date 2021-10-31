<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddCreationType;
use App\Service\FileUploader;
use App\Form\UpdateCreationType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class AdminController extends AbstractController
{
    /**    
     * @Route("/admin", name="admin")     
     */
    public function index(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findAll();
        // (“dump and die”) helper function
        // dd($products);
        return $this->render('admin/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * Permet d'afficher une seule creation
     * 
     * @Route("/admin/new", name="admin_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = new Product();

        $form = $this->createForm(AddCreationType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productName = $product->getName();
            $form->getData();
            $img = $form->get('img')->getData();

            // dump($form);
            $product->setSlug($form->get('name')->getData());
            $product->setIsactive(false);

            if ($img) {

                $imgFileName = $fileUploader->upload($img);
                $product->setImg($imgFileName);
            }


            // dump($product);
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', "<b data-product-id=\"$productName\">$productName</b> a bien été ajouté.");
            return $this->redirectToRoute('admin');
        }


        return $this->render('admin/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet d'afficher une seule creation
     * 
     * @Route("/admin/delete/{slug}", name="admin_delete")
     */
    public function showDelete($slug, ProductRepository $productRepo): Response
    {
        $product = $productRepo->findOneBySlug($slug);
        // (“dump and die”) helper function
        // dump($product);
        return $this->render('admin/delete.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * Permet de supprimer seule creation
     * 
     * @Route("/admin/delete/confirm/{slug}", name="admin_delete_confirm")
     */
    public function delete($slug, ProductRepository $productRepo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $productRepo->findOneBySlug($slug);
        $productName = $product->getName();


        $entityManager->remove($product);
        $entityManager->flush();
        // (“dump and die”) helper function
        // dump($product);
        $this->addFlash('danger', "<b>$productName</b> a bien été supprimé.");
        return $this->redirectToRoute('admin');

        return $this->render('admin/delete.html.twig', [
            'product' => $product
        ]);
    }


    /**
     * Permet d'activer ou de désactiver une seule creation
     * 
     * @Route("/admin/activate/confirm/{slug}", name="admin_activate_confirm")
     */
    public function activate($slug, ProductRepository $productRepo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $productRepo->findOneBySlug($slug);
        $productName = $product->getName();
        if ($product->getIsActive()) {
            $product->setIsactive(false);
            $flashProduct = 'désactivé'; # code...
        } else {
            $product->setIsactive(true);
            $flashProduct = 'activé';
        }
        $entityManager->persist($product);
        $entityManager->flush();
        // (“dump and die”) helper function
        // dump($product);
        $this->addFlash('success', "<b data-product-id=\"$productName\">$productName</b> a bien été $flashProduct.");

        return $this->redirectToRoute('admin');

        // return $this->render('', [
        //     'product' => $product
        // ]);
    }

    /**
     * Permet de dé-highlighter une seule creation
     * 
     * @Route("/admin/unhighlight/confirm/{slug}", name="admin_unhighlight_confirm")
     */
    public function unhighlight($slug, ProductRepository $productRepo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $productRepo->findOneBySlug($slug);
        $productName = $product->getName();
        $productHighlighted = $product->getHighlighted();

        if ($productHighlighted !== null) {
            $product->setHighlighted(null);
            $flashProduct = 'a bien été retiré de la page d\'accueil'; # code...
        }
        $entityManager->persist($product);
        $entityManager->flush();
        // (“dump and die”) helper function
        // dump($product);
        $this->addFlash('success', "<b data-product-id=\"$productName\">$productName</b> $flashProduct.");

        return $this->redirectToRoute('admin');

        // return $this->render('', [
        //     'product' => $product
        // ]);
    }

    /**
     * Permet d'highlighter une seule creation
     * 
     * @Route("/admin/highlight/confirm/{slug}", name="admin_highlight_confirm")
     */
    public function highlight($slug, ProductRepository $productRepo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $productRepo->findOneBySlug($slug);
        $productName = $product->getName();
        // $products = $productRepo->findHighlightedField(4);

        $productHighlightedValue1 = $productRepo->findOneHighlightedBySomeField(1);
        $productHighlightedValue2 = $productRepo->findOneHighlightedBySomeField(2);
        $productHighlightedValue3 = $productRepo->findOneHighlightedBySomeField(3);


        if (!$productHighlightedValue1) {
            $product->setHighlighted(1);
            $flashClass = 'success';
            $flashProduct = 'a bien été ajouté à la page d\'accueil';
        } elseif (!$productHighlightedValue2) {
            $product->setHighlighted(2);
            $flashClass = 'success';
            $flashProduct = 'a bien été ajouté à la page d\'accueil';
        } elseif (!$productHighlightedValue3) {
            $product->setHighlighted(3);
            $flashClass = 'success';
            $flashProduct = 'a bien été ajouté à la page d\'accueil';
        } else {
            $flashClass = 'warning';
            $flashProduct = 'ne peut pas être ajouté à la page d\'accueil (maximum = 3), vous devez retirer une création de la page d\'accueil au préalable';
        }

        $entityManager->persist($product);
        $entityManager->flush();

        $this->addFlash($flashClass, "<b data-product-id=\"$productName\">$productName</b> $flashProduct.");

        return $this->redirectToRoute('admin');
        // return $this->render('', [
        //     'product' => $product
        // ]);
    }

    /**
     * Permet d'afficher une seule creation
     * 
     * @Route("/update/{slug}", name="admin_update")
     */
    public function update($slug, Request $request, Product $product, FileUploader $fileUploader): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(UpdateCreationType::class, $product);

        $form->handleRequest($request);
        $unmodifiedImg = $product->getImg();

        // dump($product);
        // dump($unmodifiedImg);
        // dump($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $productName = $product->getName();
            $form->getData();


            $img = $form->get('img')->getData();

            // dump($form);
            // $product->setSlug($form->get('name')->getData());

            if ($img) {
                $imgFileName = $fileUploader->upload($img);
                $product->setImg($imgFileName);
            } else {
                $product->setImg($unmodifiedImg);
            }



            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', "<b data-product-id=\"$productName\">$productName</b> a bien été modifié.");
            return $this->redirectToRoute('admin');
        }


        // (“dump and die”) helper function
        // dump($product);
        return $this->render('admin/update.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }


    /**
     * Permet d'afficher une seule creation
     * 
     * @Route("/admin/{slug}", name="admin_show")
     */
    public function show($slug, ProductRepository $productRepo): Response
    {
        $product = $productRepo->findOneBySlug($slug);
        // (“dump and die”) helper function
        // dump($product);
        return $this->render('admin/show.html.twig', [
            'product' => $product
        ]);
    }
}
