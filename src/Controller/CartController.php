<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function cart(Request $request, ProductRepository $productRepository): Response
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        $cartDetails = [];

        foreach ($cart as $key => $item) {

            $product = $productRepository->find($item['product_id']);

            if ($product) {
                $cartDetails[$key] = [
                    'product' => $product,
                    'size' => $item['size'],
                    'qty' => $item['qty']
                ];
            }
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cartDetails
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(int $id, Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        $size = $request->request->get('size');

        if (!$size) {
            return $this->redirectToRoute('app_product_detail', ['id' => $id]);
        }

        $key = $id . '_' . $size;

        if (isset($cart[$key])) {
            $cart[$key]['qty']++;
        } else {
            $cart[$key] = [
                'product_id' => $id,
                'size' => $size,
                'qty' => 1
            ];
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{key}', name: 'app_cart_remove')]
    public function remove(string $key, Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        unset($cart[$key]);

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }
}