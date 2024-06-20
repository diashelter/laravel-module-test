<?php
declare(strict_types=1);

namespace Module\Order\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'total' => $this->amount_in_cents,
            'items' => $this->generateItemsOrders($this->items->toArray()),
        ];
    }

    private function generateItemsOrders(array $items): array
    {
        return array_map(function ($item) {
            return [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'amount_in_cents' => $item['amount_in_cents'],
            ];
        }, $items);
    }
}
