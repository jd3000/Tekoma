<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddCreationType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = new Product();

        $form = $this->createForm(AddCreationType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $productName = $product->getName();
            $form->getData();
            // dump($form);
            $product->setSlug($form->get('name')->getData());
            $product->setIsactive(false);

            dump($product);
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', "Le produit $productName a bien été ajouté.");
            return $this->redirectToRoute('admin');
        }


        return $this->render('admin/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer seule creation
     * 
     * @Route("/admin/delete/{slug}", name="admin_delete")
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
        $this->addFlash('danger', "Le produit $productName a bien été supprimé.");
        return $this->redirectToRoute('admin');

        return $this->render('admin/delete.html.twig', [
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
        dump($product);
        return $this->render('admin/show.html.twig', [
            'product' => $product
        ]);
    }
}
