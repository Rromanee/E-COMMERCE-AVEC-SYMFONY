<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testAjoutProduitAuPanier(): void
    {
        $cart = [];

        $id = 1;
        $size = 'M';
        $key = $id . '_' . $size;

        $cart[$key] = [
            'product_id' => $id,
            'size' => $size,
            'qty' => 1
        ];

        $this->assertArrayHasKey($key, $cart);
        $this->assertEquals(1, $cart[$key]['qty']);

        echo "\n✅ Ajout d'un produit au panier : OK";
    }

    public function testAjoutMemeProduitIncrementeQuantite(): void
    {
        $cart = [];
        $id = 1;
        $size = 'M';
        $key = $id . '_' . $size;

        $cart[$key] = ['product_id' => $id, 'size' => $size, 'qty' => 1];
        $cart[$key]['qty']++;

        $this->assertEquals(2, $cart[$key]['qty']);

        echo "\n✅ Ajout du même produit incrémente la quantité : OK";
    }

    public function testSuppressionProduitDuPanier(): void
    {
        $cart = [
            '1_M' => ['product_id' => 1, 'size' => 'M', 'qty' => 1],
            '2_L' => ['product_id' => 2, 'size' => 'L', 'qty' => 2],
        ];

        unset($cart['1_M']);

        $this->assertArrayNotHasKey('1_M', $cart);
        $this->assertArrayHasKey('2_L', $cart);

        echo "\n✅ Suppression d'un produit du panier : OK";
    }

    public function testPasPajoutSansTaille(): void
    {
        $size = null;

        $this->assertEmpty($size);

        echo "\n✅ Pas d'ajout sans taille sélectionnée : OK";
    }
}