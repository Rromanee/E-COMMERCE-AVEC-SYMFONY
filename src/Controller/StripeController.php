<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\StripeService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/checkout', name: 'app_stripe_checkout')]
    public function checkout(
        Request $request,
        ProductRepository $productRepository,
        StripeService $stripeService
    ): Response {

        $cart = $request->getSession()->get('cart', []);

        if (!$cart) {
            return $this->redirectToRoute('app_cart');
        }

        $session = $stripeService->createCheckoutSession(
            $cart,
            fn($id) => $productRepository->find($id),
            $this->container->get('router')
        );

        return $this->redirect($session->url);
    }

    #[Route('/checkout/success', name: 'app_stripe_success')]
    public function success(Request $request): Response
    {
        $request->getSession()->remove('cart');

        $this->addFlash('success', 'Paiement effectué avec succès.');

        return $this->redirectToRoute('app_cart');
    }
}