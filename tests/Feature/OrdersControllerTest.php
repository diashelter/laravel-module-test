<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Module\Customer\Models\Customer;
use Module\Order\Models\Order;
use Module\Order\Models\OrderItem;
use Module\Product\Model\Product;
use Tests\TestCase;

class OrdersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidatedErrorsWhenCreateOrder()
    {
        $response = $this->postJson('/api/orders', [
            'customer_id' => 23,
            'items' => [
                [
                    'product_id' => 231,
                    'quantity' => 1,
                    'price_in_cents' => 345,
                ],
                [
                    'product_id' => 03,
                    'quantity' => 2,
                    'price_in_cents' => 0,
                ]
            ]
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('customer_id');
        $response->assertJsonValidationErrors('items.0.product_id');
        $response->assertJsonValidationErrors('items.1.product_id');
    }

    public function testCreateOrder()
    {
        $customer = Customer::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $response = $this->postJson('/api/orders', [
            'customer_id' => $customer->id,
            'items' => [
                [
                    'product_id' => $product1->id,
                    'quantity' => 1,
                    'price_in_cents' => $product1->price_in_cents,
                ],
                [
                    'product_id' => $product2->id,
                    'quantity' => 2,
                    'price_in_cents' => $product2->price_in_cents,
                ]
            ]
        ]);

        $total = (1 * $product1->price_in_cents) + (2 * $product2->price_in_cents);
        $response->assertCreated();
        $response->assertJsonPath('data.total', $total);
    }

    public function testAllOrders()
    {
        Order::factory(3)
            ->has(OrderItem::factory()->count(2), 'items')
            ->create();
        $response = $this->getJson('/api/orders');
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $this->assertDatabaseCount('orders', 3);
    }

    public function testSingleOrder()
    {
        $order = Order::factory()
            ->has(OrderItem::factory()->count(3), 'items')
            ->create();
        $response = $this->getJson("/api/orders/{$order->id}");
        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $order->id);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
        ]);
    }

    public function testDeleteOrder()
    {
        $order = Order::factory()
            ->has(OrderItem::factory()->count(2), 'items')
            ->create();
        $response = $this->deleteJson("/api/orders/{$order->id}");
        $response->assertStatus(204);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
        ]);
        $this->assertSoftDeleted('orders', [
            'id' => $order->id,
        ]);
    }
}
