<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddCreationType;
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
    public function create(Request $request, EntityManagerInterface $entityManager)
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
                $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $img->guessExtension();


                // Move the file to the directory where brochures are stored
                try {
                    $img->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'imgname' property to store the PDF file name
                // instead of its contents
                $product->setImg($newFilename);
            }


            // dump($product);
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
     * Permet d'afficher une seule creation
     * 
     * @Route("/admin/delete/{slug}", name="admin_delete")
     */
    public function showDelete($slug, ProductRepository $productRepo): Response
    {
        $product = $productRepo->findOneBySlug($slug);
        // (“dump and die”) helper function
        dump($product);
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
        $this->addFlash('danger', "Le produit $productName a bien été supprimé.");
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
            $flashProduct = '<b>désactivé</b>'; # code...
        } else {
            $product->setIsactive(true);
            $flashProduct = '<b>activé</b>';
        }
        $entityManager->persist($product);
        $entityManager->flush();
        // (“dump and die”) helper function
        // dump($product);
        $this->addFlash('success', "Le produit $productName a bien été $flashProduct.");

        return $this->redirectToRoute('admin');

        return $this->render('', [
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
