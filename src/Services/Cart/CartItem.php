<?php

namespace App\Services\Cart;


use App\Entity\Produits;

class CartItem
{
    public function __construct(
        public Produits $product,
        public $quantity
    ) {
    }

    public function getTotal(): int
    {
        return $this->product->getPrix() * $this->quantity;
    }
}
