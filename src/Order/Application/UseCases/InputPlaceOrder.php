<?php

namespace Module\Order\Application\UseCases;

readonly class InputPlaceOrder
{
    public function __construct(
        public int   $customerId,
        /**
         * @var InputItemOrder[]
         */
        public array $items,
    )
    {
    }
}
