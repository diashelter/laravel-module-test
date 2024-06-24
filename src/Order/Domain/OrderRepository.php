<?php

namespace Module\Order\Domain;

interface OrderRepository
{
    public function store(OrderEntity $entity): void;
}
