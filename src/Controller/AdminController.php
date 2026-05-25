<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(
        ProductRepository $productRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $products = $productRepository->findAll();

        $newProduct = new Product();
        $form = $this->createForm(ProductType::class, $newProduct);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/images/products',
                    $newFilename
                );

                $newProduct->setImage($newFilename);
            }

            $entityManager->persist($newProduct);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'placeholder' => 'placeholder.jpeg'
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'admin_delete')]
    public function delete(
        Product $product,
        EntityManagerInterface $entityManager
    ): Response {

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/edit/{id}', name: 'admin_edit')]
    public function edit(
        Product $product,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/images/products',
                    $newFilename
                );

                $product->setImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }
}