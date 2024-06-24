<?php

namespace Module\Order\Application\UseCases;

use Illuminate\Support\Facades\DB;
use Module\Order\Domain\ItemEntity;
use Module\Order\Domain\OrderEntity;
use Module\Order\Domain\OrderRepository;
use Module\Order\Infra\Models\Order;

readonly class PlaceOrder
{
    public function __construct(
        private OrderRepository $orderRepository,
    )
    {
    }

    public function execute(InputPlaceOrder $inputPlaceOrder): void
    {
        $order = OrderEntity::create(customerId: $inputPlaceOrder->customerId);
        foreach ($inputPlaceOrder->items as $item) {
            $order->addItem(new ItemEntity(productId: $item->productId, quantity: $item->quantity, priceInCents: $item->price));
        }
        $this->orderRepository->store($order);
    }
}

