<?php

namespace Module\Order\Controllers\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Module\Order\Application\UseCases\InputItemOrder;
use Module\Order\Application\UseCases\InputPlaceOrder;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price_in_cents' => ['required', 'integer', 'min:0'],
        ];
    }

    public function toConvertInputOrder(): InputPlaceOrder
    {
        $items = array_map(function ($item): InputItemOrder {
            return new InputItemOrder(
                productId: $item['product_id'],
                quantity: $item['quantity'],
                price: $item['price_in_cents'],
            );
        }, $this->validated('items'));
        return new InputPlaceOrder(
            customerId: (int)$this->validated('customer_id'),
            items: $items,
        );
    }
}
