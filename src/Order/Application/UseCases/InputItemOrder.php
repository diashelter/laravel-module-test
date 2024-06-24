<?php

namespace Module\Order\Application\UseCases;

readonly class InputItemOrder
{
    public function __construct(
        public int $productId,
        public int $quantity,
        public int $price,
    )
    {
    }
}
