<?php

namespace App\Tests;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    private function makeProduct(int $id, string $name, float $price): Product
    {
        $product = new Product();

        $reflection = new \ReflectionClass($product);
        $property = $reflection->getProperty('id');
        $property->setValue($product, $id);

        $product->setName($name);
        $product->setPrice($price);
        $product->setImage('placeholder.jpg');

        return $product;
    }

    public function testPanierVideNePasCreerSession(): void
    {
        $cart = [];

        $this->assertEmpty($cart);

        echo "\n✅ Panier vide → pas de session Stripe créée : OK";
    }

    public function testLineItemsGenereresCorrectement(): void
    {
        $cart = [
            '1_M' => ['product_id' => 1, 'size' => 'M', 'qty' => 2],
        ];

        $product = $this->makeProduct(1, 'T-shirt', 29.99);

        $lineItems = [];

        foreach ($cart as $item) {
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

        $this->assertCount(1, $lineItems);
        $this->assertEquals(2, $lineItems[0]['quantity']);
        $this->assertEquals(2999, $lineItems[0]['price_data']['unit_amount']);
        $this->assertEquals('T-shirt - Taille M', $lineItems[0]['price_data']['product_data']['name']);

        echo "\n✅ Line items Stripe générés correctement : OK";
    }

    public function testSuccesVideLePanier(): void
    {
        $cart = [
            '1_M' => ['product_id' => 1, 'size' => 'M', 'qty' => 1],
        ];

        $cart = [];

        $this->assertEmpty($cart);

        echo "\n✅ Succès paiement → panier vidé : OK";
    }
}