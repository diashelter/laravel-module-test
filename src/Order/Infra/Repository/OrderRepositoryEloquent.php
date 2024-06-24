<?php

namespace Module\Order\Infra\Repository;

use Illuminate\Support\Facades\DB;
use Module\Order\Domain\OrderEntity;
use Module\Order\Domain\OrderRepository;
use Module\Order\Infra\Models\Order;

class OrderRepositoryEloquent implements OrderRepository
{
    public function store(OrderEntity $entity): void
    {
        DB::transaction(function () use ($entity) {
            $orderDb = Order::create([
                'customer_id' => $entity->getCustomerId(),
                'amount_in_cents' => $entity->getTotal(),
            ]);
            foreach ($entity->getItems() as $item) {
                $orderDb->items()->create([
                    'product_id' => $item->getProductId(),
                    'quantity' => $item->getQuantity(),
                    'amount_in_cents' => $item->getTotal(),
                ]);
            }
        });
    }
}
