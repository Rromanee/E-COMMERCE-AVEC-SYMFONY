<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        $featuredProducts = $productRepository->createQueryBuilder('p')
        ->where('p.isFeatured = :f')
        ->setParameter('f', true)
        ->orderBy('p.id', 'DESC')
        ->getQuery()
        ->getResult();

        return $this->render('home/index.html.twig', [
            'products' => $featuredProducts,
        ]);
    }
}
