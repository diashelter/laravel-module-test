<?php

namespace Tests\Unit;

use Module\Order\Domain\ItemEntity;
use Module\Order\Domain\OrderEntity;
use Tests\TestCase;

class OderTest extends TestCase
{
    public function testCreateOrderWithItems()
    {
        $customerId = 1;
        $order = OrderEntity::create(customerId: $customerId);
        $order->addItem(new ItemEntity(productId: 1, quantity: 2, priceInCents: 1500));
        $order->addItem(new ItemEntity(productId: 2, quantity: 1, priceInCents: 1500));

        $this->assertInstanceOf(OrderEntity::class, $order);
        $this->assertCount(2, $order->getItems());
    }

    public function testValidationItemsOrder()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Items not set');
        $customerId = 1;
        $order = OrderEntity::create(customerId: $customerId);
        $order->getItems();
    }

    public function testCreateOrderWithItemsAndCalculateTotal()
    {
        $customerId = 1;
        $order = OrderEntity::create(customerId: $customerId);
        $order->addItem(new ItemEntity(productId: 1, quantity: 2, priceInCents: 1500));
        $order->addItem(new ItemEntity(productId: 2, quantity: 1, priceInCents: 2000));

        $this->assertEquals(5000, $order->getTotal());
    }
}
