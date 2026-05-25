<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function list(ProductRepository $productRepository, Request $request): Response
    {
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');

        $queryBuilder = $productRepository->createQueryBuilder('p');

        if ($minPrice !== null && $minPrice !== '') {
            $queryBuilder->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $queryBuilder->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        $products = $queryBuilder->orderBy('p.name', 'ASC')->getQuery()->getResult();

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'current_min' => $minPrice,
            'current_max' => $maxPrice,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_detail')]
    public function detail(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $stocks = [
            'XS' => $product->getStockXS(),
            'S'  => $product->getStockS(),
            'M'  => $product->getStockM(),
            'L'  => $product->getStockL(),
            'XL' => $product->getStockXL(),
        ];

        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'stocks' => $stocks,
        ]);
    }
}