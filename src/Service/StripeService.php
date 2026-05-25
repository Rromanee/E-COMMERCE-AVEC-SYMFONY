<?php

namespace App\Service;

use App\Entity\Product;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeService
{
    public function createCheckoutSession(
        array $cart,
        callable $findProduct,
        UrlGeneratorInterface $urlGenerator
    ): Session {

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $lineItems = [];

        foreach ($cart as $item) {

            $product = $findProduct($item['product_id']);

            if (!$product instanceof Product) {
                continue;
            }

            $lineItems[] = [
                'quantity' => $item['qty'],

                'price_data' => [
                    'currency' => 'eur',

                    'unit_amount' => $product->getPrice() * 100,

                    'product_data' => [
                        'name' => $product->getName() . ' - Taille ' . $item['size'],
                    ],
                ],
            ];
        }

        return Session::create([
            'payment_method_types' => ['card'],

            'line_items' => $lineItems,

            'mode' => 'payment',

            'success_url' => $urlGenerator->generate(
                'app_stripe_success',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),

            'cancel_url' => $urlGenerator->generate(
                'app_cart',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ]);
    }
}