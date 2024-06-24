<?php

namespace Module\Order\Domain\Exceptions;

class OrderNotFoundException extends \Exception
{
    public function __construct(string $message = null)
    {
        parent::__construct($message ?? "order not found");
    }
}
