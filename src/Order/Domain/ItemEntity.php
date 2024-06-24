<?php

namespace Module\Order\Domain;

class ItemEntity
{
    public function __construct(
        private int $productId,
        private int $quantity,
        private int $priceInCents,
    )
    {
    }

    public function getTotal(): int
    {
        return $this->priceInCents * $this->quantity;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
