<?php

namespace Module\Order\Application\UseCases;

use Illuminate\Support\Facades\DB;
use Module\Order\Domain\ItemEntity;
use Module\Order\Domain\OrderEntity;
use Module\Order\Models\Order;
use Module\Order\Models\OrderItem;

class PlaceOrder
{
    public function __construct()
    {
    }

    public function execute(InputPlaceOrder $inputPlaceOrder)
    {
        $order = OrderEntity::create(customerId: $inputPlaceOrder->customerId);
        foreach ($inputPlaceOrder->items as $item) {
            $order->addItem(new ItemEntity(productId: $item->productId, quantity: $item->quantity, priceInCents: $item->price));
        }
        return DB::transaction(function () use ($order) {
            $orderDb = Order::create([
                'customer_id' => $order->getCustomerId(),
                'amount_in_cents' => $order->getTotal(),
            ]);
            foreach ($order->getItems() as $item) {
                $orderDb->items()->create([
                    'product_id' => $item->getProductId(),
                    'quantity' => $item->getQuantity(),
                    'amount_in_cents' => $item->getTotal(),
                ]);
            }
            return $orderDb;
        });
    }
}

